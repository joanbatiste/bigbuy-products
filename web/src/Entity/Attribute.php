<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
Class Attribute
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
    * @ORM\ManyToOne(targetEntity="Product", inversedBy="productAttributes")
    */
    private $product;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $attributeId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $attributeName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $attributeValue;

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getAttributeId(): ?string
    {
        return $this->attributeId;
    }

    public function setAttributeId(?string $attributeId): self
    {
        $this->attributeId = $attributeId;

        return $this;
    }

    public function getAttributeName(): ?string
    {
        return $this->attributeName;
    }

    public function setAttributeName(?string $attributeName): self
    {
        $this->attributeName = $attributeName;

        return $this;
    }

    public function getAttributeValue(): ?string
    {
        return $this->attributeValue;
    }

    public function setAttributeValue(?string $attributeValue): self
    {
        $this->attributeValue = $attributeValue;

        return $this;
    }
    
}