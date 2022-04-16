<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sku;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ean13;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $eanVirtual;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $eans = [];

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stock;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stockCatalog;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stockToShow;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stockAvailable;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $categoryName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $brandName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $partNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $collection;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $priceCatalog;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $priceWholesale;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $priceRetail;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pvp;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $discount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $height;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $width;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $length;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $weightPackaging;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $heightPackaging;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $widthPackaging;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $lengthPackaging;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $weightMaster;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $heightMaster;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $widthMaster;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $lengthMaster;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $productLangSupplier = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $productImages = [];

    /**
    * @ORM\OneToMany(targetEntity="Attribute", mappedBy="product", cascade={"persist"})
    */
    private $productAttributes;

    public function __constructor()
    {
        $this->productAttributes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getEan13(): ?string
    {
        return $this->ean13;
    }

    public function setEan13(?string $ean13): self
    {
        $this->ean13 = $ean13;

        return $this;
    }

    public function getEanVirtual(): ?string
    {
        return $this->eanVirtual;
    }

    public function setEanVirtual(?string $eanVirtual): self
    {
        $this->eanVirtual = $eanVirtual;

        return $this;
    }

    public function getEans(): ?array
    {
        return $this->eans;
    }

    public function setEans(?array $eans): self
    {
        $this->eans = $eans;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getStockCatalog(): ?int
    {
        return $this->stockCatalog;
    }

    public function setStockCatalog(?int $stockCatalog): self
    {
        $this->stockCatalog = $stockCatalog;

        return $this;
    }

    public function getStockToShow(): ?int
    {
        return $this->stockToShow;
    }

    public function setStockToShow(?int $stockToShow): self
    {
        $this->stockToShow = $stockToShow;

        return $this;
    }

    public function getStockAvailable(): ?int
    {
        return $this->stockAvailable;
    }

    public function setStockAvailable(?int $stockAvailable): self
    {
        $this->stockAvailable = $stockAvailable;

        return $this;
    }

    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    public function setCategoryName(?string $categoryName): self
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    public function getBrandName(): ?string
    {
        return $this->brandName;
    }

    public function setBrandName(?string $brandName): self
    {
        $this->brandName = $brandName;

        return $this;
    }

    public function getPartNumber(): ?string
    {
        return $this->partNumber;
    }

    public function setPartNumber(?string $partNumber): self
    {
        $this->partNumber = $partNumber;

        return $this;
    }

    public function getCollection(): ?string
    {
        return $this->collection;
    }

    public function setCollection(?string $collection): self
    {
        $this->collection = $collection;

        return $this;
    }

    public function getPriceCatalog(): ?float
    {
        return $this->priceCatalog;
    }

    public function setPriceCatalog(?float $priceCatalog): self
    {
        $this->priceCatalog = $priceCatalog;

        return $this;
    }

    public function getPriceWholesale(): ?float
    {
        return $this->priceWholesale;
    }

    public function setPriceWholesale(?float $priceWholesale): self
    {
        $this->priceWholesale = $priceWholesale;

        return $this;
    }

    public function getPriceRetail(): ?float
    {
        return $this->priceRetail;
    }

    public function setPriceRetail(?float $priceRetail): self
    {
        $this->priceRetail = $priceRetail;

        return $this;
    }

    public function getPvp(): ?float
    {
        return $this->pvp;
    }

    public function setPvp(?float $pvp): self
    {
        $this->pvp = $pvp;

        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(?float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(?float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(?float $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getLength(): ?float
    {
        return $this->length;
    }

    public function setLength(?float $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getWeightPackaging(): ?float
    {
        return $this->weightPackaging;
    }

    public function setWeightPackaging(?float $weightPackaging): self
    {
        $this->weightPackaging = $weightPackaging;

        return $this;
    }

    public function getHeightPackaging(): ?float
    {
        return $this->heightPackaging;
    }

    public function setHeightPackaging(?float $heightPackaging): self
    {
        $this->heightPackaging = $heightPackaging;

        return $this;
    }

    public function getWidthPackaging(): ?float
    {
        return $this->widthPackaging;
    }

    public function setWidthPackaging(?float $widthPackaging): self
    {
        $this->widthPackaging = $widthPackaging;

        return $this;
    }

    public function getLengthPackaging(): ?float
    {
        return $this->lengthPackaging;
    }

    public function setLengthPackaging(?float $lengthPackaging): self
    {
        $this->lengthPackaging = $lengthPackaging;

        return $this;
    }

    public function getWeightMaster(): ?float
    {
        return $this->weightMaster;
    }

    public function setWeightMaster(?float $weightMaster): self
    {
        $this->weightMaster = $weightMaster;

        return $this;
    }

    public function getHeightMaster(): ?float
    {
        return $this->heightMaster;
    }

    public function setHeightMaster(?float $heightMaster): self
    {
        $this->heightMaster = $heightMaster;

        return $this;
    }

    public function getWidthMaster(): ?float
    {
        return $this->widthMaster;
    }

    public function setWidthMaster(?float $widthMaster): self
    {
        $this->widthMaster = $widthMaster;

        return $this;
    }

    public function getLengthMaster(): ?float
    {
        return $this->lengthMaster;
    }

    public function setLengthMaster(?float $lengthMaster): self
    {
        $this->lengthMaster = $lengthMaster;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getProductLangSupplier(): ?array
    {
        return $this->productLangSupplier;
    }

    public function setProductLangSupplier(?array $productLangSupplier): self
    {
        $this->productLangSupplier = $productLangSupplier;

        return $this;
    }

    public function getProductImages(): ?array
    {
        return $this->productImages;
    }

    public function setProductImages(?array $productImages): self
    {
        $this->productImages = $productImages;

        return $this;
    }

    public function getProductAttributes(): ?Collection
    {
        return $this->productAttributes;
    }

    public function setProductAttributes(?array $productAttributes): self
    {
        $this->productAttributes = $productAttributes;

        return $this;
    }
}
