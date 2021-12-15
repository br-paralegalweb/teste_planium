<?php

namespace App\Controller;

use App\Model\Lead;
use App\Src\Classes\ClassRender;
use App\Src\Traits\TraitsParser;

class Dispacht extends ClassRender
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

    private function render()
    {
        $this->setDir('Formulario');
        $this->renderLayout();
        $this->addMain();
    }

    private function proposta()
    {
        $this->setDir('Proposta');
        $this->renderLayout();
        $this->addMain();
    }

    private function listaPropostasCriadas()
    {
        $this->setDir('Lista');
        $this->renderLayout();
        $this->addMain();
    }

    private function recebeProposta()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            $this->mandarPropostaApi($dadosForm);
        }
    }

    private function mandarPropostaApi($form)
    {
        $dados = json_encode($form);
        $url = "localhost/planium/api/api_cadastro_proposta";
        $token = "Paralegalweb3221";
        $headers = [
            "content-Type: application/json",
            'Authorization: Bearer 123456765434566543443',
            "Usuario: {$token}",
        ];
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $dados,
        ]);
        $resp = curl_exec($ch);
        curl_close($ch);

        $q = json_decode($resp, true);
        if ($q['status'] === 'Error') {
            echo $resp;
            http_response_code(401);
        } else {
            echo $resp;
        }
    }

    private function propostasCriadas()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->listaPropostaCriadaAPI();
            http_response_code(200);
            exit;
        } else {
            $this->error404();
        }
    }

    private function listaPropostaCriadaAPI()
    {
        $dados = json_encode(['dados' => "qualquer"]);
        $url = "localhost/planium/api/api_consulta_propostasCriadas";
        $token = "Paralegalweb3221";
        $headers = [
            "content-Type: application/json",
            'Authorization: Bearer 123456765434566543443',
            "Usuario: {$token}",
        ];
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $dados,
        ]);
        $resp = curl_exec($ch);
        curl_close($ch);

        echo $resp;
    }

    private function validarDadosForm()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            $setar = new Lead($dadosForm, 'validarDadosForm');
            /* Validar dados do formulário */
            if (is_null($setar->getError())) {
                /* Enviar dados para API */
                $r = $this->createBeneficarioAPI($setar->retornaJson());

                $t = json_decode($r, true);

                echo $r;
                exit;
                /* Validar retorno da API */
                if (isset($t['mensagem']) && !is_null($t['mensagem'])) {
                    echo json_encode($t);
                } else {
                    $a = [
                        'status' => 'Error',
                        'mensagem' => "Não foi possível gravar os dados no banco.",
                    ];
                    echo json_encode($a);
                    http_response_code(401);
                    exit;
                }
            } else {
                $a = [
                    'status' => 'Error',
                    'mensagem' => $setar->getError(),
                ];
                echo json_encode($a);
                http_response_code(401);
                exit;
            }
        } else {
            $this->error404();
        }
    }

    private function createBeneficarioAPI($dados)
    {
        $url = "localhost/planium/api/api_create_beneficiario";
        $token = "Paralegalweb3221";
        $headers = [
            "content-Type: application/json",
            'Authorization: Bearer 123456765434566543443',
            "Usuario: {$token}",
        ];
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $dados,
        ]);
        $resp = curl_exec($ch);
        curl_close($ch);

        return $resp;
    }

    private function carregarPlanos()
    {
        $d = $this->planosNaApi();

        echo $d;
    }

    private function planosNaApi()
    {
        $dados = json_encode(['dados' => "qualquer"]);
        $url = "localhost/planium/api/api_consulta_planos";
        $token = "Paralegalweb3221";
        $headers = [
            "content-Type: application/json",
            'Authorization: Bearer 123456765434566543443',
            "Usuario: {$token}",
        ];
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $dados,
        ]);
        $resp = curl_exec($ch);
        curl_close($ch);

        return $resp;
    }

    private function planos()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->carregarPlanos();
            http_response_code(200);
            exit;
        } else {
            $this->error404();
        }
    }

    private function getPropostasNome()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            $r = $this->consultarPropostaNomeAPI($dadosForm);

            echo $r;

            $this->soma = $this->somarArray(json_decode($r, true));
        } else {
            $this->error404();
        }
    }

    private function consultarPropostaNomeAPI($form)
    {
        $dados = json_encode($form);
        $url = "localhost/planium/api/api_lista_por_proposta";
        $token = "Paralegalweb3221";
        $headers = [
            "content-Type: application/json",
            'Authorization: Bearer 123456765434566543443',
            "Usuario: {$token}",
        ];
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $dados,
        ]);
        $resp = curl_exec($ch);
        curl_close($ch);
        $q = json_decode($resp, true);
        if ($q['status'] === 'Error') {
            echo $resp;
            http_response_code(401);
            // $_SESSION['msg'] = $q['mensagem'];
            exit;
        } else {


            return $resp;
        }
    }

    private function somarArray($dados)
    {
        foreach ($dados as $key => $value) {
            $ret[] = $value['preco'];
        }

        return array_sum($ret);
    }

    private function createProposta()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            $r = $this->createPropostaAPI($dadosForm);
            echo $r;
        } else {
            $this->error404();
        }
    }

    private function createPropostaAPI($form)
    {
        $dados = json_encode($form);
        $url = "localhost/planium/api/api_create_proposta";
        $token = "Paralegalweb3221";
        $headers = [
            "content-Type: application/json",
            'Authorization: Bearer 123456765434566543443',
            "Usuario: {$token}",
        ];
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $dados,
        ]);
        $resp = curl_exec($ch);
        curl_close($ch);
        $q = json_decode($resp, true);
        if ($q['status'] === 'Error') {
            echo $resp;
            http_response_code(401);
            exit;
        } else {
            return $resp;
        }
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
