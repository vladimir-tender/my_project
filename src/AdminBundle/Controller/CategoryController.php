<?php
/**
 * Created by PhpStorm.
 * User: hysteria
 * Date: 03.02.17
 * Time: 21:16
 */

namespace AdminBundle\Controller;


use MyShopBundle\Entity\Category;
use MyShopBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * @Template()
     */
    public function categoryListAction()
    {
        $categoryList = $this->getDoctrine()->getRepository("MyShopBundle:Category")->findAll();

        return ["categoryList" => $categoryList];
    }

    /**
     * @Template()
     */
    public function editAction(Request $request, $category_id)
    {
        $category = $this->getDoctrine()->getRepository("MyShopBundle:Category")->find($category_id);
        $form = $this->createForm(CategoryType::class, $category);

        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            if ($form->isSubmitted())
            {
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
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        if ($request->isMethod("POST"))
        {
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
}