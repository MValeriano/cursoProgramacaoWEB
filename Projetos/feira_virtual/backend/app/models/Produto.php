<?php

require_once "Model.php";

class Produto extends Model {
    protected $table = "produtos";
    protected $primaryKey = "id";
}
