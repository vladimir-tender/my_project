<?php

namespace AdminBundle\Controller;


use MyShopBundle\Entity\Category;
use MyShopBundle\Form\CategoryEditType;
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
            $categoryTree = $categoryUtility->getCategoryTree($categoryRepository);
        } catch (\Exception $exception) {
            die("Something wrong with Category method getCategoryTree. " . $exception);
        }

        return ["categoryTree" => $categoryTree];
    }

    /**
     * @Template()
     */
    public function editAction(Request $request, $category_id)
    {
        $category = $this->getDoctrine()->getRepository("MyShopBundle:Category")->find($category_id);


        $form = $this->createForm(CategoryEditType::class, $category);

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($category);
                $manager->flush();

                return $this->redirectToRoute("admin.category.list");
            }
        }

        return [
            "form" => $form->createView(),
            "category" => $category
        ];
    }


    /**
     * @Template()
     */
    public function addAction(Request $request)
    {


        $categoryRepository = $this->getDoctrine()->getRepository("MyShopBundle:Category");
        $categoryUtility = $this->get("admin.cat_utility");
        $categoryChoicesArray = $categoryUtility->getCategoryParentChoicesArray($categoryRepository);

        $form = $this->createForm(CategoryType::class, '', [
            'data' => $categoryChoicesArray,
            'data_class' => null
        ]);

        if ($request->isMethod("POST")) {

            $category = new Category();

            $form->handleRequest($request);

            $category->setCategory($form->getData()['category']);

            if (isset($form->getData()['idparent'])) {
                $parentCategoryId = $categoryRepository->find($form->getData()['idparent']);
                $category->setIdParent($parentCategoryId);
            }

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
}