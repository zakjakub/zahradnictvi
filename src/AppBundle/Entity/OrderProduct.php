<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr\Composite;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


/**
 * @ORM\Entity
 * @ORM\Table(name="order_products")
 */
class OrderProduct
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer $id
     */
    protected $id;


    /**
     * @ORM\Column(type="integer")
     */
    private $amount;



    /**
     * @ORM\ManyToOne(targetEntity="Order",inversedBy="orderProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $order;

    /**
     * @ORM\ManyToOne(targetEntity="Product",inversedBy="orderProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;


    public function __toString()
    {
        return strval($this->getId());
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
     * Set amount
     *
     * @param integer $amount
     *
     * @return OrderProduct
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set order
     *
     * @param \AppBundle\Entity\Order $order
     *
     * @return OrderProduct
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \AppBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return OrderProduct
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \AppBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }


    /**
     * Get product name
     *
     */
    public function getName()
    {
        return $this->getProduct()->getName();
    }

    /**
     * Get amount of products
     *
     */
    public function getPrice()
    {
        return $this->getAmount() * $this->getProduct()->getPrice();
    }


}
