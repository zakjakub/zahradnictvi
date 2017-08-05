<?php
// src/AppBundle/Controller/CustomerController.php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\CustomerType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CustomerController extends Controller
{

    /**
     * @Route("/customer/", name="customer")
     */
    public function showAllAction () {
        $customers = $this->getDoctrine()
            ->getRepository('AppBundle:Customer')
            ->findAll();

        return $this->render('customer/list.html.twig', array(
            'customers' => $customers,
        ));

        // ... do something, like pass the $product object into a template
    }

    /**
     * @Route("/customerservice/", name="customerservice")
     */
    public function getAllAction () {
        $em = $this->container->get('doctrine')->getManager();
        $categorias = $em->getRepository('AppBundle:Customer')->findAll();
        return $categorias;
    }

    /**
     * Matches /customer/{customerId}
     *
     * @Route("/customer/{customerId}", name="one_customer", requirements={"customerId": "\d+"})
     * @param $customerId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showOneAction ($customerId) {
        $customer = $this->getDoctrine()
            ->getRepository('AppBundle:Customer')
            ->find($customerId);

        if (!$customer) {
            return $this->redirectToRoute('customer');
        }

        return $this->render('customer/one.html.twig', array(
            'customer' => $customer,
        ));

        // ... do something, like pass the $product object into a template
    }


    /**
     * Matches /customer/new/
     *
     * @Route("/customer/new/", name="new_customer")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction (Request $request) {

        $customer = new Customer();

        $form = $this->createForm(CustomerType::class, $customer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $customer = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();

            return $this->redirectToRoute('customer');
        }

        return $this->render(
            'customer/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function addAction ($name) {
        $customer = new Customer();
        $customer->setName($name);
        $em = $this->getDoctrine()->getManager();
        $em->persist($customer);
        $em->flush();
    }

    /**
     * Matches /customer/{customerId}/edit
     *
     * @Route("/customer/{customerId}/edit", name="edit_customer", requirements={"customerId": "\d+"})
     * @param Request $request
     * @param         $customerId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction (Request $request, $customerId) {

        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('AppBundle:Customer')
            ->find($customerId);

        if (!$customer) {
            return $this->redirectToRoute('customer');
        }

        $form = $this->createForm(CustomerType::class, $customer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $customer = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            //$em = $this->getDoctrine()->getManager();
            //$em->persist($customer);
            $em->flush();

            return $this->redirectToRoute('customer');
        }

        return $this->render('customer/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Matches /customer/{customerId}/delete
     *
     * @Route("/customer/{customerId}/delete", name="delete_customer", requirements={"customerId": "\d+"})
     * @param Request $request
     * @param         $customerId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction (Request $request, $customerId) {

        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('AppBundle:Customer')
            ->find($customerId);

        if (!$customer) {
            return $this->redirectToRoute('customer');
        }


        if ($customer->getOrders()->count()==0) {
            $em->remove($customer);
        }

        $em->flush();


        return $this->redirectToRoute('customer');
    }


}
