<?php

class Product extends \Phalcon\Mvc\Model
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
     * @Column(column="slug", type="string", length=512, nullable=false)
     */
    public $slug;

    /**
     *
     * @var integer
     * @Column(column="price", type="integer", length=10, nullable=true)
     */
    public $price;

    /**
     *
     * @var integer
     * @Column(column="price_sale", type="integer", length=10, nullable=true)
     */
    public $price_sale;

    /**
     *
     * @var string
     * @Column(column="description", type="string", nullable=true)
     */
    public $description;

    /**
     *
     * @var string
     * @Column(column="image", type="string", length=255, nullable=true)
     */
    public $image;

    /**
     *
     * @var string
     * @Column(column="image_list", type="string", nullable=true)
     */
    public $image_list;

    /**
     *
     * @var integer
     * @Column(column="stock", type="integer", length=4, nullable=true)
     */
    public $stock;

    /**
     *
     * @var integer
     * @Column(column="brand_id", type="integer", length=10, nullable=true)
     */
    public $brand_id;

    /**
     *
     * @var integer
     * @Column(column="category_id", type="integer", length=10, nullable=true)
     */
    public $category_id;

    /**
     *
     * @var integer
     * @Column(column="status", type="integer", length=4, nullable=false)
     */
    public $status;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("vuivui");
        $this->setSource("product");
        $this->belongsTo('brand_id', '\Brand', 'id', ['alias' => 'Brand']);
        $this->belongsTo('category_id', '\Category', 'id', ['alias' => 'Category']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'product';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Product[]|Product|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Product|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
