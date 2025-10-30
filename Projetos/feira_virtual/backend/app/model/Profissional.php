<?php

require 'Model.php';

class Profissional extends Model{

    protected $table = 'transporte_em_saude.profissional';
    protected $primary_key = 'id_profissional';
    // protected $fillable = ['"nomeProfissional"', '"dataNascimento"', 'cpf', ];

}