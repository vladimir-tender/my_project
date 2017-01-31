<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MyShopBundle\Entity\Product;
use MyShopBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProductController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Default:index.html.twig');
    }

    /**
     * @Template()
     */
    public function addAction()
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        return [
            "form" => $form->createView()
        ];
    }

    /**
     * @Template()
     */
    public function listAction()
    {
        $categoryList = $this->getDoctrine()->getRepository("MyShopBundle:Category")->findAll();

        return ["categoryList" => $categoryList];
    }

    /**
     * @Template()
     */
    public function listByCategoryAction($id_category)
    {

        $category = $this->getDoctrine()->getRepository("MyShopBundle:Category")->find($id_category);
        $productList = $category->getProductList();

        return ["productList" => $productList];
    }
}
