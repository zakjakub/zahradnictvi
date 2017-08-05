<?php
// src/AppBundle/Controller/ProductsCategoryController.php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\ProductsCategory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\ProductsCategoryType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProductsCategoryController extends Controller
{

    /**
     * Matches /category/
     *
     * @Route("/category/", name="category")
     */
    public function showAllAction () {
        $categories = $this->getDoctrine()
            ->getRepository('AppBundle:ProductsCategory')
            ->findAll();

        return $this->render('category/list.html.twig', array(
            'categories' => $categories,
        ));

    }

    /**
     * Matches /category/{categoryId}
     *
     * @Route("/category/{categoryId}", name="one_category", requirements={"categoryId": "\d+"})
     * @param $categoryId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showOneAction ($categoryId) {
        $category = $this->getDoctrine()
            ->getRepository('AppBundle:ProductsCategory')
            ->find($categoryId);


        return $this->render('category/one.html.twig', array(
            'category' => $category,
        ));

    }

    /**
     * Matches /category/{categoryId}/products/
     *
     * @Route("/category/{categoryId}/products/", name="category_products", requirements={"categoryId": "\d+"})
     * @param $categoryId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCategoryAction ($categoryId) {
        $category = $this->getDoctrine()
            ->getRepository('AppBundle:ProductsCategory')
            ->find($categoryId);

            $products = $category->getProducts();

        return $this->render('product/list.html.twig', array(
            'category' => $category,
            'products' => $products,
        ));
    }


    /**
     * Matches /category/new/
     *
     * @Route("/category/new/", name="new_category")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction (Request $request) {

        $category = new ProductsCategory();

        $form = $this->createForm(ProductsCategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category');
        }

        return $this->render('category/new.html.twig', array('form' => $form->createView()));
    }


    /**
     * Matches /category/{categoryId}/edit
     *
     * @Route("/category/{categoryId}/edit", name="edit_category", requirements={"ccategoryId": "\d+"})
     * @param Request $request
     * @param         $categoryId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction (Request $request, $categoryId) {

        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('AppBundle:ProductsCategory')
            ->find($categoryId);

        if (!$category) {
            return $this->redirectToRoute('new_category');
        }

        $form = $this->createForm(ProductsCategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category');
        }

        return $this->render('category/new.html.twig', array('form' => $form->createView()));
    }

    /**
     * Matches /category/{categoryId}/delete
     *
     * @Route("/category/{categoryId}/delete", name="delete_category", requirements={"categoryId": "\d+"})
     * @param Request $request
     * @param         $customerId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction (Request $request, $categoryId) {

        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('AppBundle:ProductsCategory')
            ->find($categoryId);

        if (!$category) {
            return $this->redirectToRoute('category');
        }


        if ($category->getProducts()->count()==0) {
            $em->remove($category);
        }

        $em->flush();

        return $this->redirectToRoute('category');
    }


}
