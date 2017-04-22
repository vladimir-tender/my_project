<?php

namespace MyShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CustomerOrder
 *
 * @ORM\Table(name="customer_order")
 * @ORM\Entity(repositoryClass="MyShopBundle\Repository\CustomerOrderRepository")
 */
class CustomerOrder
{
    const STATUS_REJECT = 0;
    const STATUS_CURRENT = 1;
    const STATUS_DONE = 2;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datecreate", type="datetime")
     */
    private $datecreate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datedone", type="datetime", nullable=true)
     */
    private $datedone;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="MyShopBundle\Entity\OrderProducts", mappedBy="customerOrder")
     */
    private $products;

    /**
     * @ORM\OneToOne(targetEntity="MyShopBundle\Entity\OrderDelivery", mappedBy="order")
     * @ORM\JoinColumn(name="delivery", referencedColumnName="id")
     */
    private $delivery;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="MyShopBundle\Entity\Customer", inversedBy="orders")
     * @ORM\JoinColumn(name="id_customer", referencedColumnName="id")
     */
    private $customer;


    public function __construct()
    {
        $this->setDatecreate(new \DateTime("now"));
        $this->setStatus($this::STATUS_CURRENT);
        $this->products = new ArrayCollection();
    }

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
     * Set status
     *
     * @param integer $status
     *
     * @return CustomerOrder
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set datecreate
     *
     * @param \DateTime $datecreate
     *
     * @return CustomerOrder
     */
    public function setDatecreate($datecreate)
    {
        $this->datecreate = $datecreate;

        return $this;
    }

    /**
     * Get datecreate
     *
     * @return \DateTime
     */
    public function getDatecreate()
    {
        return $this->datecreate;
    }

    /**
     * Set datedone
     *
     * @param \DateTime $datedone
     *
     * @return CustomerOrder
     */
    public function setDatedone($datedone)
    {
        $this->datedone = $datedone;

        return $this;
    }

    /**
     * Get datedone
     *
     * @return \DateTime
     */
    public function getDatedone()
    {
        return $this->datedone;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return CustomerOrder
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param mixed $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

    public function getTotalSum()
    {
        $products = $this->getProducts();
        $sum = 0;
        if($products != null)
        {
            /**
             * @var OrderProducts $product
             */
            foreach ($products as $product)
            {
                $sum += $product->getPrice() * $product->getCount();
            }
        }

        return $sum;

    }
}

