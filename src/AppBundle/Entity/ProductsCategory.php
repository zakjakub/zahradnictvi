<?php
// src/AppBundle/Entity/ProductsCategory.php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="products_category")
 */
class ProductsCategory
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return ProductsCategory
     */
    public function addProduct(Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \AppBundle\Entity\Product $product
     */
    public function removeProduct(Product $product)
    {
        $this->products->removeElement($product);
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ProductsCategory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }


    public static function build_sorter($key)
    {
        return function ($a, $b) use ($key) {
            return strnatcmp($a[$key], $b[$key]);
        };
    }

    public function getLessSold()
    {
        $prod = array();
        foreach ($this->getProducts() as $product) {
            $prod[] = array("counter" => $product->getSoldAmount(), "product" => $product);
        }

        usort($prod, ProductsCategory::build_sorter('counter'));

        $returnArray = array();
        foreach ($prod as $item) {
            $returnArray[] = $item["product"];
        }

        $ret = new ArrayCollection(array_slice(array_reverse($returnArray), 0, 3));

        return $ret;
    }

    public function getMostSold()
    {
        $prod = array();
        foreach ($this->getProducts() as $product) {
            $prod[] = array("counter" => $product->getSoldAmount(), "product" => $product);
        }

        usort($prod, ProductsCategory::build_sorter('counter'));

        $returnArray = array();
        foreach ($prod as $item) {
            $returnArray[] = $item["product"];
        }

        $ret = new ArrayCollection(array_slice($returnArray, 0, 3));

        return $ret;
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

}
