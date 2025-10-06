<?php
/*
 * função __autoload()
 * carrega uma classe quando ela é necessária,
 * ou seja, quando ela é instancia pela primeira vez.
 */
function __autoload($classe)
{
    if (file_exists("app.ado/{$classe}.class.php"))
    {
        include_once "app.ado/{$classe}.class.php";
    }
}
try
{
    // abre uma transação
    TTransaction::open('pg_livro');

    // cria uma instrução de INSERT
    $sql = new TSqlInsert;

    // define o nome da entidade
    $sql->setEntity('famosos');

    // atribui o valor de cada coluna
    $sql->setRowData('codigo', 8);
    $sql->setRowData('nome', 'Galileu');

    // obtém a conexão ativa
    $conn = TTransaction::get();

    // executa a instrução SQL
    $result = $conn->Query($sql->getInstruction());

    // cria uma instrução de UPDATE
    $sql = new TSqlUpdate;

    // define o nome da entidade
    $sql->setEntity('famosos');

    // atribui o valor de cada coluna
    $sql->setRowData('nome', 'Galileu Galilei');

    // cria critério de seleção de dados
    $criteria = new TCriteria;

    // obtém a pessoa de código "8"
    $criteria->add(new TFilter('codigo', '=', '8'));

    // atribui o critério de seleção de dados
    $sql->setCriteria($criteria);

    // obtém a conexão ativa
    $conn = TTransaction::get();

    // executa a instrução SQL
    $result = $conn->Query($sql->getInstruction());

    // fecha a transação, aplicando todas operações
    TTransaction::close();
}
catch (Exception $e)
{
    // exibe a mensagem de erro
    echo $e->getMessage();
    // desfaz operações realizadas durante a transação
    TTransaction::rollback();
}
?>
