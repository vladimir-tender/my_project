<?php

namespace AdminBundle\Services\Image;


use MyShopBundle\Entity\Product;
use MyShopBundle\Entity\ProductPhoto;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Eventviva\ImageResize;

class ImageUtility
{
    private $supportedImgTypeList;
    private $mainProductPhotoHeight;
    private $mainProductPhotoWidth;
    private $photoDirPrefix;

    public function __construct($imageTypeList, $mainProductPhotoHeight, $mainProductPhotoWidth, $photoDirPrefix)
    {
        $this->supportedImgTypeList = $imageTypeList;
        $this->mainProductPhotoHeight = $mainProductPhotoHeight;
        $this->mainProductPhotoWidth = $mainProductPhotoWidth;
        $this->photoDirPrefix = $photoDirPrefix;
    }

    public function checkImgType(UploadedFile $photoFile)
    {
        $clientMimeType = $photoFile->getClientMimeType();
        $clientFileExt = $photoFile->getClientOriginalExtension();

        $mimeTypes = [];
        $fileExts = [];

        foreach ($this->supportedImgTypeList as $imgType) {
            $mimeTypes[] = $imgType[1];
            $fileExts[] = $imgType[0];
        }

        if (in_array($clientMimeType, $mimeTypes) == false) {
            throw new \InvalidArgumentException("Mime type is blocked!");
        }

        if (in_array($clientFileExt, $fileExts) == false) {
            throw new \InvalidArgumentException("Extension is blocked");
        }

        return true;
    }

    public function photoFileSave($product_id, UploadedFile $photoFile)
    {
        try {
            $this->checkImgType($photoFile);
        } catch (\InvalidArgumentException $ex) {
            die("Image type error!");
        }

        $photoFileName = $product_id . "_" . time() . "." . $photoFile->getClientOriginalExtension();
        $photoFile->move($this->photoDirPrefix, $photoFileName);

        return $photoFileName;
    }

    public function setMainProductPhoto(Product $product, ProductPhoto $photo)
    {
        $photoFileName = $photo->getFileName();

        $fileNamePrefix = "main__";
        $photoFullName = $this->photoDirPrefix . $photoFileName;

        $oldMainPhoto = $product->getMainPhoto();

        if ($oldMainPhoto !== null) {
            $oldMainPhotoFull = $this->photoDirPrefix . $oldMainPhoto;

            if (file_exists($oldMainPhotoFull)) {
                unlink($oldMainPhotoFull);
            }
        }

        $newMainPhoto = new ImageResize($photoFullName);
        //$newMainPhoto->resizeToBestFit($this->mainProductPhotoWidth, $this->mainProductPhotoHeight);
        $newMainPhoto->resizeToHeight($this->mainProductPhotoHeight);

        $newMainPhotoFullName = $this->photoDirPrefix . $fileNamePrefix . $photoFileName;
        $newMainPhoto->save($newMainPhotoFullName);

        return $newMainPhotoName = $fileNamePrefix . $photoFileName;

    }
}