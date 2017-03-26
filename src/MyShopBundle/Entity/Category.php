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
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true, name="category")
     */
    private $category;

    /**
     *
     * @ORM\ManyToOne(targetEntity="MyShopBundle\Entity\Category", inversedBy="childrenCategories")
     * @ORM\JoinColumn(name="idparent", referencedColumnName="id")
     */
    private $idparent;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="MyShopBundle\Entity\Category", mappedBy="idparent")
     */
    private $childrenCategories;

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
/*    public function setProductList(Product $productList)
    {
        $this->productList = $productList;
    }*/
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
     * @param integer $id_parent
     *
     * @return Category
     */
    public function setIdParent($idparent)
    {
        $this->idparent = $idparent;

        return $this;
    }

    /**
     * Get idParent
     *
     * @return mixed
     */
    public function getIdParent()
    {
        return $this->idparent;
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

    /**
     * Add childrenCategory
     *
     * @param \MyShopBundle\Entity\Category $childrenCategory
     *
     * @return Category
     */
    public function addChildrenCategory(\MyShopBundle\Entity\Category $childrenCategory)
    {
        $this->childrenCategories[] = $childrenCategory;

        return $this;
    }

    /**
     * Remove childrenCategory
     *
     * @param \MyShopBundle\Entity\Category $childrenCategory
     */
    public function removeChildrenCategory(\MyShopBundle\Entity\Category $childrenCategory)
    {
        $this->childrenCategories->removeElement($childrenCategory);
    }

    /**
     * Get childrenCategories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildrenCategories()
    {
        return $this->childrenCategories;
    }

    /**
     * Add productList
     *
     * @param \MyShopBundle\Entity\Product $productList
     *
     * @return Category
     */
    public function addProductList(\MyShopBundle\Entity\Product $productList)
    {
        $this->productList[] = $productList;

        return $this;
    }

    /**
     * Remove productList
     *
     * @param \MyShopBundle\Entity\Product $productList
     */
    public function removeProductList(\MyShopBundle\Entity\Product $productList)
    {
        $this->productList->removeElement($productList);
    }
}
