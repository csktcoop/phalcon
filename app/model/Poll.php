<?php

namespace App\Model;

class Poll extends Base
{
	
    /**
     *
     * @var string
     * @Column(column="content", type="string", nullable=false)
     */
    public $content;
    
//    public function validation()
//    {
//        // Type must be: droid, mechanical or virtual
//        $this->validate(
//            new InclusionIn(
//                [
//                    'field'  => 'type',
//                    'domain' => [
//                        'droid',
//                        'mechanical',
//                        'virtual',
//                    ],
//                ]
//            )
//        );
//
//        // Robot name must be unique
//        $this->validate(
//            new Uniqueness(
//                [
//                    'field'   => 'name',
//                    'message' => 'The robot name must be unique',
//                ]
//            )
//        );
//
//        // Year cannot be less than zero
//        if ($this->year < 0) {
//            $this->appendMessage(
//                new Message('The year cannot be less than zero')
//            );
//        }
//
//        // Check if any messages have been produced
//        if ($this->validationHasFailed() === true) {
//            return false;
//        }
//    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Poll[]|Poll|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null) {
		
        // Suffix match
    	if (isset($parameters['conditions'])) {
			$parameters['conditions']      = str_replace("[content] =", "[content] LIKE", $parameters['conditions']);
			$parameters['bind']['content'] = $parameters['bind']['content'] . "%";
		}
        
		$parameters = array_merge(["order" => "created DESC"], (array)$parameters);
        
        return parent::find($parameters);
    }

	public function beforeSave() {
		if ($this->id) {
			$this->editor   = 1;
			$this->modified = date(DATETIME);
		}
	}
	
}