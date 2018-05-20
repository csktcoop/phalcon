<?php

namespace App\Model;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Base extends \Phalcon\Mvc\Model
{
    
    // use PostEditing;

    const DISABLE = "= 1";
    const ENABLE  = 'IS NULL';

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id", type="integer", length=20, nullable=false)
     */
    public $id;

    /**
     *
     * @var integer
     * @Column(column="ordering", type="integer", length=3, nullable=false)
     */
    public $ordering;

    /**
     *
     * @var integer
     * @Column(column="author", type="integer", length=20, nullable=false)
     */
    public $author;

    /**
     *
     * @var integer
     * @Column(column="last_editor", type="integer", length=20, nullable=true)
     */
    public $last_editor;

    /**
     *
     * @var string
     * @Column(column="created", type="string", nullable=false)
     */
    public $created;

    /**
     *
     * @var string
     * @Column(column="modified", type="string", nullable=true)
     */
    public $modified;

    /**
     *
     * @var integer
     * @Column(column="disable", type="integer", length=1, nullable=true)
     */
    public $disable;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        // Append prefix to table name
        $this->setSource(
        	$this->getDI()->getConfig()->database->prefix . strtolower($this->getSource())
        );
        
        $this->addBehavior(
            new SoftDelete(
                [
                    "field" => "disable",
                    "value" => Base::DISABLE,
                ]
            )
        );
        
        // Skips only when updating
        $this->skipAttributesOnUpdate(
            [
                "author",
                "created"
            ]
        );
    }
    
    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return User[]|User|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        $condition = isset($parameters['conditions']) ? $parameters['conditions'] : '1';
    	$parameters['conditions'] = $condition . ' AND disable ' . self::ENABLE;
		
        parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return User|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        if ($parameters) {
            $key = key($parameters);
            $parameters[$key] .= " AND disable " . self::ENABLE;
        }

        return parent::findFirst($parameters);
    }

}


trait PostEditing
{

    public function beforeValidationOnUpdate()
    {
        $this->editor    = 1;
        $this->modified  = date(DATETIME);
    }
    
}