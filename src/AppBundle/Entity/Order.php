<?php
// src/AppBundle/Entity/Order.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\OrderProduct;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


/**
 * @ORM\Entity
 * @ORM\Table(name="order_")
 */
class Order
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="orders")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    private $customer;
    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $date;
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $payment;
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $processed;

    public function __toString()
    {
        return strval($this->getId());
    }

    /**
     * @ORM\OneToMany(targetEntity="OrderProduct", mappedBy="order", cascade={"remove"})
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Order
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set payment
     *
     * @param \DateTime $payment
     *
     * @return Order
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment
     *
     * @return \DateTime
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Set processed
     *
     * @param \DateTime $processed
     *
     * @return Order
     */
    public function setProcessed($processed)
    {
        $this->processed = $processed;

        return $this;
    }

    /**
     * Get processed
     *
     * @return \DateTime
     */
    public function getProcessed()
    {
        return $this->processed;
    }

    /**
     * Set customer
     *
     * @param \AppBundle\Entity\Customer $customer
     *
     * @return Order
     */
    public function setCustomer(Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \AppBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Add orderProduct
     *
     * @param \AppBundle\Entity\OrderProduct $orderProduct
     *
     * @return Order
     */
    public function addOrderProduct(OrderProduct $orderProduct)
    {
        $this->orderProducts[] = $orderProduct;

        return $this;
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


    public function getPrice()
    {
        $sum = 0;
        foreach ($this->orderProducts as $orderProduct) {
            $sum += $orderProduct->getPrice();
        }
        return $sum;
    }

    public function getAmount()
    {
        $sum = 0;
        foreach ($this->orderProducts as $orderProduct) {
            $sum += $orderProduct->getAmount();
        }
        return $sum;
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
}
