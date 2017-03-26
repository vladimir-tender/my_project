<?php

namespace AdminBundle\Event;
use MyShopBundle\Entity\Product;
use Symfony\Component\EventDispatcher\Event;

class ProductEvent extends Event
{
    /**
     * @var Product
     */
    private $product;
    public function __construct(Product $product)
    {
        $this->product = $product;
    }
    public function getProduct()
    {
        return $this->product;
    }
}