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
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsernameLogin = "";

        if (count($error) > 0) {
            $lastUsernameLogin = $authenticationUtils->getLastUsername();
            $this->addFlash("login_failed", "Authentication error");
        }
        return [
            'lastlogin' => $lastUsernameLogin,
        ];
    }


    /**
     * @Template()
     */
    public function registrationAction(Request $request)
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);

        $handler = $this->get("my_shop.customer.handler.auth");
        $auth = $handler->customerActionHandler($request, $form, $customer);

        if ($auth === true) {
            $this->addFlash("success", "User added, please login");
            return $this->redirectToRoute("my_shop.index");
        } elseif ($auth === false || $auth === null) {
            return [
                'form' => $form->createView()
            ];
        } elseif ($auth == 'customerExists') {
            $this->addFlash("registration_error", "User with such email Exists");
            return $this->render("@MyShop/Default/login.html.twig", [
                'form' => $form->createView()
            ]);
        } else {
            return $this->render("@MyShop/Default/login.html.twig", [
                'form' => $form->createView()
            ]);
        }

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

    public function confirmEmailAction($hash)
    {
        $handler = $this->get("my_shop.customer.handler.auth");
        $confirm = $handler->confirmEmailHash($hash);
        if ($confirm === true) {
            $this->addFlash("success", "Email confirmed. Please login!");
        } else {
            $this->addFlash("success", "Email confirmed failed.");
        }
        return $this->redirectToRoute("my_shop.index");
    }

}
