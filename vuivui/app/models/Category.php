<?php

class Category extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id", type="integer", length=10, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(column="name", type="string", length=255, nullable=false)
     */
    public $name;

    /**
     *
     * @var string
     * @Column(column="slug", type="string", length=255, nullable=false)
     */
    public $slug;

    /**
     *
     * @var string
     * @Column(column="description", type="string", nullable=true)
     */
    public $description;

    /**
     *
     * @var integer
     * @Column(column="status", type="integer", length=4, nullable=false)
     */
    public $status;

    /**
     *
     * @var integer
     * @Column(column="parent_id", type="integer", length=10, nullable=true)
     */
    public $parent_id;

    /**
     *
     * @var string
     * @Column(column="parent_url", type="string", length=255, nullable=true)
     */
    public $parent_url;

    /**
     *
     * @var integer
     * @Column(column="level", type="integer", length=4, nullable=true)
     */
    public $level;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("vuivui");
        $this->setSource("category");
        $this->hasMany('id', 'Product', 'category_id', ['alias' => 'Product']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'category';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Category[]|Category|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Category|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
