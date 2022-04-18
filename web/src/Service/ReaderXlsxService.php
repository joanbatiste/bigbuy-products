<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Attribute;
use App\Util\ColumnsXlsx;
use App\Repository\ProductRepository;
use App\Repository\AttributeRepository;
use Symfony\Component\HttpFoundation\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ReaderXlsxService
{
    use ColumnsXlsx;

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

    public function getProductsFromXlsx()
    {
        $spreadsheet = IOFactory::load("../public/files/Productos.xlsx");
        $data = $spreadsheet->getActiveSheet()->toArray();
        $headers = array_shift($data);
        $productsInserted = 0;
        $productsUpdated = 0;

        foreach($data as $row){
            $productExist = $this->productRepository->findOneByProductIdentifier($row[$this->product_id]);
            if($productExist){
                $this->updateProductByXlsx($row, $productExist);
                ++$productsUpdated;
            }else{
                $this->addNewProductByXlsx($row);
                ++$productsInserted;
            }
        }
        return ['productsInserted' => $productsInserted, 'productsUpdated' => $productsUpdated];
    }

    private function updateProductByXlsx($row, $productExist)
    {
        $productExist->setSku($row[$this->sku]);
        $productExist->setEan13($row[$this->ean13]);
        $productExist->setDescription($row[$this->description]);
        $productExist->setName($row[$this->name]);
        $productExist->setEanVirtual($row[$this->eanVirtual]);
        $productExist->setBrandName($row[$this->brandName]);
        $productExist->setCategoryName($row[$this->categoryName]);
        $productExist->setCategoryName2($row[$this->categoryName2]);
        $productExist->setCategoryName3($row[$this->categoryName3]);
        $productExist->setWidth($row[$this->width]);
        $productExist->setHeight($row[$this->height]);
        $productExist->setLength($row[$this->length]);
        $productExist->setWeight($row[$this->weight]);
        $productExist->setWidthPackaging($row[$this->widthPackaging]);
        $productExist->setHeightPackaging($row[$this->heightPackaging]);
        $productExist->setLengthPackaging($row[$this->lengthPackaging]);
        $productExist->setWeightPackaging($row[$this->weightPackaging]);
        $productExist->setCbm($row[$this->cbm]);
        $attrArray = array_slice($row, 36, 171);
        $productExist->getProductAttributes();
        $productAttributes = [];
        for ($i = 0; $i < count($attrArray); $i +=2){
            if($attrArray[$i] && $attrArray[$i + 1]){
                $productAttribut = new Attribute();
                $productAttribut->setProduct($productExist);
                $productAttribut->setAttributeName($attrArray[$i]);
                $productAttribut->setAttributeValue($attrArray[$i + 1]);
                array_push($productAttributes, $productAttribut);
            }
        }
        $this->productRepository->persist($productExist);
        $this->attributeRepository->addAttributes($productAttributes);
    }

    private function addNewProductByXlsx($row)
    {
        $bbProduct = new Product();
        $bbProduct->setProductIdentifier($row[$this->product_id]);
        $bbProduct->setSku($row[$this->sku]);
        $bbProduct->setEan13($row[$this->ean13]);
        $bbProduct->setDescription($row[$this->description]);
        $bbProduct->setName($row[$this->name]);
        $bbProduct->setEanVirtual($row[$this->eanVirtual]);
        $bbProduct->setBrandName($row[$this->brandName]);
        $bbProduct->setCategoryName($row[$this->categoryName]);
        $bbProduct->setCategoryName2($row[$this->categoryName2]);
        $bbProduct->setCategoryName3($row[$this->categoryName3]);
        $bbProduct->setWidth($row[$this->width]);
        $bbProduct->setHeight($row[$this->height]);
        $bbProduct->setLength($row[$this->length]);
        $bbProduct->setWeight($row[$this->weight]);
        $bbProduct->setWidthPackaging($row[$this->widthPackaging]);
        $bbProduct->setHeightPackaging($row[$this->heightPackaging]);
        $bbProduct->setLengthPackaging($row[$this->lengthPackaging]);
        $bbProduct->setWeightPackaging($row[$this->weightPackaging]);
        $bbProduct->setCbm($row[$this->cbm]);
        $attrArray = array_slice($row, 36, 171);
        $productAttributes = [];
        for($i = 0; $i < count($attrArray); $i +=2){                
            if($attrArray[$i] && $attrArray[$i + 1]){
                $productAttribut = new Attribute();
                $productAttribut->setProduct($bbProduct);
                $productAttribut->setAttributeName($attrArray[$i]);
                $productAttribut->setAttributeValue($attrArray[$i + 1]);
                array_push($productAttributes, $productAttribut);
            }
        }
        $this->productRepository->add($bbProduct);
        $this->attributeRepository->addAttributes($productAttributes);
    }
}