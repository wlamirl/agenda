<?php

namespace Contato\Model;

// namespace de localização de nossa entity

use Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterInterface;

class Contato implements InputFilterAwareInterface {

    public $id;
    public $name;
    public $fone1;
    public $fone2;
    public $created;
    public $updated;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->fone1 = (!empty($data['fone1'])) ? $data['fone1'] : null;
        $this->fone2 = (!empty($data['fone2'])) ? $data['fone2'] : null;
        $this->created = (!empty($data['created'])) ? $data['created'] : null;
        $this->updated = (!empty($data['updated'])) ? $data['updated'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new Exception('Não utilizado.');
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            // input filter para campo de id
            $inputFilter->add(array(
                'name' => 'id',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'), #transforma string para int
                ),
            ));
 
            // input filter para campo de nome
            $inputFilter->add(array(
                'name' => 'name',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'), # Remove xml e html da string
                    array('name' => 'StringTrim'), # Remove espaços do início e do fim da string
                    array('name' => 'StringToUpper'), # Transforma string em caixa alta                    
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Campo Obrigatório'
                             ),
                        ),
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'enconding' => 'UTF-8',
                            'min' => 3,
                            'max' => 100,
                            'messages' => array(
                                \Zend\Validator\StringLength::TOO_SHORT => 'Mínimo de caracteres aceitáveis %min%.',
                                \Zend\Validator\StringLength::TOO_LONG => 'Máximo de caracteres aceitáveis %max%.',
                             ),                            
                        ),
                    ),                    
                ),
            ));

            // input filter para campo de nome
            $inputFilter->add(array(
                'name' => 'fone1',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'), # Remove xml e html da string
                    array('name' => 'StringTrim'), # Remove espaços do início e do fim da string
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Campo Obrigatório'
                             ),
                        ),
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'enconding' => 'UTF-8',
                            'min' => 8, #xxxxxxxx
                            'max' => 16, #(xxx)xxx-xxx-xxx
                            'messages' => array(
                                \Zend\Validator\StringLength::TOO_SHORT => 'Mínimo de caracteres aceitáveis %min%.',
                                \Zend\Validator\StringLength::TOO_LONG => 'Máximo de caracteres aceitáveis %max%.',
                             ),                            
                        ),
                    ),                    
                ),
            ));
            
            // input filter para campo de nome
            $inputFilter->add(array(
                'name' => 'fone2',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'), # Remove xml e html da string
                    array('name' => 'StringTrim'), # Remove espaços do início e do fim da string
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Campo Obrigatório'
                             ),
                        ),
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'enconding' => 'UTF-8',
                            'min' => 8, #xxxxxxxx
                            'max' => 16, #(xxx)xxx-xxx-xxx
                            'messages' => array(
                                \Zend\Validator\StringLength::TOO_SHORT => 'Mínimo de caracteres aceitáveis %min%.',
                                \Zend\Validator\StringLength::TOO_LONG => 'Máximo de caracteres aceitáveis %max%.',
                             ),                            
                        ),
                    ),                    
                ),
            ));
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }

}
