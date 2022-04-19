<?php

namespace App\Service;

use App\Entity\Product;
Use \SimpleXMLElement;
use App\Util\FilesToImport;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;

class ReaderXmlService
{

    use FilesToImport;
    private ProductRepository $productRepository;

    public function __construct
    (
        ProductRepository $productRepository
    )
    {
        $this->productRepository = $productRepository;
    }

    public function getProductsFromXml()
    {
        $xmlProductsFile = file_get_contents($this->xmlFile);
        $products = new SimpleXMLElement($xmlProductsFile);
        $productsInserted = 0;
        $productsUpdated = 0;
        foreach ($products->Articulo as $product) {     
            $productExist = $this->productRepository->findOneBySku($product->Codigo);
            if($productExist){
                $this->updateProductFromXml($product, $productExist);
                ++$productsUpdated;
            }else{
                $this->addNewProductFromXml($product);
                ++$productsInserted;
            }    
        }
        return ['productsInserted' => $productsInserted, 'productsUpdated' => $productsUpdated];
    }

    private function updateProductFromXml(SimpleXMLElement $product, Product $productExist)
    {
        $productExist->setDescription($product->Descripcion);
        $productExist->setEan13($product->CodigoBarras);
        $productExist->setPvp(floatval($product->Precio));
        $productExist->setPriceWholesale(floatval($product->PrecioBase));
        $productExist->setPartNumber($product->Surtido);
        $productExist->setStock(intval($product->Cantidad));
        $productExist->setStockToShow(intval($product->StockReal));
        $productExist->setStockCatalog(intval($product->StockTeorico));
        $productExist->setStockAvailable(intval($product->StockDisponible));
        $productExist->setCategoryName($product->VMD);

        $this->productRepository->persist($productExist);
    }

    private function addNewProductFromXml(SimpleXMLElement $product)
    {
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
}