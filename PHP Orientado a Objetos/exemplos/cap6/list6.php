<?php
/*
 * função __autoload()
 *  Carrega uma classe quando ela é necessária,
 *  ou seja, quando ela é instancia pela primeira vez.
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

// declara a classe Pessoa
class Pessoa extends TRecord
{
    const TABLENAME = 'pessoa';
}

/*
 * classe PessoasList
 * ****
 */
class PessoasList extends TPage
{
    private $datagrid;
    private $navbar;    
    private $loaded;

    public function __construct()
    {
        parent::__construct();
        
        // instancia objeto DataGrid
        $this->datagrid = new TDataGrid;
        
        // instancia as colunas da DataGrid
        $codigo   = new TDataGridColumn('id',       'Código',       'left',  50);
        $nome     = new TDataGridColumn('nome',     'Nome',         'left', 180);
        $endereco = new TDataGridColumn('endereco', 'Endereço',     'left', 140);
        $qualifica= new TDataGridColumn('qualifica','Qualificações','left', 200);
        
        // adiciona as colunas à DataGrid
        $this->datagrid->addColumn($codigo);
        $this->datagrid->addColumn($nome);
        $this->datagrid->addColumn($endereco);
        $this->datagrid->addColumn($qualifica);
        
        // cria o modelo da DataGrid, montando sua estrutura
        $this->datagrid->createModel();
        
        $this->navbar = new TPageNavigation;
        $this->navbar->setAction(new TAction(array($this, 'onReload')));
        
        $table = new TTable;
        $row = $table->addRow();
        $row->addCell($this->datagrid);
        
        $row = $table->addRow();
        $row->addCell($this->navbar);
        // adiciona a DataGrid à página
        parent::add($table);
    }

    /*
     * função onReload()
     * Carrega a DataGrid com os objetos do banco de dados
     */
    function onReload($param = NULL)
    {
        $offset    = $param['offset'];
        //$order     = $param['order'];
        $limit     = 10;
        // inicia transação com o banco 'pg_livro'
        TTransaction::open('pg_livro');
        
        // instancia um repositório para Alunos
        $repository = new TRepository('Pessoa');
        // retorna todos objetos que satisfazem o critério
        $criteria = new TCriteria;
        $count   = $repository->count($criteria);
        $criteria->setProperty('limit', $limit);
        $criteria->setProperty('offset', $offset);
        $pessoas = $repository->load($criteria);

        $this->navbar->setPageSize($limit);
        $this->navbar->setCurrentPage($param['page']);
        $this->navbar->setTotalRecords($count);

        $this->datagrid->clear();
        if ($pessoas)
        {
            foreach ($pessoas as $pessoa)
            {
                // adiciona o objeto na DataGrid
                $this->datagrid->addItem($pessoa);
            }
        }
        // finaliza a transação
        TTransaction::close();
        $this->loaded = true;
    }

    /*
     * função show()
     * Executada quando o usuário clicar no botão excluir
     */
    function show()
    {
        if (!$this->loaded)
        {
            $this->onReload();
        }
        parent::show();
    }
}

$page = new PessoasList;
$page->show();
?>
