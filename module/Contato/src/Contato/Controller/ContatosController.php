<?php

/**
 * namespace de localizacao do nosso controller
 */

namespace Contato\Controller;

// import ZendMvc
use Zend\Mvc\Controller\AbstractActionController;
// import ZendView
use Zend\View\Model\ViewModel;
// imort ModelContatoTable com alias
use Contato\Model\ContatoTable as ModelContato;
use Contato\Form\ContatoForm;
use Contato\Model\Contato;

class ContatosController extends AbstractActionController {

    protected $contatoTable;

    // GET /contatos
    public function indexAction() {
//        // localizar adapter do banco
        // colocar parametros da url em um array
        $paramsUrl = [
            'pagina_atual' => $this->params()->fromQuery('pagina', 1),
            'itens_pagina' => $this->params()->fromQuery('itens_pagina', 10),
            'coluna_nome' => $this->params()->fromQuery('coluna_nome', 'name'),
            'coluna_sort' => $this->params()->fromQuery('coluna_sort', 'ASC'),
            'search' => $this->params()->fromQuery('search', null),
        ];

        // configuar método de paginação
        $paginacao = $this->getContatoTable()->fetchPaginator(
                /* $pagina */ $paramsUrl['pagina_atual'],
                /* $itensPagina */ $paramsUrl['itens_pagina'],
                /* $ordem */ "{$paramsUrl['coluna_nome']} {$paramsUrl['coluna_sort']}",
                /* $search */ $paramsUrl['search'],
                /* $itensPaginacao */ 5
        );

        // retonar paginação mais os params de url para view
        return new ViewModel(['contatos' => $paginacao] + $paramsUrl);
    }

    // GET /contatos/novo
    public function novoAction() {
        return ['formContato' => new ContatoForm()];
    }

    // POST /contatos/adicionar
    public function adicionarAction() {
        // obtém a requisição
        $request = $this->getRequest();

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            // instancia formulário
            $form = new ContatoForm();
            // instancia model contato com regras de filtros e validações
            $modelContato = new Contato();
            // passa para o objeto formulário as regras de filtros e validações
            // contidas na entity contato
            $form->setInputFilter($modelContato->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());

            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelContato->exchangeArray($form->getData());
                // 2 - persistir dados do model para banco de dados
                $this->getContatoTable()->save($modelContato);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Contato criado com sucesso!");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('contatos');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formContato', $form)
                                ->setTemplate('contato/contatos/novo');
            }
        }
    }

    // GET /contatos/detalhes/id
    public function detalhesAction() {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);

        // se id = 0 ou não informado redirecione para contatos
        if (!$id) {
            // adicionar mensagem
            $this->flashMessenger()->addMessage("Contato não encotrado");

            // redirecionar para action index
            return $this->redirect()->toRoute('contatos');
        }

        // aqui vai a lógica para pegar os dados referente ao contato
        // 1 - solicitar serviço para pegar o model responsável pelo find
        // 2 - solicitar form com dados desse contato encontrado
        // formulário com dados preenchidos

        try {
//            $form = (array) $this->getContatoTable()->find($id);
            $contato = $this->getContatoTable()->find($id);
        } catch (Exception $exc) {
            // adicionar mensagem
            $this->flashMessenger()->addErrorMessage($exc->getMessage());

            // redirecionar para action index
            return $this->redirect()->toRoute('contatos');
        }
        // dados eviados para detalhes.phtml
//        return array('id' => $id, 'form' => $form);
        return ['contato' => $contato];
    }

    // GET /contatos/editar/id
    public function editarAction() {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);

        // se id = 0 ou não informado redirecione para contatos
        if (!$id) {
            // adicionar mensagem de erro
            $this->flashMessenger()->addMessage("Contato não encotrado");

            // redirecionar para action index
            return $this->redirect()->toRoute('contatos');
        }

        try {
//            $form = (array) $this->getContatoTable()->find($id);
            // variável com objeto contato localizado
            $contato = (array) $this->getContatoTable()->find($id);
        } catch (Exception $exc) {
            // adicionar mensagem
            $this->flashMessenger()->addErrorMessage($exc->getMessage());

            // redirecionar para action index
            return $this->redirect()->toRoute('contatos');
        }
        // objeto form contato vazio
        $form = new ContatoForm();

        // popula objeto form contato com objeto model contato
        $form->setData($contato);

        // dados eviados para editar.phtml
        return ['formContato' => $form];
    }

    // PUT /contatos/editar/id
    public function atualizarAction() {
        // obtém a requisição
        $request = $this->getRequest();

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            // instancia formulário
            $form = new ContatoForm();
            // instancia model contato com regras de filtros e validações
            $modelContato = new Contato();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
            $form->setInputFilter($modelContato->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());

            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                // aqui vai a lógica para atualizar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelContato->exchangeArray($form->getData());
                // 2 - atualizar dados do model para banco de dados
                $this->getContatoTable()->update($modelContato);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Contato editado com sucesso");

                // redirecionar para action detalhes
                return $this->redirect()->toRoute('contatos', array("action" => "detalhes", "id" => $modelContato->id));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formContato', $form)
                                ->setTemplate('contato/contatos/editar');
            }
        }
    }

    // DELETE /contatos/deletar/id
    public function deletarAction() {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);

        // se id = 0 ou não informado redirecione para contatos
        if (!$id) {
            // adicionar mensagem de erro
            $this->flashMessenger()->addMessage("Contato não encotrado");
        } else {
            // aqui vai a lógica para deletar o contato no banco
            // 1 - solicitar serviço para pegar o model responsável pelo delete
            // 2 - deleta contato
            // adicionar mensagem de sucesso
            $this->getContatoTable()->delete($id);

            $this->flashMessenger()->addSuccessMessage("Contato de ID $id deletado com sucesso");
        }

        // redirecionar para action index
        return $this->redirect()->toRoute('contatos');
    }

    /**
     * Metodo privado para obter instacia do Model ContatoTable
     *
     * @return ContatoTable
     */
    private function getContatoTable() {
        // localizar adapter do banco
//        $tableGateway = $this->getServiceLocator()->get('ContatoTableGateway');
//
//        // return model ContatoTable
//        return new ModelContato($tableGateway); // alias para ContatoTable
//        return $this->getServiceLocator()->get('ModelContato');
        if (!$this->contatoTable) {
            $this->contatoTable = $this->getServiceLocator()->get('ModelContato');
        }

        // return vairavel de classe com service ModelContato
        return $this->contatoTable;
    }

    /**
     * Atualizar um contato existente
     * 
     * @param Contato\Model\Contato $contato
     * @throws Exception
     */
    public function update(Contato $contato) {
        $timeNow = new \DateTime();

        $data = [
            'name' => $contato->name,
            'fone1' => $contato->fone1,
            'fone2' => $contato->fone2,
            'created' => $timeNow->format('Y-m-d H:i:s'),
        ];

        $id = (int) $contato->id;
        if ($this->find($id)) {
            $this->tableGateway->update($data, array('id' => $id));
        } else {
            throw new Exception("Contato #{$id} inexistente");
        }
    }

}
