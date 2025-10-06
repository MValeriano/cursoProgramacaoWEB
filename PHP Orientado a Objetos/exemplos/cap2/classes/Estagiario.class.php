<?php
class Estagiario extends Funcionario
{
	   /* método GetSalario sobreescrito
	    * retorna o $Salário com 12% de bônus.
	    */
	   function GetSalario()
	   {
		      return $this->Salario * 1.12;
	   }
}
?>
