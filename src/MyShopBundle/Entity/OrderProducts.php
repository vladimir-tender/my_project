<?php

namespace MyShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderProducts
 *
 * @ORM\Table(name="order_products")
 * @ORM\Entity(repositoryClass="MyShopBundle\Repository\OrderProductsRepository")
 */
class OrderProducts
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=255)
     */
    private $model;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @var int
     *
     * @ORM\Column(name="catalogid", type="integer")
     */
    private $catalogid;

    /**
     * @var CustomerOrder
     *
     * @ORM\ManyToOne(targetEntity="MyShopBundle\Entity\CustomerOrder", inversedBy="products")
     * @ORM\JoinColumn(name="customerOrder", referencedColumnName="id")
     */
    private $customerOrder;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set model
     *
     * @param string $model
     *
     * @return OrderProducts
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return OrderProducts
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set count
     *
     * @param integer $count
     *
     * @return OrderProducts
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set catalogid
     *
     * @param integer $catalogid
     *
     * @return OrderProducts
     */
    public function setCatalogid($catalogid)
    {
        $this->catalogid = $catalogid;

        return $this;
    }

    /**
     * Get catalogid
     *
     * @return int
     */
    public function getCatalogid()
    {
        return $this->catalogid;
    }

    /**
     * @return CustomerOrder
     */
    public function getCustomerOrder()
    {
        return $this->customerOrder;
    }

    /**
     * @param CustomerOrder $customerOrder
     */
    public function setCustomerOrder($customerOrder)
    {
        $this->customerOrder = $customerOrder;
    }


}

