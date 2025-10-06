<?php
function __autoload($classe)
{
    if (file_exists("app.widgets/{$classe}.class.php"))
    {
        include_once "app.widgets/{$classe}.class.php";
    }
}

/*
 * classe Pagina
 * demonstra o funcionamento de um diálogo de questão
 */
class Pagina extends TPage
{
    private $panel;

    /**
     * método construtor
     * instancia uma nova página
     */
    function __construct()
    {
        parent::__construct();
        // cria um novo painel
        $this->panel = new TPanel(400,200);

        // coloca um novo texto na coluna:10 linha:10
        $this->panel->put(new TParagraph('Responda a questão'), 10, 10);

        // cria duas ações
        $action1 = new TAction(array($this, 'onYes'));
        $action2 = new TAction(array($this, 'onNo'));

        // exibe a pergunta ao usuário
        new TQuestion('Deseja realmente excluir o registro?', $action1, $action2);

        // adiciona o painel à janela
        parent::add($this->panel);
    }

    /**
     * método onYes
     * executado caso o usuário responda SIM para pergunta
     */
    function onYes()
    {
        $this->panel->put(new TParagraph('Você escolheu a opção sim'), 10, 40);
    }

    /**
     * método onYes
     * executado caso o usuário responda NÃO para pergunta
     */
    function onNo()
    {
        $this->panel->put(new TParagraph('Você escolheu a opção não'), 10, 40);
    }
}
// instancia a página
$pagina = new Pagina;

// exibe a página
$pagina->show();
?>
