<?php

namespace AdminBundle\Services\Product;


use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;

class ProductUtils
{
    private $manager;
    private $pagination;

    public function __construct(EntityManager $entityManager, Paginator $pagination)
    {
        $this->setManager($entityManager);
        $this->setPagination($pagination);
    }

    public function getPaginationProductList($page, $countPerPage = 6)
    {
        $manager = $this->getManager();
        $dql = "SELECT p FROM MyShopBundle:Product p ORDER BY p.productname";
        $queryResult = $manager->createQuery($dql)->getResult();
        //var_dump($query);
        //die();

        $result = $this->getPagination()->paginate($queryResult, $page, $countPerPage);

        return $result;
    }

    /**
     * @return EntityManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param EntityManager $manager
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return mixed
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * @param mixed $pagination
     */
    public function setPagination($pagination)
    {
        $this->pagination = $pagination;
    }


}