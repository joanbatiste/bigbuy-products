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
use Symfony\Component\DependencyInjection;
Use \SimpleXMLElement;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        $xmlProductsFile = file_get_contents('../public/files/Articulos.xml');
        $products = new SimpleXMLElement($xmlProductsFile);
        foreach ($products->Articulo as $product) {
            
            $bbProduct = new Product();
            $bbProduct->setSku($product->Codigo);
            $bbProduct->setDescription($product->Descripcion);
            $bbProduct->setEan13($product->CodigoBarras);
            $bbProduct->setPvp(floatval($product->Precio));
            $bbProduct->setPriceWholesale(floatval($product->PrecioBase));
            $bbProduct->setPartNumber($product->Surtido);
            $bbProduct->setStock(intval($product->Cantidad));
            $bbProduct->setStockToShow(intval($product->StockReal));
            $bbProduct->setStockCatalog(intval($product->StockTeorico));
            $bbProduct->setStockAvailable(intval($product->StockDisponible));
            $bbProduct->setCategoryName($product->VMD);
            $this->productRepository->add($bbProduct);
        }

        return $this->render('product/index.html.twig', [
            'controller_name' => 'XML IMPORTADO',
        ]);
    }

    /**
    * @Route("/product/xlsx", name="xlsx_product")
    */
    public function productsXslx(): Response
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
        return $this->render('product/index.html.twig', [
            'controller_name' => 'XML IMPORTADO',
        ]);

    }
}
