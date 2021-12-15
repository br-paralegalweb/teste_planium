<?php

namespace App\Src\Traits;

trait TraitsParser
{

    public function parserUrl()
    {
        return explode("/", $_SERVER['REQUEST_URI']);
    }

    protected function getRoute()
    {
        $url = $this->parserUrl();
        $i = $url[3];

        $this->route = [
            "" => "render",
            "home" => "render",
            'simulador' => 'validarDadosForm',
            'planosPlanium' => 'planos',
            'proposta' => 'proposta',
            'recebeProposta' => 'recebeProposta',
            'suasPropostas' => 'propostasCriadas',
            'listarPropostas' => 'listaPropostasCriadas',
            'consultarPropostas' => 'getPropostasNome',
            'criarProposta' => 'createProposta',
        ];

        if (array_key_exists($i, $this->route)) {
            return $this->route[$i];
        } else {
            return "error404";
        }
    }
}
