<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Form\OrderType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/order")
 */
class OrderController extends Controller
{
    /**
     * @Route("/", name="order")
     */
    public function showAllAction()
    {
        $orders = $this->getDoctrine()
            ->getRepository('AppBundle:Order')
            ->findAll();

        return $this->render(
            'order/list.html.twig',
            array(
                'orders' => $orders,
            )
        );

    }

    /**
     * @Route("/notprocessed/", name="order_notprocessed")
     */
    public function showNotProcessedAction()
    {
        $orders = $this->getDoctrine()
            ->getRepository('AppBundle:Order')
            ->findBy(
                array('processed' => NULL)
            );

        return $this->render(
            'order/list.html.twig',
            array(
                'orders' => $orders,
            )
        );

    }

    /**
     * @Route("/notpaid/", name="order_notpaid")
     */
    public function showNotPaidAction()
    {
        $orders = $this->getDoctrine()
            ->getRepository('AppBundle:Order')
            ->findBy(
                array('payment' => NULL)
            );

        return $this->render(
            'order/list.html.twig',
            array(
                'orders' => $orders,
            )
        );

    }

    /**
     * @Route("/obrat/month/", name="obrat_month")
     */
    public function showObratMonthAction(Request $request)
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Order');
        $qb = $repository->createQueryBuilder('c')
            ->where("c.date LIKE :month")
            ->setParameter('month', $request->get("month")."%")
        ;
        $orders = $qb->getQuery()->getResult();

        $sum = 0;

        foreach ($orders as $order) {
            $sum += $order->getPrice();
        }

        return $this->render(
            'order/obrat.html.twig',
            array(
                'obrat' => $sum,
            )
        );

    }

    /**
     * @Route("/obrat/range/", name="obrat_range")
     */
    public function showObratRangeAction(Request $request)
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Order');
        $qb = $repository->createQueryBuilder('c')
            ->where('c.date BETWEEN :firstDate AND :lastDate')
            ->setParameter('firstDate', $request->get("obratRangeStart"))
            ->setParameter('lastDate', $request->get("obratRangeEnd"))
        ;
        $orders = $qb->getQuery()->getResult();

        $sum = 0;

        foreach ($orders as $order) {
            $sum += $order->getPrice();
        }

        return $this->render(
            'order/obrat.html.twig',
            array(
                'obrat' => $sum,
            )
        );

    }

    /**
     * @Route("/range/", name="order_range")
     */
    public function showRangeAction(Request $request)
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Order');
        $qb = $repository->createQueryBuilder('c')
            ->where('c.date BETWEEN :firstDate AND :lastDate')
            ->setParameter('firstDate', $request->get("ordersRangeStart"))
            ->setParameter('lastDate', $request->get("ordersRangeEnd"))
        ;

        $orders = $qb->getQuery()->getResult();


        return $this->render(
            'order/list.html.twig',
            array(
                'orders' => $orders,
            )
        );

    }

    /**
     * @Route("/{orderId}", name="one_order", requirements={"orderId": "\d+"})
     * @param $orderId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showOneAction($orderId)
    {
        $order = $this->getDoctrine()
            ->getRepository('AppBundle:Order')
            ->find($orderId);

        if (!$order) {
            return $this->redirectToRoute('order');
        }

        return $this->render(
            'order/one.html.twig',
            array(
                'order' => $order,
            )
        );

    }

    /**
     * @Route("/new/", name="new_order")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->add('submit', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            $orderProducts = $order->getOrderProducts();
            $em = $this->getDoctrine()->getManager();
            $em->persist($order);

            foreach ($orderProducts as $oneOrderProduct) {
                $oneOrderProduct->setOrder($order);
                $em->persist($oneOrderProduct);
            }

            $em->flush();

            return $this->redirectToRoute('one_order', array('orderId' => $order->getId()));
        }

        return $this->render('order/new.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/{orderId}/edit", name="edit_order", requirements={"orderId": "\d+"})
     * @param Request $request
     * @param         $orderId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $orderId)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('AppBundle:Order')->find($orderId);

        if (!$order) {
            return $this->redirectToRoute('new_order');
        }

        $originalOrderProducts = new ArrayCollection();

        foreach ($order->getOrderProducts() as $orderProduct) {
            $originalOrderProducts->add($orderProduct);
        }

        $editForm = $this->createForm(OrderType::class, $order);
        $editForm->add('submit', SubmitType::class);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $orderProducts = $order->getOrderProducts();

            foreach ($originalOrderProducts as $oneOriginalOrderProduct) {
                if (false === $order->getOrderProducts()->contains($oneOriginalOrderProduct)) {
                    $order->removeOrderProduct($oneOriginalOrderProduct);
                    $em->remove($oneOriginalOrderProduct);
                    $em->flush();
                }
            }

            foreach ($orderProducts as $oneOrderProduct) {
                if ($oneOrderProduct->getAmount() == 0) {
                    $order->removeOrderProduct($oneOrderProduct);
                    $em->remove($oneOrderProduct);
                } else {
                    if (!$originalOrderProducts->contains($oneOrderProduct)) {
                        $oneOrderProduct->setOrder($order);
                    }
                    $em->persist($oneOrderProduct);
                }
                $em->persist($order);
                $em->flush();
            }

            return $this->redirectToRoute('order');
        }

        return $this->render('order/new.html.twig', array('form' => $editForm->createView()));

    }

    /**
     * @Route("/{orderId}/delete/", name="delete_order", requirements={"orderId": "\d+"})
     * @param Request $request
     * @param         $customerId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction (Request $request, $orderId) {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('AppBundle:Order')
            ->find($orderId);

        if (!$order) {
            return $this->redirectToRoute('order');
        }

        foreach ($order->getOrderProducts() as $orderProduct) {
            $order->removeOrderProduct($orderProduct);
            $em->remove($orderProduct);
        }

        $em->remove($order);
        $em->flush();

        return $this->redirectToRoute('order');
    }

}
