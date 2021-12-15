<?php

namespace App\Src\Classes;

class ClassRender
{

    private $dir;

    public function renderLayout()
    {
        // echo 'aqui';
        // exit;
        if (file_exists("App/View/Layout.php")) {
            include("App/View/Layout.php");
        } else {
            echo "Estamos em manutenção";
        }
    }

    protected function addMain()
    {
        if (file_exists("App/View/paginas/corpo{$this->getDir()}.php")) {
            return include_once("App/View/paginas/corpo{$this->getDir()}.php");
        } else {
            echo "Sem corpo";
        }
    }

    protected function scriptCSS()
    {
        return "<link rel='stylesheet' href='" . DIRPAGE . "App/View/materialize/css/contato.css'>" .
        "<link rel='stylesheet' href='" . DIRPAGE . "App/View/materialize/css/materialize.min.css'>";
    }

    protected function scriptJS()
    {
        if (file_exists("App/View/materialize/js/{$this->getDir()}.html")) {
            include_once("App/View/materialize/js/{$this->getDir()}.html");
            unset($_SESSION['msg']);
            exit;
        } else {
            include_once("App/View/materialize/js/geral.html");
            unset($_SESSION['msg']);
            exit;
        }
    }

    public function getDir()
    {
        return $this->dir;
    }

    public function setDir($dir)
    {
        $this->dir = $dir;
        return $this;
    }
}
