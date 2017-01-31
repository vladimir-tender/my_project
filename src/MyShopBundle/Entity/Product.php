<?php

namespace MyShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="MyShopBundle\Repository\ProductRepository")
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="id")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="MyShopBundle\Entity\Category", inversedBy="productList")
     * @ORM\JoinColumn(name="id_category", referencedColumnName="id")
     */
    private $category;


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true, name="productname")
     */
    private $productname;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true, name="price")
     */
    private $price;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true, name="adddate")
     */
    private $adddate;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true, name="offer")
     */
    private $offer;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true, name="discount")
     */
    private $discount;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true, name="description")
     */
    private $description;

    /**
     * @var sting
     *
     * @ORM\Column(type="string", length=255, nullable=true, name="imgpath")
     */
    private $imgpath;


    public function __construct()
    {
        $date = new \DateTime("now");
        $this->setAdddate($date);
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
     * Set category
     *
     * @param integer $category
     *
     * @return Product
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return int
     */
    public function getCategory()
    {
        return $this->category;
    }


    /**
     * Set productname
     *
     * @param string $productname
     *
     * @return Product
     */
    public function setProductname($productname)
    {
        $this->productname = $productname;

        return $this;
    }

    /**
     * Get productname
     *
     * @return string
     */
    public function getProductname()
    {
        return $this->productname;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
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
     * Set adddate
     *
     * @param \DateTime $adddate
     *
     * @return Product
     */
    public function setAdddate($adddate)
    {
        $this->adddate = $adddate;

        return $this;
    }

    /**
     * Get adddate
     *
     * @return \DateTime
     */
    public function getAdddate()
    {
        return $this->adddate;
    }

    /**
     * Set offer
     *
     * @param integer $offer
     *
     * @return Product
     */
    public function setOffer($offer)
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * Get offer
     *
     * @return int
     */
    public function getOffer()
    {
        return $this->offer;
    }

    /**
     * Set discount
     *
     * @param float $discount
     *
     * @return Product
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set imgpath
     *
     * @param string $imgpath
     *
     * @return Product
     */
    public function getImgpath()
    {
        return $this->imgpath;
    }

    /**
     * Get imgpath
     *
     * @return string
     */
    public function setImgpath($imgpath)
    {
        $this->imgpath = $imgpath;
    }


}

