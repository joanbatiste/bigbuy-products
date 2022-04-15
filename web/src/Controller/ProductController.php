<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }



    /**
     * @Route("/product", name="app_product")
     */
    public function index(Request $request): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

     /**
     * @Route("/product/xml", name="xml_product")
     */
    public function productsXml(Request $request): Response
    {
        $jsonProducts = file_get_contents('../public/files/catalog.json');
        $products = json_decode($jsonProducts, true);
        $productsArray = [];
        foreach($products as $key => $value){
            if($key === 'Data'){
                foreach($value as $product){
                    array_push($productsArray, $product);
                }                
            };
        };
        $bbProducts = [];
        foreach($productsArray as $row){
            $bbProduct = new Product();
            $bbProduct->setSku($row['Sku_Provider']);
            $bbProduct->setEan13($row['Ean']);
            $bbProduct->setDescription($row['Provider_Full_Description']);
            $bbProduct->setName($row['Provider_Name']);
            $bbProduct->setBrandName($row['Brand_Supplier_Name']);
            $bbProduct->setCategoryName($row['Category_Supplier_Name']);
            $bbProduct->setWidthPackaging($row['Width_Packaging']);
            $bbProduct->setHeightPackaging($row['Height_Packaging']);
            $bbProduct->setLengthPackaging($row['Length_Packaging']);
            $bbProduct->setWeightPackaging($row['Weight_Packaging']);
            
            array_push($bbProducts, $bbProduct);
        };
        $this->productRepository->addProducts($bbProducts);
        return $this->render('product/index.html.twig', [
            'controller_name' => $productsArray,
        ]);
    }
}
