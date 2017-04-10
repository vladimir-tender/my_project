<?php

namespace MyShopBundle\Controller;

use Ivory\CKEditorBundle\Exception\Exception;
use MyShopBundle\Entity\Customer;
use MyShopBundle\Entity\Product;
use MyShopBundle\Form\CustomerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

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
    public function loginAction(Request $request)
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);

        $handler = $this->get("my_shop.customer.handler.auth");
        $auth = $handler->customerActionHandler($request, $form, $customer);

        if ($auth === true) {
            //var_dump($auth);
            //var_dump($customer);
            $this->addFlash("success", "User added, please login");
            $this->redirectToRoute("my_shop.login");
        } else {
            //var_dump($auth);
            return [
                'form' => $form->createView()
            ];
        }


        return [
            'form' => $form->createView()
        ];

    }

    public function authAction()
    {

    }

    public function logoutAction()
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

    /**
     * @Template()
     */
    public function ownPageAction($pageKey)
    {
        $page = $this->getDoctrine()->getRepository("MyShopBundle:Page")->findOneBy(["pageKey" => $pageKey]);

        return [
            "page" => $page
        ];
    }

}
