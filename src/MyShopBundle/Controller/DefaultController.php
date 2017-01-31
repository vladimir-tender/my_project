<?php

namespace MyShopBundle\Controller;

use MyShopBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    public function indexAction($firstName)
    {

        //$result = $this->getProduct(3);
        $result = $this->getProducts();

        return $this->render('MyShopBundle:Default:index.html.twig', [
            "firstname" => $firstName,
            "products" => $result
        ]);
    }

    /**
     * @Template()
     */
    public function createProductAction()
    {
        $product = new Product();
        $product->setCategory(1);
        $product->setProductname("Mobile Phone PX");
        $product->setPrice(150);
        $product->setoffer(0);
        $product->setDiscount(0);
        $product->setDescription("This is the best Choice with 256 GB on board and 15 Mpx Camera!");
        $product->setImgpath("img/mobile1.jpg");

        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();

        $manager->persist($product);
        $manager->flush();

        return [
            "product" => $product
        ];
    }

    public function getProduct($id)
    {
        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();

        $repository = $manager->getRepository("MyShopBundle:Product");
        $product = $repository->find($id);

        /*return [
            "product" => $product
        ];*/
        return $product;
    }

    public function getProducts()
    {
        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();

        $repository = $manager->getRepository("MyShopBundle:Product");
        $products = $repository->findAll();

        /*return [
            "product" => $product
        ];*/
        return $products;
    }



}
