<?php
// src/AppBundle/Entity/Product.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 */
class Product
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ProductsCategory", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $price;


    /**
     * @ORM\OneToMany(targetEntity="OrderProduct", mappedBy="product")
     */
    private $orderProducts;

    public function __construct()
    {
        $this->orderProducts = new ArrayCollection();
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
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\ProductsCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\ProductsCategory $category
     *
     * @return Product
     */
    public function setCategory(ProductsCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }



    public function __toString()
    {
        return $this->name;
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
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }



    /**
     * Add orderProduct
     *
     * @param \AppBundle\Entity\OrderProduct $orderProduct
     *
     * @return Product
     */
    public function addOrderProduct(\AppBundle\Entity\OrderProduct $orderProduct)
    {
        $this->orderProducts[] = $orderProduct;

        return $this;
    }

    /**
     * Remove orderProduct
     *
     * @param \AppBundle\Entity\OrderProduct $orderProduct
     */
    public function removeOrderProduct(\AppBundle\Entity\OrderProduct $orderProduct)
    {
        $this->orderProducts->removeElement($orderProduct);
    }

    /**
     * Get orderProducts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderProducts()
    {
        return $this->orderProducts;
    }

    /**
     * Get orderProducts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoldAmount()
    {
        $sum = 0;
        foreach ($this->getOrderProducts() as $orderProduct) {
            $sum += $orderProduct->getAmount();
        }
        return $sum;
    }

    /**
     * Get orderProducts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoldPrice()
    {
        return $this->getSoldAmount() * $this->getPrice();
    }

}
