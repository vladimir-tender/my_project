<?php

namespace AdminBundle\Controller;

use MyShopBundle\MyShopBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MyShopBundle\Entity\Product;
use MyShopBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Default:index.html.twig');
    }

    /**
     * @Template()
     */
    public function addAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($product);
            $manager->flush();

            return $this->redirectToRoute("admin.product.list");
        }

        return [
            "form" => $form->createView()
        ];


    }

    /**
     * @Template()
     */
    public function categoryListAction()
    {
        $categoryRepository = $this->getDoctrine()->getRepository("MyShopBundle:Category");

        $categoryUtility = $this->get("admin.cat_utility");
        try {
            $categoryTree = $categoryUtility->getCategoryListTree($categoryRepository);
        } catch (\Exception $exception) {
            die("Something wrong with Category method getCategoryTree. " . $exception);
        }

        return ["categoryTree" => $categoryTree];
    }

    /**
     * @Template()
     */
    public function listByCategoryAction($id_category)
    {

        $category = $this->getDoctrine()->getRepository("MyShopBundle:Category")->find($id_category);
        $productList = $category->getProductList();

        return [
            "productList" => $productList,
            "category" => $category
        ];
    }

    /**
     * @Template()
     */
    public function productListAction()
    {
        $productList = $this->getDoctrine()->getRepository("MyShopBundle:Product")->findAll();

        return ["productList" => $productList];
    }

    public function deleteAction($id)
    {
        $product = $this->getDoctrine()->getRepository("MyShopBundle:Product")->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($product);
        $manager->flush();

        return $this->redirectToRoute("admin.product.list");
    }

    /**
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $product = $this->getDoctrine()->getRepository("MyShopBundle:Product")->find($id);

        $form = $this->createForm(ProductType::class, $product);

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($product);
                $manager->flush();

                return $this->redirectToRoute("admin.product.list");
            }
        }

        return [
            "form" => $form->createView(),
            "product" => $product
        ];
    }

}
