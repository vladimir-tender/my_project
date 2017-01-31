<?php

namespace MyShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use MyShopBundle\Form\ProductType;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="MyShopBundle\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column( name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true, name="id_parent")
     */
    private $idParent;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true, name="category")
     */
    private $category;

/////////////////////////////////////////////////////////
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="MyShopBundle\Entity\Product", mappedBy="category")
     */
    private $productList;

    public function __construct()
    {
        $this->productList = new ArrayCollection();
    }

    public function addProduct(Product $product)
    {
        /** @var Product $product */
        $product->setCategory($this);
        $this->productList[] = $product;
    }

    /**
     * @return ArrayCollection
     */
    public function getProductList()
    {
        return $this->productList;
    }

    /**
     * @param mixed Product
     */
    public function setProductList(Product $productList)
    {
        $this->productList = $productList;
    }
/////////////////////////////////////////////////////////
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
     * Set idParent
     *
     * @param integer $idParent
     *
     * @return Category
     */
    public function setIdParent($idParent)
    {
        $this->idParent = $idParent;

        return $this;
    }

    /**
     * Get idParent
     *
     * @return int
     */
    public function getIdParent()
    {
        return $this->idParent;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return Category
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }
}

