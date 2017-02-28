<?php
namespace MyShopBundle\Controller\API\REST;

use MyShopBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Postman Chrome Extension
class ProductController extends Controller
{
    public function getProductAction(Request $request, $id)
    {
        /** @var Product $product */
        $product = $this->getDoctrine()->getRepository("MyShopBundle:Product")->find($id);
        if (is_null($product)) {
            $productArray = [];
        } else {
            $productArray = [
                'product' => $product->getProductname(),
                'price' => $product->getPrice(),
                'description' => $product->getDescription(),
                'date' => $product->getAdddate()->format('d.m.Y'),
                'main_photo' => $product->getMainPhoto() != null ?
                    $request->getHttpHost() ."/photos/". $product->getMainPhoto() :
                    ""
            ];
        }

        $response = new JsonResponse($productArray);
        return $response;
    }

    public function getProductsAction(Request $request, $category_id)
    {
        $products = $this->getDoctrine()->getRepository("MyShopBundle:Product")
            ->findBy(['category' => $category_id], ['category' => 'ASC']);

        if (count($products) > 0) {
            $productArray = [];
            /**@var Product $product */
            foreach ($products as $product) {
                $productArray[] = [
                    'product' => $product->getProductname(),
                    'price' => $product->getPrice(),
                    'description' => $product->getDescription(),
                    'date' => $product->getAdddate()->format('d.m.Y'),
                    'main_photo' => $request->getHttpHost() ."/photos/". $product->getMainPhoto()
                ];
            }
        } else {
            $productArray = [];
        }

        $response = new JsonResponse($productArray);
        //var_dump($productArray);
        return $response;
    }

    public function detailsXmlAction(Request $request, $id)
    {
        /** @var Product $product */
        $product = $this->getDoctrine()->getRepository("MyShopBundle:Product")->find($id);
        // плохой вариант
//        $res = '<product id='.$product->getId().'>';
//        $res .= '<model>' . $product->getModel() . '</model>';
//        $res .= '</product>';

        // нормальный вариант
        $xml = new \SimpleXMLElement("<product></product>");
        $xml->addAttribute("id", $product->getId());
        $xml->addChild("name", $product->getProductname());
        $xml->addChild("price", $product->getPrice());
        $xml->addChild("description", $product->getDescription());
        $xml->addChild("date", $product->getAdddate()->format("d.m.Y"));
        $xmlStr = $xml->asXML();
        $response = new Response($xmlStr);
        return $response;
    }
}