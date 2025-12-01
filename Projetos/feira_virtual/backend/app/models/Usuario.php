<?php

require_once "Model.php";

class Usuario extends Model {
    protected $table = "usuarios";
    protected $primaryKey = "id";
}