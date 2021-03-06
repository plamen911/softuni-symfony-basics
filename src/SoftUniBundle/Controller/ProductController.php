<?php

namespace SoftUniBundle\Controller;

use SoftUniBundle\Entity\Product;
use SoftUniBundle\Service\ProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductController
 * @package SoftUniBundle\Controller
 * @author Plamen Markov <plamen@lynxlake.org>
 *
 * @Route("admin/product")
 */
class ProductController extends Controller
{
    /**
     * Lists all product entities.
     *
     * @Route("/", name="admin_product_index")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $manager = $this->get('softuni.product_manager');
        $products = $manager->findProductBy([], ['rank' => 'DESC', 'title' => 'ASC']);

        return $this->render('SoftUniBundle:product:index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="admin_product_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm('SoftUniBundle\Form\ProductType', $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->get('softuni.product_manager');
            $manager->createProduct($product);

            $this->addFlash('success', 'A new product was successfully created.');

            return $this->redirectToRoute('admin_product_show', array('id' => $product->getId()));
        }

        return $this->render('SoftUniBundle:product:new.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * Finds and displays a product entity.
     *
     * @Route("/{id}", name="admin_product_show")
     * @Method("GET")
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);

        return $this->render('SoftUniBundle:product:show.html.twig', [
            'product' => $product,
            'delete_form' => $deleteForm->createView()
        ]);
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="admin_product_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('SoftUniBundle\Form\ProductType', $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $manager = $this->get('softuni.product_manager');
            $manager->editProduct($product);

            $this->addFlash('success', 'Product was successfully updated.');

            return $this->redirectToRoute('admin_product_edit', array('id' => $product->getId()));
        }

        return $this->render('SoftUniBundle:product:edit.html.twig', [
            'product' => $product,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        ]);
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/{id}", name="admin_product_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Product $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->get('softuni.product_manager');
            $manager->removeProduct($product);

            $this->addFlash('success', 'Product was successfully deleted.');
        }

        return $this->redirectToRoute('admin_product_index');
    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_product_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
