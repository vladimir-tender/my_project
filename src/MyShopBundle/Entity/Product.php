<?php

namespace MyShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="MyShopBundle\Repository\ProductRepository")
 */
class Product
{
    const ACTIVE = 1;
    const INACTIVE = 0;
    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="id")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="MyShopBundle\Entity\Category", inversedBy="productList")
     * @ORM\JoinColumn(name="id_category", referencedColumnName="id")
     */
    private $category;


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true, name="productname")
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $productname;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true, name="price")
     */
    private $price;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true, name="adddate")
     */
    private $adddate;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true, name="offer")
     */
    private $offer;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true, name="discount")
     */
    private $discount;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true, name="description")
     */
    private $description;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="MyShopBundle\Entity\ProductPhoto", mappedBy="product")
     */
    private $photos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="mainPhoto")
     */
    private $mainPhoto;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true, name="status", options={"default" : 1})
     */
    private $status;

    public $actualPrice;


    public function __construct()
    {
        $date = new \DateTime("now");
        $this->setAdddate($date);

        $this->photos = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set category
     *
     * @param integer $category
     *
     * @return Product
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }


    /**
     * Set productname
     *
     * @param string $productname
     *
     * @return Product
     */
    public function setProductname($productname)
    {
        $this->productname = $productname;

        return $this;
    }

    /**
     * Get productname
     *
     * @return string
     */
    public function getProductname()
    {
        return $this->productname;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set adddate
     *
     * @param \DateTime $adddate
     *
     * @return Product
     */
    public function setAdddate($adddate)
    {
        $this->adddate = $adddate;

        return $this;
    }

    /**
     * Get adddate
     *
     * @return \DateTime
     */
    public function getAdddate()
    {
        return $this->adddate;
    }

    /**
     * Set offer
     *
     * @param integer $offer
     *
     * @return Product
     */
    public function setOffer($offer)
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * Get offer
     *
     * @return int
     */
    public function getOffer()
    {
        return $this->offer;
    }

    /**
     * Set discount
     *
     * @param float $discount
     *
     * @return Product
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * Add photo
     *
     * @param \MyShopBundle\Entity\ProductPhoto $photo
     *
     * @return Product
     */
    public function addPhoto(\MyShopBundle\Entity\ProductPhoto $photo)
    {
        $this->photos[] = $photo;

        return $this;
    }

    /**
     * Remove photo
     *
     * @param \MyShopBundle\Entity\ProductPhoto $photo
     */
    public function removePhoto(\MyShopBundle\Entity\ProductPhoto $photo)
    {
        $this->photos->removeElement($photo);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @return string
     */
    public function getMainPhoto()
    {
        return $this->mainPhoto;
    }

    /**
     * @param string $mainPhoto
     */
    public function setMainPhoto($mainPhoto)
    {
        $this->mainPhoto = $mainPhoto;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getActualPrice()
    {
        if ($this->getDiscount() > 0) {
            return $this->getPrice() * (100 - $this->getDiscount()) / 100;
        } else {
            return $this->getPrice();
        }
    }

}
