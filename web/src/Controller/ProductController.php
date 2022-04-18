<?php

namespace App\Controller;

use App\Service\ReaderXmlService;
use App\Service\ReaderJsonService;
use App\Service\ReaderXlsxService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection;


class ProductController extends AbstractController
{
    private ReaderXmlService $readerXmlService;
    private ReaderJsonService $readerJsonService;
    private ReaderXlsxService $readerXlsxService;

    public function __construct
    (
        ReaderXmlService $readerXmlService,
        ReaderJsonService $readerJsonService,
        ReaderXlsxService $readerXlsxService
    )
    {
        $this->readerXmlService = $readerXmlService;
        $this->readerJsonService = $readerJsonService;
        $this->readerXlsxService = $readerXlsxService;
    }

    /**
     * @Route("/product/{fileType}", name="app_product")
     */
    public function index($fileType): Response
    {
        if($fileType !== 'xml' && $fileType !== 'xlsx' && $fileType !== 'json' ){
            $accessError = 'Lo sentimos, tu petición no se ha podido procesar';
            return $this->render('product/index.html.twig', [
                'access_error' => $accessError,
            ]);
        }

        switch($fileType){
            case 'xml':
                $this->readerXmlService->getProductsFromXml();
                $productController = 'Soy el archivo XML';
                break;
            case 'xlsx':
                $this->readerXlsxService->getProductsFromXlsx();
                $productController = 'Soy el archivo XLSX';
                break;
            case 'json':
                $this->readerJsonService->getProductsFromJson();
                $productController = 'Soy el archivo JSON';
                break;
            default:
                break;
        }

        return $this->render('product/index.html.twig', [
            'controller_name' => $productController,
        ]);
    }
}
