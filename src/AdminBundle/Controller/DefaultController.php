<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Default:index.html.twig');
    }

    public function getJsonDataAction()
    {
        $dataLoader = $this->get("admin.data_loader");
        $data = $dataLoader->getJsonDataFromEntities();
        //var_dump($data);
        //$data = "";
        $response = new JsonResponse($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}
