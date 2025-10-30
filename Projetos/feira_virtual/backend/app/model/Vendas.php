<?php

require 'Model.php';

class Vendas extends Model{

    protected $table = 'vendas';
    protected $primary_key = 'venda_id';
    protected $fillable = ['venda_id','cliente_id','data_venda','total','status'];
}