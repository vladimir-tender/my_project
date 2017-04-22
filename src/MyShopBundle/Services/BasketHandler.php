<?php
/**
 * Created by PhpStorm.
 * User: hysteria
 * Date: 22.04.17
 * Time: 13:04
 */

namespace MyShopBundle\Services;


use Doctrine\ORM\EntityManager;
use MyShopBundle\Entity\Customer;
use MyShopBundle\Entity\CustomerOrder;
use MyShopBundle\Entity\OrderProducts;
use MyShopBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Request;

class BasketHandler
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getCurrentOrder(Customer $customer)
    {
        $em = $this->em;
        $order = $em->getRepository("MyShopBundle:CustomerOrder")
            ->findOneBy([
                'customer' => $customer,
                'status' => CustomerOrder::STATUS_CURRENT
            ]);

        if (is_null($order)) {
            $order = $this->createOrder($customer);
        }

        return $order;
    }

    public function createOrder(Customer $customer)
    {
        $order = new CustomerOrder();

        $em = $this->em;
        $order->setStatus(CustomerOrder::STATUS_CURRENT);
        $order->setCustomer($customer);

        $em->persist($order);
        $em->flush();

        return $order;
    }

    public function addProductToBasket(Product $catalog_product, Customer $customer)
    {
        /**
         * @var CustomerOrder $order
         */
        $order = $this->getCurrentOrder($customer);


        $em = $this->em;

        $order_id = $order->getId();
        $catalog_id = $catalog_product->getId();

        $product = $em->getRepository("MyShopBundle:OrderProducts")
            ->findOneBy(["customerOrder" => $order_id, "catalogid" => $catalog_id]);


        if ($product == null) {

            /**
             * @var Product $catalog_product
             */
            $product = new OrderProducts();
            $product->setCatalogid($catalog_product->getId());
            $product->setPrice($catalog_product->getActualPrice());
            $product->setModel($catalog_product->getProductname());
            $product->setCount(1);
            $product->setCustomerOrder($order);

        } else {
            $product->setCount($product->getCount() + 1);
        }

        $em->persist($product);
        $em->flush();

    }

    public function refreshBasket(Request $request, $customer)
    {
        $em = $this->em;
        $order = $this->getCurrentOrder($customer);
        $products = $order->getProducts();

        /**
         * @var OrderProducts $product
         */
        foreach ($products as $product) {
            $key = "product_" . $product->getId();
            if ($productCount = $request->get($key)) {
                if ($productCount > 0) {
                    $product->setCount($productCount);
                    $em->persist($product);
                }
            }
        }
        $em->flush();
    }

    public function removeProductFromBasket(OrderProducts $product)
    {
        $em = $this->em;

        try {
            $em->remove($product);
            $em->flush();
        } catch (\Exception $exception) {
            throw new \Exception("No such product in basket");
        }

    }
}