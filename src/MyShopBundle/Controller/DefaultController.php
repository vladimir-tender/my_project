<?php

namespace MyShopBundle\Controller;

use MyShopBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        $categoryList = $this->getDoctrine()->getRepository("MyShopBundle:Category")
            ->findBy(["idparent" => null], ["category" => "ASC"]);
        $productList = $this->getDoctrine()->getRepository("MyShopBundle:Product")
            ->findBy(["status" => "1"], ["category" => "ASC"]);

        return [
            "categoryList" => $categoryList,
            "productList" => $productList
        ];

    }

    /**
     * @Template()
     */
    public function loginAction()
    {

    }

    public function menuRenderAction()
    {
        $categoryList = $this->getDoctrine()->getRepository("MyShopBundle:Category")
            ->findBy(["idparent" => null], ["category" => "ASC"]);
        return $this->render("@MyShop/Default/menu.html.twig", [
            "categoryList" => $categoryList
        ]);

    }

}
