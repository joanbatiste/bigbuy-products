<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Attribute;
use App\Repository\ProductRepository;
use App\Repository\AttributeRepository;
use Symfony\Component\HttpFoundation\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ReaderXlsxService
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

    public function getProductsFromXlsx(): void
    {
        $spreadsheet = IOFactory::load("../public/files/Productos.xlsx");
        $data = $spreadsheet->getActiveSheet()->toArray();
        $headers = array_shift($data);
        
        foreach($data as $row){
            $bbProduct = new Product();
            $bbProduct->setProductIdentifier($row[0]);
            $bbProduct->setSku($row[1]);
            $bbProduct->setEan13($row[2]);
            $bbProduct->setDescription($row[4]);
            $bbProduct->setName($row[6]);
            $bbProduct->setEanVirtual($row[7]);
            $bbProduct->setBrandName($row[8]);
            $bbProduct->setCategoryName($row[9]);
            $bbProduct->setCategoryName2($row[10]);
            $bbProduct->setCategoryName3($row[11]);
            $bbProduct->setWidth($row[12]);
            $bbProduct->setHeight($row[13]);
            $bbProduct->setLength($row[14]);
            $bbProduct->setWeight($row[18]);
            $bbProduct->setWidthPackaging($row[19]);
            $bbProduct->setHeightPackaging($row[20]);
            $bbProduct->setLengthPackaging($row[21]);
            $bbProduct->setWeightPackaging($row[22]);
            $bbProduct->setCbm($row[23]);
            $attrArray = array_slice($row, 36, 171);
            $productAttributes = [];
            for ($i = 0; $i < count($attrArray); $i +=2){                
                $productAttribut = new Attribute();
                $productAttribut->setProduct($bbProduct);
                $productAttribut->setAttributeName($attrArray[$i]);
                $productAttribut->setAttributeValue($attrArray[$i + 1]);
                array_push($productAttributes, $productAttribut);
            }
            $this->productRepository->add($bbProduct);
            $this->attributeRepository->addAttributes($productAttributes);
        }
    }
}