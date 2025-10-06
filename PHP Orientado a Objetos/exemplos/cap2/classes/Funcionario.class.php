<?php
class Funcionario
{
    private   $Codigo;
    public    $Nome;
    private   $Nascimento;
    protected $Salario;

    /* método SetSalario
     * atribui o parâmetro $Salario à propriedade $Salario
     */
    function SetSalario($Salario)
    {
        // verifica se é numérico e positivo
        if (is_numeric($Salario) and ($Salario > 0))
        {
	           $this->Salario = $Salario;
        }
    }

    /* método GetSalario
     * retorna o valor da propriedade $Salario
     */
    function GetSalario()
    {
        return $this->Salario;
    }
}
?>
