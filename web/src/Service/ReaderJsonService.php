<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Attribute;
use App\Util\FilesToImport;
use App\Repository\ProductRepository;
use App\Repository\AttributeRepository;
use Symfony\Component\HttpFoundation\Response;

class ReaderJsonService
{
    use FilesToImport;
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

    public function getProductsFromJson()
    {
        $jsonProducts = file_get_contents($this->jsonFile);
        $products = json_decode($jsonProducts, true);
        $productsArray = [];
        foreach($products as $key => $value){
            if($key === 'Data'){
                foreach($value as $product){
                    array_push($productsArray, $product);
                }                
            };
        };
        $productsInserted = 0;
        $productsUpdated = 0;
        foreach($productsArray as $row){
            $productExist = $this->productRepository->findOneBySku($row['Sku_Provider']);
            if($productExist){
                $this->updateProductFromJson($row, $productExist);
                ++$productsUpdated;
            }else{
                $this->addNewProductFromJson($row);
                ++$productsInserted;
            }
        };
        return ['productsInserted' => $productsInserted, 'productsUpdated' => $productsUpdated];
    }

    private function updateProductFromJson($row, $productExist)
    {
        $productExist->setEan13($row['Ean']);
        $productExist->setDescription($row['Provider_Full_Description']);
        $productExist->setName($row['Provider_Name']);
        $productExist->setBrandName($row['Brand_Supplier_Name']);
        $productExist->setCategoryName($row['Category_Supplier_Name']);
        $productExist->setWidthPackaging($row['Width_Packaging']);
        $productExist->setHeightPackaging($row['Height_Packaging']);
        $productExist->setLengthPackaging($row['Length_Packaging']);
        $productExist->setWeightPackaging($row['Weight_Packaging']);
        $productExist->setProductImages($row['Images']);
        $productAttributes = [];
        if(array_key_exists('Attributes', $row) && count($row['Attributes']) > 0){             
            foreach($row['Attributes'] as $att){
                $attExist = $this->attributeRepository->findOneByIdAndProductId($productExist->getId(), $att['Attribute_ID']);
                if($attExist){
                    $attExist->setAttributeName($att['Attribute_Name']);
                    $attExist->setAttributeValue($att['Attribute_Value']);
                    array_push($productAttributes, $attExist);
                }else{
                    $productAttribut = new Attribute();
                    $productAttribut->setProduct($productExist);
                    $productAttribut->setAttributeId($att['Attribute_ID']);
                    $productAttribut->setAttributeName($att['Attribute_Name']);
                    $productAttribut->setAttributeValue($att['Attribute_Value']);
                    array_push($productAttributes, $productAttribut);
                }                
            }
        }
        $this->productRepository->persist($productExist);
        $this->attributeRepository->addAttributes($productAttributes);
    }

    private function addNewProductFromJson($row)
    {
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
        $productAttributes = [];
        if(array_key_exists('Attributes', $row) && count($row['Attributes']) > 0){               
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
    }
}