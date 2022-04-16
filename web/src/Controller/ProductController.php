<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Attribute;
use App\Repository\ProductRepository;
use App\Repository\AttributeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private ProductRepository $productRepository;
    private AttributeRepository $attributeRepository;

    public function __construct
    (
        ProductRepository $productRepository,
        AttributeRepository $attributeRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->attributeRepository = $attributeRepository;
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
    * @Route("/product/json", name="json_product")
    */
    public function productsJson(): Response
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
            $bbProduct->setProductImages($row['Images']);
            if(array_key_exists('Attributes', $row) && count($row['Attributes']) > 0){                
                $productAttributes = [];
                foreach($row['Attributes'] as $att){
                    $productAttribut = new Attribute();
                    $productAttribut->setProduct($bbProduct);
                    $productAttribut->setAttributeId($att['Attribute_ID']);
                    $productAttribut->setAttributeName($att['Attribute_Name']);
                    $productAttribut->setAttributeValue($att['Attribute_Value']);
                    array_push($productAttributes, $productAttribut);
                }
            }
            $this->productRepository->add($bbProduct);
            $this->attributeRepository->addAttributes($productAttributes);
        };

        return $this->render('product/index.html.twig', [
            'controller_name' => $productsArray,
        ]);
    }

    /**
    * @Route("/product/xml", name="xml_product")
    */
    public function productsXml(): Response
    {
        
    }
}
