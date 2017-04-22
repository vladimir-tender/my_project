<?php

namespace MyShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderDelivery
 *
 * @ORM\Table(name="order_delivery")
 * @ORM\Entity(repositoryClass="MyShopBundle\Repository\OrderDeliveryRepository")
 */
class OrderDelivery
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
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="deliveryby", type="string", length=255)
     */
    private $deliveryby;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255)
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="paymenttype", type="string", length=255)
     */
    private $paymenttype;

    /**
     * @var int
     * @ORM\OneToOne(targetEntity="MyShopBundle\Entity\CustomerOrder", inversedBy="delivery")
     * @ORM\JoinColumn(name="order", referencedColumnName="id")
     */
    private $order;


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
     * Set address
     *
     * @param string $address
     *
     * @return OrderDelivery
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set deliveryby
     *
     * @param string $deliveryby
     *
     * @return OrderDelivery
     */
    public function setDeliveryby($deliveryby)
    {
        $this->deliveryby = $deliveryby;

        return $this;
    }

    /**
     * Get deliveryby
     *
     * @return string
     */
    public function getDeliveryby()
    {
        return $this->deliveryby;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return OrderDelivery
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set paymenttype
     *
     * @param string $paymenttype
     *
     * @return OrderDelivery
     */
    public function setPaymenttype($paymenttype)
    {
        $this->paymenttype = $paymenttype;

        return $this;
    }

    /**
     * Get paymenttype
     *
     * @return string
     */
    public function getPaymenttype()
    {
        return $this->paymenttype;
    }
}

