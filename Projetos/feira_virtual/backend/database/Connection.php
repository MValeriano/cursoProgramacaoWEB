<?php

class Connection{

    public static function getConnection(){
        return new PDO('pgsql:host=10.0.26.110;port=13100;dbname=transporte_em_saude','usr_transporte_em_saude', 'abkt432vrd');
    }
}