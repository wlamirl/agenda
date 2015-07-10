<?php
namespace Contato\Model;
 
class Contato
{
    public $id;
    public $name;
    public $fone1;
    public $fone2;
    public $created;
    public $updated;
 
    public function exchangeArray($data)
    {
        $this->id      = (!empty($data['id'])) ? $data['id'] : null;
        $this->name    = (!empty($data['name'])) ? $data['name'] : null;
        $this->fone1   = (!empty($data['fone1'])) ? $data['fone1'] : null;
        $this->fone2   = (!empty($data['fone2'])) ? $data['fone2'] : null;
        $this->created = (!empty($data['created'])) ? $data['created'] : null;
        $this->updated = (!empty($data['updated'])) ? $data['updated'] : null;
    }
}

