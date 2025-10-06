<?php
# carrega as classes
include_once 'classes/Conta.class.php';
include_once 'classes/ContaCorrente.class.php';

class ContaCorrenteEspecial extends ContaCorrente
{
    function Depositar($Valor)
    {
        echo "sobrescrevendo método Depositar.<br>\n";
        parent::Depositar($Valor);
    }

    function Transferir($Conta, $Valor)
    {
        echo "sobrescrevendo método Transferir.<br>\n";
        parent::Transferir($Conta, $Valor);
    }
}
?>
