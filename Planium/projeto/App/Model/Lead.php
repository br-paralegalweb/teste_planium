<?php

namespace App\Model;

class Lead
{
    private $id;
    private $nome;
    private $idade;
    private $quant_vidas;
    private $plano;
    private $plano_id;
    private $faixa;
    private $price;
    private $nome_proposta;
    private $date;
    private $error;

    public function __construct($dados, $method)
    {
        if (!is_null($method) || !empty($method)) {
            $obj = get_class();
            if (method_exists($obj, $method)) {
                $this->$method($dados);
            } else {
                http_response_code(400);
                exit("Error");
            }
        } else {
            http_response_code(400);
            exit('Error');
        }
    }

    public function __toString()
    {
        return
            $this->getId() . ',' .
            $this->getNome() . ',' .
            $this->getIdade() . ',' .
            $this->getQuant_vidas() . ',' .
            $this->getPlano() . ',' .
            $this->getPlano_id() . ',' .
            $this->getPrice() . ',' .
            $this->getFaixa() . ',' .
            $this->getNome_proposta() . ',' .
            $this->getDate() . ',' .
            $this->getError();
    }

    private function validarDadosForm($dados)
    {
        if (is_null($dados['nome_beneficiario']) || empty($dados['nome_beneficiario'])) {
            return $this->setError('Campo Nome não pode ser vazio');
        } else if (is_null($dados['idade_beneficiario']) || empty($dados['idade_beneficiario']) || $dados['idade_beneficiario'] > 115) {
            if ($dados['idade_beneficiario'] > 115) {
                return $this->setError(' Campo Idade superior a 115 anos?');
            } else {
                $this->setError('Campo Idade não pode ser vazio.');
            }
        } 
        else if (is_null($dados['propostas']) || empty($dados['propostas'])) {
            return $this->setError('Campo suas propostas deve ser escolhida uma opção.');
        } 
        // else if (is_null($dados['plano']) || empty($dados['plano'])) {
        //     $this->setError('Campo plano não pode ser vazio.');
        // } 
        else {
            $this->setarDadosForm($dados);
        }
    }

    private function setarDadosForm($dados)
    {
        $this->setNome($dados['nome_beneficiario']);
        $this->setIdade($dados['idade_beneficiario']);
        $this->setNome_proposta($dados['propostas']);
        // $this->setPlano($dados['plano']);
        $this->setFaixa('');
    }

    public function retornaJson()
    {
        $j = [
            "nome" => $this->getNome(),
            "idade" => $this->getIdade(),
            // "vidas" => $this->getQuant_vidas(),
            // "plano" => $this->getPlano(),
            // 'plano_id' => $this->getPlano_id(),
            "preco" => $this->getPrice(),
            "faixa" => $this->getFaixa(),
            "nome_proposta" => $this->getNome_proposta(),
            "ID" => $this->getId(),
        ];
        return json_encode($j);
    }


    public function getId()
    {
        return $this->id;
    }
    private function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function getNome()
    {
        return $this->nome;
    }
    private function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }
    public function getIdade()
    {
        return $this->idade;
    }
    private function setIdade($idade)
    {
        $this->idade = $idade;
        return $this;
    }
    public function getQuant_vidas()
    {
        return $this->quant_vidas;
    }
    private function setQuant_vidas($quant_vidas)
    {
        $this->quant_vidas = $quant_vidas;
        return $this;
    }
    public function getDate()
    {
        return $this->date;
    }
    private function setDate($date)
    {
        $this->date = $date;
        return $this;
    }
    public function getError()
    {
        return $this->error;
    }
    private function setError($error)
    {
        $this->error = $error;
        return $this;
    }
    public function getPlano()
    {
        return $this->plano;
    }
    public function setPlano($plano)
    {
        $this->plano = $plano;
        return $this;
    }
    public function getPrice()
    {
        return $this->price;
    }
    private function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }
    public function getFaixa()
    {
        return $this->faixa;
    }
    private function setFaixa($faixa)
    {
        $idade = $this->getIdade();

        if ($idade >= 0 && $idade < 18) {
            $f = 'faixa1';
        } else if ($idade >= 18 && $idade < 41) {
            $f = 'faixa2';
        } else {
            $f = 'faixa3';
        }
        $this->faixa = $f;
        return $this;
    } 
    public function getPlano_id()
    {
        return $this->plano_id;
    }
    public function setPlano_id($plano_id)
    {
        $this->plano_id = $plano_id;
        return $this;
    } 
    public function getNome_proposta()
    {
        return $this->nome_proposta;
    }
    public function setNome_proposta($nome_proposta)
    {
        $this->nome_proposta = $nome_proposta;
        return $this;
    }
}
