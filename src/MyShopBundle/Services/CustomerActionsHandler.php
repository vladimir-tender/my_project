<?php
/**
 * Created by PhpStorm.
 * User: hysteria
 * Date: 17.04.17
 * Time: 12:50
 */

namespace MyShopBundle\Services;


use Doctrine\ORM\EntityManager;
use MyShopBundle\Entity\Product;

class CustomerActionsHandler
{
    /**
     * @var EntityManager $em
     */
    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function getProductsByParentCategory($parent_cat_id)
    {
        $parentCategory = $this->em->getRepository("MyShopBundle:Category")
            ->findBy(['idparent' => $parent_cat_id], ['category' => 'ASC']);

        $productList = [];
        foreach ($parentCategory as $category) {
            $productCategoryList = $this->getProductsByCategory($category->getId());
            foreach ($productCategoryList as $product) {
                $productList[] = $product;
            }
        }

        $productList = $this->filterActiveProducts($productList);

        return $productList;
    }

    public function getProductsByCategory($cat_id)
    {
        $category = $this->em->getRepository("MyShopBundle:Category")->find($cat_id);
        $productList = $category->getProductList();
        $this->countActualPrice($productList);

        $productList = $this->filterActiveProducts($productList);

        return $productList;
    }

    public function getAllProducts()
    {
        $productList = $this->em->getRepository("MyShopBundle:Product")
            ->findBy(["status" => "1"], ["category" => "ASC"]);
        $this->countActualPrice($productList);

        return $productList;
    }

    public function countActualPrice($productList)
    {
        /**
         * @var Product $product
         */
        foreach ($productList as $product) {
            if ($product->getDiscount() != 0.0) {
                $product->actualPrice = $product->getPrice() * (100 - $product->getDiscount()) / 100;
            }
        }
    }

    public function getCategoriesForMenu()
    {
        $categoryList = $this->em->getRepository("MyShopBundle:Category")
            ->findBy(["idparent" => null], ["category" => "ASC"]);
        return $categoryList;
    }


    public function filterActiveProducts($productsList)
    {
        $filteredProductList = [];

        /**
         * @var Product $product
         */
        foreach ($productsList as $product) {
            if ($product->getStatus() == 1) {
                $filteredProductList[] = $product;
            }
        }
        return $filteredProductList;
    }
}