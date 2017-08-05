<?php

namespace AppBundle\Controller;

use AppBundle\Entity\OrderProduct;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Orderproduct controller.
 *
 * @Route("orderproductd")
 */
class OrderProductController extends Controller
{
    /**
     * Lists all orderProduct entities.
     *
     * @Route("/", name="orderproductd_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $orderProducts = $em->getRepository('AppBundle:OrderProduct')->findAll();
        return $this->render('orderproduct/index.html.twig', array('orderProducts' => $orderProducts,));
    }

    /**
     * Creates a new orderProduct entity.
     *
     * @Route("/new", name="orderproductd_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $orderProduct = new Orderproduct();
        $form = $this->createForm('AppBundle\Form\OrderProductType', $orderProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($orderProduct);
            $em->flush($orderProduct);

            return $this->redirectToRoute('orderproductd_show', array('id' => $orderProduct->getId()));
        }

        return $this->render('orderproduct/new.html.twig', array(
            'orderProduct' => $orderProduct,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a orderProduct entity.
     *
     * @Route("/{id}", name="orderproductd_show")
     * @Method("GET")
     */
    public function showAction(OrderProduct $orderProduct)
    {
        $deleteForm = $this->createDeleteForm($orderProduct);

        return $this->render('orderproduct/show.html.twig', array(
            'orderProduct' => $orderProduct,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing orderProduct entity.
     *
     * @Route("/{id}/edit", name="orderproductd_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, OrderProduct $orderProduct)
    {
        $deleteForm = $this->createDeleteForm($orderProduct);
        $editForm = $this->createForm('AppBundle\Form\OrderProductType', $orderProduct);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('orderproductd_edit', array('id' => $orderProduct->getId()));
        }

        return $this->render('orderproduct/edit.html.twig', array(
            'orderProduct' => $orderProduct,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a orderProduct entity.
     *
     * @Route("/{id}", name="orderproductd_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, OrderProduct $orderProduct)
    {
        $form = $this->createDeleteForm($orderProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($orderProduct);
            $em->flush($orderProduct);
        }

        return $this->redirectToRoute('orderproductd_index');
    }

    /**
     * Creates a form to delete a orderProduct entity.
     *
     * @param OrderProduct $orderProduct The orderProduct entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(OrderProduct $orderProduct)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('orderproductd_delete', array('id' => $orderProduct->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
