<?php

namespace App\Service;

use App\Entity\Product;
Use \SimpleXMLElement;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;

class ReaderXmlService
{
    private ProductRepository $productRepository;

    public function __construct
    (
        ProductRepository $productRepository
    )
    {
        $this->productRepository = $productRepository;
    }

    public function getProductsFromXml(): void
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
    }
}