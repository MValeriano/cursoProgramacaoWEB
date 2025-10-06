<?php
// exemplo chamada simples
function minhafuncao()
{
	   echo "minha função! <br>\n";
}
call_user_func('minhafuncao');
// declaração de classe
class MinhaClasse
{
	   function MeuMetodo()
	   {
		      echo "Meu método! <br>\n";
	   }
}
// chamada de método estático
call_user_func(array('MinhaClasse', 'MeuMetodo'));
// chamada de método
$obj = new MinhaClasse();
call_user_func(array($obj, 'MeuMetodo'));
?>

