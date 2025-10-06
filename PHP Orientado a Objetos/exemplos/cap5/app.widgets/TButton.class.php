<?php
/**
 * classe TButton
 * responsável por exibir um botão
 */
class TButton extends TField
{
    private $action;
    private $label;
    private $formName;

    /**
     * método setAction
     * define a ação do botão (função a ser executada)
     * @param $action = ação do botão
     * @param $label    = rótulo do botão
     */
    public function setAction($action, $label)
    {
        $this->action = $action;
        $this->label = $label;
    }

    /**
     * método setFormName
     * define o nome do formulário para a ação botão
     * @param $name = nome do formulário
     */
    public function setFormName($name)
    {
        $this->formName = $name;
    }

    /**
     * método show()
     * exibe o botão
     */
    public function show()
    {
        $url = $this->action->serialize();
        // define as propriedades do botão
        $this->tag->name    = $this->name;    // nome da TAG
        $this->tag->type    = 'button';       // tipo de input
        $this->tag->value   = $this->label;   // rótulo do botão
        // se o campo não é editável
        if (!parent::getEditable())
        {
            $this->tag->disabled = "1";
            $this->tag->class = 'tfield_disabled'; // classe CSS
        }
        // define a ação do botão
        $this->tag->onclick =	"document.{$this->formName}.action='{$url}'; ".
			                                 "document.{$this->formName}.submit()";
        // exibe o botão
        $this->tag->show();
    }
}
?>
