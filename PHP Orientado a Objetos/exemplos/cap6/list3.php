<?php
/*
 * função __autoload()
 * carrega uma classe quando ela é necessária,
 * ou seja, quando ela é instancia pela primeira vez.
 */
function __autoload($classe)
{
    $pastas = array('app.widgets', 'app.ado');
    foreach ($pastas as $pasta)
    {
        if (file_exists("{$pasta}/{$classe}.class.php"))
        {
            include_once "{$pasta}/{$classe}.class.php";
        }
    }
}
/*
 * função conv_data_to_br()
 * converte uma data para o formato dd/mm/yyyy
 * @param $data = data no formato yyyy/mm/dd
 */
function conv_data_to_br($data)
{
    // captura as partes da data
    $ano = substr($data,0,4);
    $mes = substr($data,5,2);
    $dia = substr($data,8,4);
    // retorna a data resultante
    return "{$dia}/{$mes}/{$ano}";
}

/*
 * função get_sexo()
 * converte um caractere (M,F) para extenso
 * @param $sexo = M ou F (Masculino/Feminino)
 */
function get_sexo($sexo)
{
    switch ($sexo)
    {
        case 'M':
            return 'Masculino';
            break;
        case 'F':
            return 'Feminino';
            break;
    }
}

// declara a classe Pessoa
class Pessoa extends TRecord
{
    const TABLENAME = 'pessoa';
}

// instancia objeto DataGrid
$datagrid = new TDataGrid;

// instancia as colunas da DataGrid
$codigo    = new TDataGridColumn('id',      'Código',   'right',   50);
$nome      = new TDataGridColumn('nome',    'Nome',     'left',   160);
$endereco  = new TDataGridColumn('endereco', 'Endereço', 'left',   140);
$datanasc  = new TDataGridColumn('datanasc', 'Data Nasc','left',   100);
$sexo      = new TDataGridColumn('sexo',    'Sexo',     'center', 100);

// aplica as funções para transformar as colunas
$nome->setTransformer('strtoupper');
$datanasc->setTransformer('conv_data_to_br');
$sexo->setTransformer('get_sexo');

// adiciona as colunas à DataGrid
$datagrid->addColumn($codigo);
$datagrid->addColumn($nome);
$datagrid->addColumn($endereco);
$datagrid->addColumn($datanasc);
$datagrid->addColumn($sexo);

// cria o modelo da DataGrid, montando sua estrutura
$datagrid->createModel();

// obtém objetos do banco de dados
try
{
    // inicia transação com o banco 'pg_livro'
    TTransaction::open('pg_livro');
    // instancia um repositório para Pessoa
    $repository = new TRepository('Pessoa');
    // cria um critério, definindo a ordenação
    $criteria = new TCriteria;
    $criteria->setProperty('order', 'id');
    // carrega os objetos $pessoas
    $pessoas = $repository->load($criteria);
    foreach ($pessoas as $pessoa)
    {
        // adiciona o objeto na DataGrid
        $datagrid->addItem($pessoa);
    }
    // finaliza a transação
    TTransaction::close();
}
catch (Exception $e)   // em caso de exceção
{
    // exibe a mensagem gerada pela exceção
    new TMessage('error', $e->getMessage());
    // desfaz todas alterações no banco de dados
    TTransaction::rollback();
}
// instancia uma página TPage
$page = new TPage;
// adiciona a DataGrid à página
$page->add($datagrid);
// exibe a página
$page->show();
?>
