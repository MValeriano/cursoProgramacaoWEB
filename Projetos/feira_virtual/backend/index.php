<?php

require_once 'app/controller/ProfissionalController.php';

$controller = new ProfissionalController();


echo '<pre>';
// print_r($_SERVER);
print_r($controller->verificaProfissional());
echo '</pre>';