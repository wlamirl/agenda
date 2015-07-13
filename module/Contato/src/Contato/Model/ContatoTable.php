<?php

// namespace de localizacao do nosso model

namespace Contato\Model;

// import ZendDb
// use Zend\Db\Adapter\Adapter,
//Zend\Db\ResultSet\ResultSet,   
use Zend\Db\TableGateway\TableGateway;

class ContatoTable {

    protected $tableGateway;

    /**
     * Contrutor com dependencia do Adapter do Banco
     *
     * @param Adapter $adapter
     */
//    public function __construct(Adapter $adapter)
//    {
//        $resultSetPrototype = new ResultSet();
//        $resultSetPrototype->setArrayObjectPrototype(new Contato());
// 
//        $this->tableGateway = new TableGateway('contatos', $adapter, null, $resultSetPrototype);
//    }
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Recuperar todos os elementos da tabela contatos
     *
     * @return ResultSet
     */
    public function fetchAll() {
        return $this->tableGateway->select();
    }

    /**
     * Localizar linha especifico pelo id da tabela contatos
     *
     * @param type $id
     * @return ModelContato
     * @throws Exception
     */
    public function find($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new Exception("Não foi encontrado contado de id = {$id}");
        }

        return $row;
    }

    /**
     * Inserir um novo contato
     * 
     * @param Contato\Model\Contato $contato
     * @return 1/0
     */
    public function save(Contato $contato) {
        $timeNow = new \DateTime();

        $data = [
            'name' => $contato->name,
            'fone1' => $contato->fone1,
            'fone2' => $contato->fone2,
            'created' => $timeNow->format('Y-m-d H:i:s'),
            'updated' => $timeNow->format('Y-m-d H:i:s'), # data de criação igual a de atualização 
        ];

        return $this->tableGateway->insert($data);
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
            'updated' => $timeNow->format('Y-m-d H:i:s'),
        ];

        $id = (int) $contato->id;
        if ($this->find($id)) {
            $this->tableGateway->update($data, array('id' => $id));
        } else {
            throw new Exception("Contato #{$id} inexistente");
        }
    }

    /**
     * Deletar um contato existente
     * 
     * @param type $id
     */
    public function delete($id) {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

}
