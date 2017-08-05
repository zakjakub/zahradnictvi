<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductsCategory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\ProductsCategoryType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Form\ProductType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Product controller.
 *
 * @Route("product")
 */
class ProductController extends Controller
{
    /**
     * Show all products action
     *
     * @Route("/", name="product")
     */
    public function showAllAction () {
        $products = $this->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->findAll();

        return $this->render('product/list.html.twig', array(
            'products' => $products,
        ));
    }

    /**
     * Show one product action
     *
     * @Route("/{productId}", name="one_product", requirements={"productId": "\d+"})
     * @param $productId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showOneAction ($productId) {
        $product = $this->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->find($productId);

        if (!$product) {
            return $this->redirectToRoute('product');
        }

        return $this->render('product/one.html.twig', array(
            'product' => $product,
        ));
    }


    /**
     * New product action
     *
     * @Route("/new/", name="new_product")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction (Request $request) {

        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $product = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product');
        }

        return $this->render(
            'product/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * Edit one product action
     *
     * @Route("/{productId}/edit", name="edit_product", requirements={"productId": "\d+"})
     * @param Request $request
     * @param         $productId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction (Request $request, $productId) {

        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('AppBundle:Product')
            ->find($productId);


        if (!$product) {
            return $this->redirectToRoute('new_product');
        }

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $product = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            //$em = $this->getDoctrine()->getManager();
            //$em->persist($customer);
            $em->flush();

            return $this->redirectToRoute('product');
        }

        return $this->render('product/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Matches /product/{productId}/delete
     *
     * @Route("/product/{productId}/delete", name="delete_product", requirements={"productId": "\d+"})
     * @param Request $request
     * @param         $customerId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction (Request $request, $productId) {

        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('AppBundle:Product')
            ->find($productId);

        if (!$product) {
            return $this->redirectToRoute('product');
        }


        if ($product->getOrderProducts()->count()==0) {
            $em->remove($product);
        }

        $em->flush();


        return $this->redirectToRoute('product');
    }


}
