<?php

namespace AdminBundle\Controller;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use MyShopBundle\Entity\ProductPhoto;
use MyShopBundle\Form\ProductPhotoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

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
    {//Request $request, $idProduct
        $manager = $this->getDoctrine()->getManager();
        $product = $manager->getRepository("MyShopBundle:Product")->find($idProduct);

        if ($product == null) {
            return $this->createNotFoundException("Product not found!");
        }

        $photo = new ProductPhoto();
        $form = $this->createForm(ProductPhotoType::class, $photo);

        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            $filesAr = $request->files->get("myshopbundle_productphoto");

            /** @var UploadedFile $photoFile */
            $photoFile = $filesAr["photoFile"];
            $mimeType = $photoFile->getClientMimeType();
            if ($mimeType !== "image/jpeg" and $mimeType !== "image/jpg" and $mimeType !== "image/gif" and $mimeType !== "image/png") {
                throw new InvalidArgumentException("MimeType is blocked!");
            }

            $fileExt = $photoFile->getClientOriginalExtension();
            if ($fileExt !== "jpg" and $fileExt !== "png" and $fileExt !== "gif" and $fileExt !== "jpeg") {
                throw new InvalidArgumentException("Extension is blocked!");
            }

            $photoFileName = $product->getId() . rand(100000000, 999999999) . "." . $photoFile->getClientOriginalExtension();
            $photoDirPath = $this->get("kernel")->getRootDir() . "/../web/photos/";

            $photoFile->move($photoDirPath, $photoFileName);

            $photo->setFileName($photoFileName);
            $photo->setProduct($product);

            $manager->persist($photo);
            $manager->flush();

            return $this->redirectToRoute("admin.product_photo_list", ["idProduct" => $product->getId()]);
        }


        return [
            "form" => $form->createView(),
            "product" => $product
        ];


    }

    /**
     * @Template()
     */
    public function editAction($idPhoto, Request $request)
    {

        $manager = $this->getDoctrine()->getManager();
        $productPhoto = $manager->getRepository("MyShopBundle:ProductPhoto")->find($idPhoto);

        $form = $this->createForm(ProductPhotoType::class, $productPhoto);

        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            $filesAr = $request->files->get("myshopbundle_productphoto");


            /** @var UploadedFile $photoFile */
            $photoFile = $filesAr["photoFile"];
            if ($photoFile->isFile())
            {
                $mimeType = $photoFile->getClientMimeType();
                if ($mimeType !== "image/jpeg" and $mimeType !== "image/jpg" and $mimeType !== "image/gif" and $mimeType !== "image/png") {
                    throw new InvalidArgumentException("MimeType is blocked!");
                }

                $fileExt = $photoFile->getClientOriginalExtension();
                if ($fileExt !== "jpg" and $fileExt !== "png" and $fileExt !== "gif" and $fileExt !== "jpeg") {
                    throw new InvalidArgumentException("Extension is blocked!");
                }

                $photoFileName = $productPhoto->getFileName();
                $photoDirPath = $this->get("kernel")->getRootDir() . "/../web/photos/";

                $photoFile->move($photoDirPath, $photoFileName);

                $productPhoto->setFileName($photoFileName);
            }
            $manager->persist($productPhoto);
            $manager->flush();

            return $this->redirectToRoute("admin.product.list");
        }


        return [
            "myshopbundle_productphoto[photoFile]"=>$productPhoto->getFileName(),
            "form" => $form->createView(),
            "photo" => $productPhoto,
            "request" => $request
        ];
    }

}