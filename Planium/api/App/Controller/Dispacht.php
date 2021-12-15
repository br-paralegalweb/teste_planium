<?php

namespace App\Controller;

use App\Src\Traits\TraitsParser;

class Dispacht
{
    use TraitsParser;

    public function __construct()
    {
        $obj = get_class();
        $method = $this->getRoute();

        if (method_exists($obj, $this->getRoute())) {
            $this->$method();
        } else {
            echo $this->semMethod();
        }
    }

    private function consultarPlanos()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $d = $this->getPlanos();
            echo $d;
        } else {
            $this->error404();
        }
    }

    private function getPlanos()
    {
        $url = 'App/Persistence/plans.json';
        $contents = file_get_contents($url);

        return $contents;
    }

    private function consultarPrecos()
    {
        var_dump($_POST);
    }

    private function cadastrarProposta()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = file_get_contents('php://input');
            $array = json_decode($input, true);
            unset($array['plano_id']);

            $e = $this->createProposta($array);
            if ($e['status'] === 'Error') {
                // http_response_code(401);
                echo json_encode($e, JSON_PRETTY_PRINT);
                exit;
            } else {
                $e['status'] = "Proposta criada com sucesso";
                echo json_encode($e, JSON_PRETTY_PRINT);
                // http_response_code(201);
            }
        } else {
            $this->error404();
        }
    }

    private function createProposta($dados)
    {
        /* Chama o banco */
        $url = 'App/Persistence/beneficiarios.json';
        $contents = file_get_contents($url);
        $banco = json_decode($contents, true);

        if (is_null($banco)) {
            $banco = [
                "proposta" => [],
            ];
            /* Gerar um ID */
            $dados['id'] = time();
            /* Colocar os dados dentro da proposta */
            $banco['proposta'][] = $dados;
            /* salvar no arquivo */
            file_put_contents($url, json_encode($banco));

            return $banco;
        } else {
            foreach ($banco['proposta'] as $key => $value) {
                if ($value['nome_proposta'] == $dados['nome_proposta']) {
                    $tem[] = $value;
                }
            }
            /* Se tiver é porque o nome da proposta já tem no banco */
            if (is_array($tem)) {
                $c = [
                    'status' => "Error",
                    "mensagem" => "Nome já existe no banco",
                ];
                return $c;
            } else {
                /* Inclui um id para identificação da proposta */
                $dados['id'] = time();
                /* Se não tiver uma proposta (banco) cria uma proposta */
                if (!$banco['proposta']) {
                    $banco['proposta'] = [];
                }
                /* Pegar o ID do plano para salvar */
                $h = $this->idPlano($dados);
                if (is_null($h['plano_id'])) {
                    $e = [
                        "status" => "Error",
                        "Mensagem" => "Não conseguimos identificar o ID do plano escolhido.",
                    ];
                    return json_encode($e, JSON_PRETTY_PRINT);
                }
                /* inclui os dados do form no banco */
                $banco['proposta'][] = $dados;
                /* grava */
                file_put_contents($url, json_encode($banco));

                return $dados;
            }
        }
    }

    private function consultarProposta()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $d = $this->getPropostas();
            $dados = json_decode($d, true);

            $count = is_array($dados['proposta']) ? count($dados['proposta']) : 'Sem dados';

            if (is_numeric($count)) {
                foreach ($dados['proposta'] as $key => $value) {
                    $a[]['nome_proposta'] = $value['nome_proposta'];
                    $b[]['id'] = $value['id'];
                }
                echo json_encode($a);
            } else {
                echo $count;
            }
        } else {
            $this->error404();
        }
    }

    private function getPropostas()
    {
        $url = 'App/Persistence/beneficiarios.json';
        $contents = file_get_contents($url);

        return $contents;
    }

    private function criarBeneficiario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = file_get_contents('php://input');
            $array = json_decode($input, true);

            /* Validar retorno da pesquisa do id do plano */

            $d = $this->createBeneficiario($array);
            $d['mensagem'] = 'Criado com sucesso';
            echo json_encode($d);
        } else {
            $this->error404();
        }
    }

    private function createBeneficiario($dados)
    {
        /* Chama o banco */
        $url = 'App/Persistence/beneficiarios.json';
        $contents = file_get_contents($url);
        $banco = json_decode($contents, true);
        /* Verificar se existe a proposta */
        foreach ($banco['proposta'] as $key => $value) {
            if ($value['nome_proposta'] == $dados['nome_proposta']) {
                $plano[] =  $value;
                $ke[] = $key;
            }
        }
        /* Se vier mais de um array tem algum problema */
        if ($ke > 1) {
            $dados['ID'] = time();
            array_filter($dados);
            /* Incluir na base uma coluna para beneficiários */
            if (!$banco['proposta'][$ke[0]]['beneficiarios']) {
                $banco['proposta'][$ke[0]]['beneficiarios'] = [];
            }
            /* Excluir o array nome_proposta */
            unset($dados['nome_proposta']);
            /* inclui os dados do form no banco */
            $banco['proposta'][$ke[0]]['beneficiarios'][] = $dados;
            /* grava */
            file_put_contents($url, json_encode($banco));

            return $dados;
        } else {
            $e = [
                "status" => "Error",
                "Mensagem" => "Não conseguimos identificar sua proposta.",
            ];
            echo json_encode($e, JSON_PRETTY_PRINT);
        }
    }

    /* Qualquer coisa excluir - preservado o original */
    private function carregarPrecos($dados)
    {
        $url = 'App/Persistence/prices.json';
        $contents = file_get_contents($url);
        $precosBD = json_decode($contents, true);
        /* Saber a quantidade de benefiários tem nesta proposta */
        $vidas = is_array($dados) ? count($dados) : 1;
        $plano = end($dados);
        /* Retorna do banco os planos da escolha do cliente */
        foreach ($precosBD as $key => $value) {
            if ($value['codigo'] == $plano['plano_id']) {
                $r[] = $value;
            }
        }
        /* Ordenando os arrays pelo minimo de vidas, calculando do maior para o menor */
        array_multisort(array_map(function ($element) {
            return $element['minimo_vidas'];
        }, $r), SORT_ASC, $r);
        /* filtra o resultado para trazer o último array que se aproxima de mínimo de vidas */
        $count = is_countable($r) ? count($r) : 1;
        if ($count >= 1) {
            foreach ($r as $key => $value) {
                if ($vidas >= $value['minimo_vidas']) {
                    $g[] = $value;
                }
            }
            return end($g);
        } else {
            return "Error";
        }
    }

    private function idPlano($plano)
    {
        $url = 'App/Persistence/plans.json';
        $contents = file_get_contents($url);
        $json = json_decode($contents, true);
        foreach ($json as $key => $value) {
            if ($value['nome'] == $plano['plano']) {
                $o[] = $value;
            }
        }
        $plano['plano_id'] = (string)$o[0]['codigo'];

        return $plano;
    }

    private function consultaListaProposta()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            /* Dados do form */
            $input = file_get_contents('php://input');
            $array = json_decode($input, true);
            /* Banco de dados */
            $d = $this->getPropostas();
            $banco = json_decode($d, true);
            /* Filtro do banco */
            foreach ($banco['proposta'] as $key => $value) {
                if ($value['nome_proposta'] == $array['nome_proposta']) {
                    $itens[] = $value;
                }
            }
            if (!is_null($itens[0]['beneficiarios'])) {
                $beneficiarios = $itens[0]['beneficiarios'];
                /* pegar id plano  e colocar no array do beneficiário */
                $p = $this->saberIdPlano($array);
                $beneficiarios[]['plano_id'] = $p['plano_id'];
                /* Enviar a quantidade de beneficiários */
                $j = $this->carregarPrecos($beneficiarios);
                /* Formatar dados para a frontEnd */
                $z = $this->putPriceInBeneficiario($itens[0]['beneficiarios'], $j, $itens[0]['plano']);

                echo json_encode($z);
            } else {
                $semDados = [
                    'status' => 'Error',
                    'mensagem' => 'sem dados',
                ];
                echo json_encode($semDados);
            }
        } else {
            $this->error404();
        }
    }

    private function saberIdPlano($dados)
    {
        $contents = $this->getPropostas();
        $banco = json_decode($contents, true);
        /* Verificar se existe a proposta */
        foreach ($banco['proposta'] as $key => $value) {
            if ($value['nome_proposta'] == $dados['nome_proposta']) {
                $plano[] =  $value;
                $ke[] = $key;
            }
        }
        $dados = [['plano' => $plano[0]['plano']]];
        $c = $this->idPlano($dados[0]);

        return $c;
    }

    private function putPriceInBeneficiario($dados, $tablePrices, $plano)
    {
        foreach ($dados as $key => $value) {
            if (array_key_exists($value['faixa'], $tablePrices)) {
                $retorno[] = [
                    'nome' => $value['nome'],
                    'idade' => $value['idade'],
                    'preco' => $tablePrices[$value['faixa']],
                    'plano' => $plano,
                ];
            }
        }
        return $retorno;
    }

    private function criarProposta()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            /* Dados do form */
            $input = file_get_contents('php://input');
            $array = json_decode($input, true);

            $d = $this->getPropostas();
            $banco = json_decode($d, true);
            /* Filtro do banco */
            foreach ($banco['proposta'] as $key => $value) {
                if ($value['nome_proposta'] == $array['nome_proposta']) {
                    $itens[] = $value;
                }
            }
            if (!is_null($itens[0]['beneficiarios'])) {
                $beneficiarios = $itens[0]['beneficiarios'];
                /* pegar id plano  e colocar no array do beneficiário */
                $p = $this->saberIdPlano($array);
                $beneficiarios[]['plano_id'] = $p['plano_id'];
                /* Enviar a quantidade de beneficiários */
                $j = $this->carregarPrecos($beneficiarios);
                /* Formatar dados para a frontEnd */
                $z = $this->putPriceInBeneficiario($itens[0]['beneficiarios'], $j, $itens[0]['plano']);

                $pegarValorTotal = $this->somarArray($z);

                $r = $this->createBancoProposta($z, $itens[0]['nome_proposta'], $pegarValorTotal);
            } else {
                $semDados = [
                    'status' => 'Error',
                    'mensagem' => 'sem dados',
                ];
                echo json_encode($semDados);
            }
        } else {
            $this->error404();
        }
    }

    private function createBancoProposta($arg, $nomeProposta, $total)
    {
        /* Chama o banco */
        $url = 'App/Persistence/proposta.json';
        $contents = file_get_contents($url);
        $banco = json_decode($contents, true);

        if (is_null($banco)) {
            $banco = [
                'propostas_fechadas' => [],
            ];
            $dados['beneficiarios'] = $arg;
            $dados['nome_proposta'] = $nomeProposta;
            $dados['valor_total_proposta'] = $total;
            $dados['id_dataTime'] = time();
            $banco['propostas_fechadas'][] = $dados;

            file_put_contents($url, json_encode($banco));

            echo json_encode($dados);
        } else {
            /* não precisa criar o banco */
            $dados['beneficiarios'] = $arg;
            $dados['nome_proposta'] = $nomeProposta;
            $dados['valor_total_proposta'] = $total;
            $dados['id_dataTime'] = time();
            $banco['propostas_fechadas'][] = $dados;

            file_put_contents($url, json_encode($banco));

            echo json_encode($dados);
        }

        exit;
    }

    private function somarArray($dados)
    {
        foreach ($dados as $key => $value) {
            $ret[] = $value['preco'];
        }

        return array_sum($ret);
    }

    private function semMethod()
    {
        header('Content-Type: application/json');
        $e = [
            "status" => "200",
            "Mensagem" => "Ainda não construímos algo legal, mas estamos trabalhando nisso.",
        ];
        echo json_encode($e, JSON_PRETTY_PRINT);
        http_response_code(200);
    }

    private function error404()
    {
        header("Content-Type: application/json; charset=utf-8");
        $e = [
            "status" => "Error 404",
            "Error" => "Não encontramos caminho adequado para atender sua solicitação.",
        ];
        echo json_encode($e, JSON_PRETTY_PRINT);
        http_response_code(404);
    }
}
