<?php
/**
 * classe TImage
 * classe para exibição de imagens
 */
class TImage
{
    private $source;  // localização da imagem
    private $tag;     // objeto TElement

    /**
     * método construtor
     * instancia objeto TImage
     * @param $source = localização da imagem
     */
    public function __construct($source)
    {
        // atribui a localização da imagem
        $this->source = $source;
        // instancia objeto TElement
        $this->tag    = new TElement('img');
    }

    /**
     * método show()
     * exibe imagem na tela
     */
    public function show()
    {
        // define algumas propriedades da tag
        $this->tag->src = $this->source;
        $this->tag->border=0;
        // exibe tag na tela
        $this->tag->show();
    }
}
?>
