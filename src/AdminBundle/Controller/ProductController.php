<?php

namespace AdminBundle\Controller;


use MyShopBundle\Entity\Category;
use MyShopBundle\MyShopBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MyShopBundle\Entity\Product;
use MyShopBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use AdminBundle\Event\ProductEvent;

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

            $validator = $this->get('validator');
            $errors = $validator->validate($product);
            if (count($errors) > 0) {

                return [
                    "form" => $form->createView()
                ];
                //$errorsString = (string) $errors;
            }

            $manager = $this->getDoctrine()->getManager();
            $product->setStatus(1);
            $manager->persist($product);
            $manager->flush();

            $event = new ProductEvent($product);
            $this->get("event_dispatcher")->dispatch("product_add_event", $event);

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
            $categoryTree = $categoryUtility->getCategoryListTree();
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
        $parentCategory = $this->getDoctrine()->getRepository("MyShopBundle:Category")
            ->findBy(['idparent' => $id_category], ['category' => 'ASC']);
        $productList = [];

        if ($parentCategory == null) {
            $category = $this->getDoctrine()->getRepository("MyShopBundle:Category")->find($id_category);
            $productList = $category->getProductList();

        } else {
            foreach ($parentCategory as $category) {
                $productListArray = $category->getProductList();
                foreach ($productListArray as $product) {
                    $productList[] = $product;
                }
            }
            /**
             * @var Category $parentCategory
             */
            $category = $productList[0]->getCategory()->getIdParent()->getCategory();

            //var_dump($productList);
        }


        return [
            "productList" => $productList,
            "category" => $category
        ];
    }

    /**
     * @Template()
     */
    public function productListAction(Request $request)
    {
        if (($request->get("page") === null)) {
            $page = 1;
        } else {
            $page = $request->get("page");
        }

        $productListPagination = $this->get("admin.productUtils")->getPaginationProductList($page);
        return ["productList" => $productListPagination];
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

                $event = new ProductEvent($product);
                $this->get("event_dispatcher")->dispatch("product_edit_event", $event);

                $this->addFlash("success", "Изменения сохранены");

                ///mailReport
                $mailer = $this->get("admin.actions_mailer");
                //$message_body = "Статус товара #" . $product->getId() . " \"" . $product->getProductname() . "\" изменен. ";
                $message_body = $this->renderView("@Admin/MailerForms/productchangereporthtml.twig", [
                    "product" => $product
                ]);
                try {
                    $mailer->sendReportUserAction($message_body, $this->getUser());
                    //$this->addFlash("success", ". Статус товара изменен.");
                } catch (\Exception $exception) {
                    $this->addFlash("failed", "Ошибка отправки письма." . $exception);
                }
                ///mailReport


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

        $this->addFlash("success", "Статус товара " . $product->getProductname() . " изменен.");

        ///mailReport
        $mailer = $this->get("admin.actions_mailer");
        $message_body = "Статус товара #" . $product->getId() . " \"" . $product->getProductname() . "\" изменен. ";
        try {
            $mailer->sendReportUserAction($message_body, $this->getUser());
            //$this->addFlash("success", ". Статус товара изменен.");
        } catch (\ErrorException $exception) {
            $this->addFlash("failed", "Ошибка отправки письма." . $exception);
        }

        ///mailReport


        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Template
     */
    public function productInfoAction($id)
    {
        $product = $this->getDoctrine()->getRepository("MyShopBundle:Product")->find($id);

        return [
            "product" => $product
        ];
    }

}
