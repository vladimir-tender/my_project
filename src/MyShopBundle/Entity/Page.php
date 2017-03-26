<?php

namespace MyShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Page
 *
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="MyShopBundle\Repository\PageRepository")
 */
class Page
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreateAt", type="datetime")
     */
    private $dateCreateAt;

    /**
     * @var string
     *
     * @ORM\Column(name="pageKey", type="string", length=255, unique=true)
     */
    private $pageKey;


    public function __construct()
    {
        $this->setDateCreateAt(new \DateTime("now"));
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
     * Set content
     *
     * @param string $content
     *
     * @return Page
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set dateCreateAt
     *
     * @param \DateTime $dateCreateAt
     *
     * @return Page
     */
    public function setDateCreateAt($dateCreateAt)
    {
        $this->dateCreateAt = $dateCreateAt;

        return $this;
    }

    /**
     * Get dateCreateAt
     *
     * @return \DateTime
     */
    public function getDateCreateAt()
    {
        return $this->dateCreateAt;
    }

    /**
     * Set pageKey
     *
     * @param string $pageKey
     *
     * @return Page
     */
    public function setPageKey($pageKey)
    {
        $this->pageKey = $pageKey;

        return $this;
    }

    /**
     * Get pageKey
     *
     * @return string
     */
    public function getPageKey()
    {
        return $this->pageKey;
    }
}

