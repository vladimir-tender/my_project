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
use Knp\Component\Pager\Paginator;

class CustomerActionsHandler
{
    /**
     * @var EntityManager $em
     */
    private $em;
    private $paginator;

    public function __construct($em, Paginator $paginator)
    {
        $this->em = $em;
        $this->paginator = $paginator;
    }

    public function getAllProducts($page = 1, $productsPerPage)
    {
        $dql = "SELECT p FROM MyShopBundle:Product p WHERE p.status = 1 ORDER BY p.category ASC";
        $productList = $this->em->createQuery($dql)->getResult();

        if (is_null($productsPerPage)) {
            $productsPerPage = 24;
        }
        /*$productList = $this->em->getRepository("MyShopBundle:Product")
            ->findBy(["status" => "1"], ["category" => "ASC"]);*/

        $productList = $this->filterActiveProducts($productList);
        $this->countActualPrice($productList);

        $productList = $this->paginator->paginate($productList, $page, $productsPerPage);

        return $productList;
    }

    public function getProductsByParentCategory($parent_cat_id)
    {//TODO: paginate getProductsByParentCategory
        $parentCategory = $this->em->getRepository("MyShopBundle:Category")
            ->findBy(['idparent' => $parent_cat_id], ['category' => 'ASC']);

        $productList = [];
        foreach ($parentCategory as $category) {
            $productCategoryList = $this->getProductsByCategory($category->getId());
            foreach ($productCategoryList as $product) {
                $productList[] = $product;
            }
        }

        return $productList;
    }

    public function getProductsByCategory($cat_id)
    {//TODO: paginate getProductsByCategory
        $category = $this->em->getRepository("MyShopBundle:Category")->find($cat_id);
        $productList = $category->getProductList();

        $productList = $this->filterActiveProducts($productList);
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
    {//now unused
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