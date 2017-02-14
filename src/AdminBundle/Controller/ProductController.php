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
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);

            $manager = $this->getDoctrine()->getManager();
            $product->setStatus(1);
            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', 'Товар успешно добавлен');
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

        $this->addFlash('success', 'Товар удален');
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
                $cat_id = $product->getCategory()->getId();
                //var_dump($cat_id);
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($product);
                $manager->flush();

                $this->addFlash("success", "Изменения сохранены");
                return $this->redirectToRoute("admin.product.listbycategory", [
                    "id_category" => $cat_id
                ]);
            }
        }

        return [
            "form" => $form->createView(),
            "product" => $product
        ];
    }

    public function changeStatusAction(Request $request, $id)
    {
        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();
        $product = $doctrine->getRepository("MyShopBundle:Product")->find($id);

        if ($product->getStatus() == 1) {
            $new_status = 0;
        } else {
            $new_status = 1;
        }

        $product->setStatus($new_status);
        $manager->persist($product);
        $manager->flush();

        $this->addFlash("success", "Статус товара " . $product->getProductname() . " изменен");
        return $this->redirect($request->headers->get('referer'));
    }

}
