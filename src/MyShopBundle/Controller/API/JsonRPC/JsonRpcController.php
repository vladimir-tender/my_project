<?php
namespace MyShopBundle\Controller\API\JsonRPC;

use MyShopBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class JsonRpcController extends Controller
{
    /*
    [
    {"jsonrpc": "2.0","method": "getproducts","params": {"categoryId":"19"},"id": "1"},
    {"jsonrpc": "2.0","method": "getproduct","params": {"productId":"18"},"id": "1"}
    ]
    */
    public function indexAction(Request $request)
    {
        $requestJson = $request->getContent();

        $requestAr = json_decode($requestJson, true);
        if ($requestAr == null) {
            return new JsonResponse([
                'jsonrpc' => '2.0',
                'error' => [
                    'code' => -32700,
                    'message' => 'Wrong json format'
                ]
            ]);
        }
        if (isset($requestAr['method'])) {
            $method = $requestAr['method'];
            $responseParamsAr = $this->$method($requestAr['params'], $request->getHttpHost());
            $responseAr = [
                'jsonrpc' => '2.0',
                'result' => $responseParamsAr,
                'id' => $requestAr['id']
            ];
            return new JsonResponse($responseAr);
        } else {
            if (isset($requestAr[0]['method'])) {
                $result = [];
                foreach ($requestAr as $reqAr) {
                    $method = $reqAr['method'];
                    $responseParamsAr = $this->$method($reqAr['params'], $request->getHttpHost());
                    $responseAr = [
                        'jsonrpc' => '2.0',
                        'result' => $responseParamsAr,
                        'id' => $reqAr['id']
                    ];
                    $result[] = $responseAr;
                }
                return new JsonResponse($result);
            }
        }
    }

    public function getProducts($params, $HttpHost)
    {
        $categoryId = $params['categoryId'];
        $products = $this->getDoctrine()->getRepository('MyShopBundle:Product')
            ->findBy(['category' => $categoryId], ['category' => 'ASC']);

        if (count($products) > 0) {
            $productArray = [];
            /**@var Product $product */
            foreach ($products as $product) {
                $productArray[] = [
                    'product' => $product->getProductname(),
                    'price' => $product->getPrice(),
                    'description' => $product->getDescription(),
                    'date' => $product->getAdddate()->format('d.m.Y'),
                    'mainPhoto' => $HttpHost . "/photos/" . $product->getMainPhoto()
                ];
            }
        } else {
            $productArray = [];
        }

        return $productArray;
    }

    public function getProduct($params, $HttpHost)
    {
        $productId = $params['productId'];
        $product = $this->getDoctrine()->getRepository('MyShopBundle:Product')->find($productId);

        if (is_null($product)) {
            $productArray = [
                'product' => $product->getProductname(),
                'price' => $product->getPrice(),
                'description' => $product->getDescription(),
                'date' => $product->getAdddate()->format('d.m.Y'),
                'mainPhoto' => $HttpHost . "/photos/" . $product->getMainPhoto()
            ];
        } else {
            $productArray = [];
        }

        return $productArray;
    }
}