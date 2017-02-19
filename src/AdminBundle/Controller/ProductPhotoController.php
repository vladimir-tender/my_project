<?php

namespace AdminBundle\Controller;

use MyShopBundle\Entity\ProductPhoto;
use MyShopBundle\Form\ProductPhotoEditType;
use MyShopBundle\Form\ProductPhotoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class ProductPhotoController extends Controller
{
    /**
     * @Template()
     */
    public function listAction($idProduct)
    {
        $product = $this->getDoctrine()->getManager()->getRepository("MyShopBundle:Product")->find($idProduct);
        return [
            "product" => $product
        ];
    }

    /**
     * @Template()
     */
    public function addAction(Request $request, $idProduct)
    {
        $manager = $this->getDoctrine()->getManager();
        $product = $manager->getRepository("MyShopBundle:Product")->find($idProduct);

        if ($product == null) {
            return $this->createNotFoundException("Product not found!");
        }

        $photo = new ProductPhoto();
        $form = $this->createForm(ProductPhotoType::class, $photo);

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);

            /** @var UploadedFile $photoFile */

            $photoFile = $request->files->get("myshopbundle_productphoto")["photoFile"];

            $imageUtility = $this->get("admin.img_utility");

            $photoFileName = $imageUtility->photoFileSave($product->getId(), $photoFile);

            $photo->setFileName($photoFileName);
            $photo->setProduct($product);

            $manager->persist($photo);
            $manager->flush();

            return $this->redirectToRoute("admin.product_photo_list", [
                "idProduct" => $product->getId()
            ]);
        }

        return [
            "form" => $form->createView(),
            "product" => $product
        ];
    }

    /**
     * @Template()
     * @param $idPhoto
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction($idPhoto, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $photo = $manager->getRepository("MyShopBundle:ProductPhoto")->find($idPhoto);
        $product_id = $photo->getProduct()->getId();

        $form = $this->createForm(ProductPhotoEditType::class, $photo);

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);

            /** @var UploadedFile $photoFile */

            if (isset($request->files->get("myshopbundle_productphoto")["photoFile"])) {

                $photoFile = $request->files->get("myshopbundle_productphoto")["photoFile"];
                $imageUtility = $this->get("admin.img_utility");
                $photoDirPrefix = $this->getParameter("image_upload_dir");

                $photoFileName = $imageUtility->photoFileSave($product_id, $photoFile);

                $oldFileName = $photo->getFileName();
                $oldFile = $photoDirPrefix . $oldFileName;

                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
                $photo->setFileName($photoFileName);
            }

            $manager->persist($photo);
            $manager->flush();

            return $this->redirectToRoute("admin.product_photo_list", [
                "idProduct" => $product_id
            ]);
        }

        return [
            "form" => $form->createView(),
            "photo" => $photo
        ];
    }

    public function deleteAction($idPhoto, Request $request)
    {

        $photo = $this->getDoctrine()->getRepository("MyShopBundle:ProductPhoto")->find($idPhoto);
        $manager = $this->getDoctrine()->getManager();

        $photoDirPath = $this->getParameter("image_upload_dir");
        $fileName = $photoDirPath . $photo->getFileName();

        if (file_exists($fileName)) {
            unlink($fileName);
        }

        $manager->remove($photo);
        $manager->flush();

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    public function mainPhotoAction($product_id, $photo_id)
    {
        $manager = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository("MyShopBundle:Product")->find($product_id);
        $photo = $this->getDoctrine()->getRepository("MyShopBundle:ProductPhoto")->find($photo_id);

        $imageUtility = $this->get("admin.img_utility");
        try {
            $photoFileName = $imageUtility->setMainProductPhoto($product, $photo);
        } catch (\Exception $exception) {
            die("Something wrong with Main photo set!");
        }
        $product->setMainPhoto($photoFileName);

        $manager->persist($product);
        $manager->flush();

        return $this->redirectToRoute("admin.product_photo_list", [
            "idProduct" => $product_id
        ]);
    }

}