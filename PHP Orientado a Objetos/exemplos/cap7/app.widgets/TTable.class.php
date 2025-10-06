<?php
/**
 * classe TTable
 * responsável pela exibição de tabelas
 */
class TTable extends TElement
{
    /**
     * método construtor
     * instancia uma nova tabela
     */
    public function __construct()
    {
        parent::__construct('table');
    }

    /**
     * método addRow
     * agrega um novo objeto linha (TTableRow) na tabela
     */
    public function addRow()
    {
        // instancia objeto linha
        $row = new TTableRow;
        // armazena no array de linhas
        parent::add($row);
        return $row;
    }
}
?>
