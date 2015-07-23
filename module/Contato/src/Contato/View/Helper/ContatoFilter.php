<?php
 
namespace Contato\View\Helper;
 
use Zend\View\Helper\AbstractHelper;
use Contato\Model\Contato;
 
class ContatoFilter extends AbstractHelper
{
 
    protected $contato;
 
    public function __invoke(Contato $contato)
    {
        $this->contato = $contato;
 
        return $this;
    }
 
    public function id()
    {
        $result = $this->contato->id;
 
        return $this->view->escapeHtml($result);
    }
 
    public function nomeSobrenome()
    {
        $partes_nome = explode(" ", $this->nomeCompleto());
        $result = null;
 
        if (count($partes_nome) <= 2) {
            $result = join($partes_nome, " ");
        } else {
            $result = "{$partes_nome[0]} {$partes_nome[1]} ...";
        }
 
        return $this->view->escapeHtml($result);
    }
 
    public function nomeCompleto()
    {
        $result = ucwords(strtolower($this->contato->name));
 
        return $this->view->escapeHtml($result);
    }
 
    public function quantidadeTelefones()
    {
        $result = ((int) !empty($this->contato->fone1)) + ((int) !empty($this->contato->fone2));
 
        return $this->view->escapeHtml($result);
    }
 
    public function dataCriacao()
    {
        $result = (new \DateTime($this->contato->created))->format('d/m/Y - H:i');
 
        return $this->view->escapeHtml($result);
    }
 
    public function dataAtualizacao()
    {
        $result = (new \DateTime($this->contato->updated))->format('d/m/Y - H:i');
 
        return $this->view->escapeHtml($result);
    }
 
    public function telefonePrincipal()
    {
        $result = $this->contato->fone1 ? $this->contato->fone1 : 'Sem Registro';
 
        return $this->view->escapeHtml($result);
    }
 
    public function telefoneSecundario()
    {
        $result = $this->contato->fone2 ? $this->contato->fone2 : 'Sem Registro';
 
        return $this->view->escapeHtml($result);
    }
 
}
