<?php

namespace AdminBundle\Controller;


use MyShopBundle\Entity\Category;
use MyShopBundle\Form\CategoryType;
use MyShopBundle\MyShopBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * @Template()
     */
    public function categoryControlListAction()
    {
        $categoryRepository = $this->getDoctrine()->getRepository("MyShopBundle:Category");

        $categoryUtility = $this->get("admin.cat_utility");
        try {
            $categoryTree = $categoryUtility->getCategoryTree();
        } catch (\Exception $exception) {
            die("Something wrong with Category method getCategoryTree. " . $exception);
        }

        return ["categoryTree" => $categoryTree];
    }

    /**
     * @Template()
     * @param Request $request
     * @param $category_id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request, $category_id)
    {
        $category = $this->getDoctrine()->getRepository("MyShopBundle:Category")->find($category_id);

        $form = $this->createForm(CategoryType::class, $category);

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                $category_id = $category->getId();

                if ($category->getIdParent() == null) {
                    $category_parent_id = null;
                } else {
                    $category_parent_id = $category->getIdParent()->getId();
                }

                if (isset($category_id) AND $category_id != $category_parent_id) {
                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($category);
                    $manager->flush();
                    //echo $category->getId()." ".$category->getIdParent()->getId();

                    ///mailReport
                    $mailer = $this->get("admin.actions_mailer");
                    $message_body = "Категория #" . $category->getId() . " изменена. ";

                    try {
                        $mailer->sendReportUserAction($message_body, $this->getUser());
                        $this->addFlash("success", "Категория изменена.");
                    } catch (\Exception $exception) {
                        $this->addFlash("failed", "Ошибка отправки письма." . $exception);
                    }
                    ///mailReport

                    return $this->redirectToRoute("admin.category.list");
                } else {
                    $this->addFlash('success', 'Выберите другую родительскую категорию');
                }
            }
        }
        return [
            "form" => $form->createView(),
            "category" => $category
        ];
    }


    /**
     * @Template()
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        if ($request->isMethod("POST")) {

            $form->handleRequest($request);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();

            return $this->redirectToRoute("admin.category.list");
        }

        return [
            "form" => $form->createView()
        ];
    }

    public function deleteAction($category_id)
    {
        $category = $this->getDoctrine()->getRepository("MyShopBundle:Category")->find($category_id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($category);
        $manager->flush();

        return $this->redirectToRoute("admin.category.list");
    }

    /**@Template()*/
    public function categoriesForTreeJsonAction()
    {
        $categoryUtility = $this->get("admin.cat_utility");

        $jsonData = $categoryUtility->getCategoriesForTreeJson();

        return $this->render("@MyShop/Default/categorytree.html.twig", [
            "categoriesForTreeJson" => $jsonData
        ]);
    }
}