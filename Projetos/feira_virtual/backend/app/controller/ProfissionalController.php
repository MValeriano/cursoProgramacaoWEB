<?php

require_once "app/model/Profissional.php";

class ProfissionalController{

    protected $profissional;

    function __construct(){
        $this->profissional = new Profissional();
    }

    public function verificaProfissional(){
        $id = 123;

        // $resultado->bindValue(":id",1);

        //$resultado->execute();

        echo '<pre>';
        // var_dump($resultado->fetch());
        // print_r($profissional->show($id));
        print_r($this->profissional->readAll());
        echo '</pre>';
    }
}