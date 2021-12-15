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
            "api_consulta_planos" => "consultarPlanos",
            "api_consulta_precos" => "consultarPrecos",
            'api_cadastro_proposta' => 'cadastrarProposta',
            'api_consulta_propostasCriadas' => 'consultarProposta',
            'api_create_beneficiario' => 'criarBeneficiario',
            'api_lista_por_proposta' => 'consultaListaProposta',
            'api_create_proposta' => 'criarProposta',

        ];

        if (array_key_exists($i, $this->route)) {
            return $this->route[$i];
        } else {
            return "error404";
        }
    }
}
