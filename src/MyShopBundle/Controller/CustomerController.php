<?php
/**
 * Created by PhpStorm.
 * User: hysteria
 * Date: 10.04.17
 * Time: 19:26
 */

namespace MyShopBundle\Controller;


use MyShopBundle\Entity\OrderProducts;
use MyShopBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class CustomerController extends Controller
{
    /**
     * @Template()
     */
    public function mainAction()
    {
        $customer = $this->getUser();

        $basket_handler = $this->get("my_shop.basket.handler");
        $currentOrder = $basket_handler->getCurrentOrder($customer);

        return [
            'order' => $currentOrder
        ];
    }

    public function addProductToBasketAction(Product $product, Request $request)
    {
        $customer = $this->getUser();
        $basket_handler = $this->get("my_shop.basket.handler");

        try {
            $basket_handler->addProductToBasket($product, $customer);

            $this->addFlash("success", "Product added to basket");
            return $this->redirect($request->headers->get('referer'));
        } catch (Exception $exception) {
            throw new \Exception("Some problem with add product to basket");
        }

    }

    public function refreshBasketAction(Request $request)
    {
        $basket_handler = $this->get('my_shop.basket.handler');
        $customer = $this->getUser();
        try {
            $basket_handler->refreshBasket($request, $customer);
            return $this->redirectToRoute("my_shop.customer.main");
        } catch (\Exception $exception) {
            throw new Exception("Some problem with refresh basket");
        }

    }

    public function removeFromBasketAction(OrderProducts $product)
    {
        $basket_handler = $this->get('my_shop.basket.handler');
        $basket_handler->removeProductFromBasket($product);

        return $this->redirectToRoute("my_shop.customer.main");
    }


}