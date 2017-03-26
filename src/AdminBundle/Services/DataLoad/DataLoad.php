<?php

namespace AdminBundle\Services\DataLoad;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\PersistentCollection;
use Ivory\CKEditorBundle\Exception\Exception;

class DataLoad
{
    /** @var  EntityManager $manager */
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getJsonDataFromEntity()
    {
        $product = $this->getEntityManager()->getRepository("MyShopBundle:Product")->find("9");

        $reflect = new \ReflectionClass($product);
        $properties = $reflect->getProperties();

        //var_dump($properties);
        $result = [];


        foreach ($properties as $prop) {
            $propName = $prop->getName();
            $objMethod = "get" . $propName;

            if ($product->$objMethod() instanceof \DateTime) {
                $propValue = $product->$objMethod()->format("Y-m-d H:i:s");
            } elseif (is_scalar($product->$objMethod())) {
                $propValue = $product->$objMethod();
            } elseif ($product->$objMethod() instanceof PersistentCollection) {
                $propValue = null;
            } else {
                $propValue = $product->$objMethod()->getId();
            }

            if (!is_null($propValue)) {
                $result[$propName] = $propValue;
            }
        }

        return $result;
    }

    public function getJsonDataFromEntities()
    {
        $products = $this->getEntityManager()->getRepository("MyShopBundle:Product")->findAll();

        $result = [];
        foreach ($products as $product) {
            $reflect = new \ReflectionClass($product);
            $properties = $reflect->getProperties();

            //var_dump($properties);
            //var_dump($product->getMainPhoto());
            foreach ($properties as $prop) {
                $propName = $prop->getName();
                $objMethod = "get" . $propName;

                if ($product->$objMethod() instanceof \DateTime) {
                    $propValue = $product->$objMethod()->format("Y-m-d H:i:s");
                } elseif (is_scalar($product->$objMethod())) {
                    $propValue = $product->$objMethod();
                } elseif ($product->$objMethod() instanceof PersistentCollection) {
                    $propValue = null;
                } elseif ($product->$objMethod() === null) {
                    $propValue = "NULL";


                    //echo $product->getId()."<br>".$product->$objMethod()."___";
                    //$propValue = null;
                    //$propValue = $product->$objMethod()->getId();

                    //$objMethod = $product->$objMethod();

                    /*var_dump($objMethod);
                    if($propValue = $product->$objMethod()->getId() == null)
                    {
                        echo "sss";
                    }*/
                    //var_dump($product->getCategory());
                }

                if (!is_null($propValue)) {
                    $result[$product->getId()][$propName] = $propValue;
                }
            }
        }


        return [
            "products" => $result
        ];
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }
}


/*


#5
March 27th 2017, 12:03:02 am

VALID JSON (RFC 4627)
Formatted JSON Data
{
   "products":{
      "9":{  },
      "10":{  },
      "11":{  },
      "12":{  },
      "13":{  },
      "14":{  },
      "15":{  },
      "16":{  },
      "17":{  },
      "18":{  },
      "20":{
         "id":20,
         "category":20,
         "productname":"213",
         "price":1,
         "adddate":"2017-03-26 23:39:42",
         "offer":0,
         "discount":0,
         "description":"0",
         "mainPhoto":"NULL",
         "status":0
        }
   }
}

*/