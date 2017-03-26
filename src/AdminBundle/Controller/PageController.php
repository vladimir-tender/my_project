<?php
/**
 * Created by PhpStorm.
 * User: hysteria
 * Date: 26.03.17
 * Time: 13:14
 */

namespace AdminBundle\Controller;


use MyShopBundle\Entity\Page;
use MyShopBundle\Form\PageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PageController extends Controller
{
    public function indexAction()
    {

    }

    /**
     * @Template()
     */
    public function pageListAction()
    {
        $pageList = $this->getDoctrine()->getRepository("MyShopBundle:Page")->findAll();

        return [
            "pageList" => $pageList
        ];
    }

    /**
     * @Template()
     */
    public function addAction(Request $request)
    {
        $page = new Page();

        $form = $this->createForm(PageType::class, $page);

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($page);
            $manager->flush();

            return $this->redirectToRoute("admin.page_list");
        }

        return [
            "form" => $form->createView()
        ];
    }

    /**
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $page = $this->getDoctrine()->getRepository("MyShopBundle:Page")->find($id);

        $form = $this->createForm(PageType::class, $page);

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($page);
            $manager->flush();

            return $this->redirectToRoute("admin.page_list");
        }

        return [
            "form" => $form->createView(),
            "pageid" => $page->getId()
        ];
    }

    /**
     * @Template()
     */
    public function pageViewAction($id)
    {
        $page = $this->getDoctrine()->getRepository("MyShopBundle:Page")->find($id);
        return ["page" => $page];
    }

    public function deleteAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $page = $manager->getRepository("MyShopBundle:Page")->find($id);
        $manager->remove($page);
        $manager->flush();

        return $this->redirectToRoute("admin.page_list");
    }
}