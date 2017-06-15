<?php

namespace SoftUniBundle\Controller;

use SoftUniBundle\Entity\ProductCategory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductCategoryController
 * @package SoftUniBundle\Controller
 * @author Plamen Markov <plamen@lynxlake.org>
 *
 * @Route("admin/product-category")
 */
class ProductCategoryController extends Controller
{
    /**
     * Lists all productCategory entities.
     *
     * @Route("/", name="admin_product-category_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $manager = $this->get('softuni.product_category_manager');
        $productCategories = $manager->findCategoryBy([], ['rank' => 'DESC', 'title' => 'ASC']);

        return $this->render('SoftUniBundle:productcategory:index.html.twig', [
            'productCategories' => $productCategories
        ]);
    }

    /**
     * Creates a new productCategory entity.
     *
     * @Route("/new", name="admin_product-category_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $productCategory = new Productcategory();
        $form = $this->createForm('SoftUniBundle\Form\ProductCategoryType', $productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->get('softuni.product_category_manager');
            $manager->createCategory($productCategory);

            $this->addFlash('success', 'A new product category was successfully created.');

            return $this->redirectToRoute('admin_product-category_show', array('id' => $productCategory->getId()));
        }

        return $this->render('SoftUniBundle:productcategory:new.html.twig', [
            'productCategory' => $productCategory,
            'form' => $form->createView()
        ]);
    }

    /**
     * Finds and displays a productCategory entity.
     *
     * @Route("/{id}", name="admin_product-category_show")
     * @Method("GET")
     * @param ProductCategory $productCategory
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(ProductCategory $productCategory)
    {
        $deleteForm = $this->createDeleteForm($productCategory);

        return $this->render('SoftUniBundle:productcategory:show.html.twig', array(
            'productCategory' => $productCategory,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing productCategory entity.
     *
     * @Route("/{id}/edit", name="admin_product-category_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param ProductCategory $productCategory
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, ProductCategory $productCategory)
    {
        $deleteForm = $this->createDeleteForm($productCategory);
        $editForm = $this->createForm('SoftUniBundle\Form\ProductCategoryType', $productCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $manager = $this->get('softuni.product_category_manager');
            $manager->editCategory($productCategory);

            $this->addFlash('success', 'Product category was successfully updated.');

            return $this->redirectToRoute('admin_product-category_edit', array('id' => $productCategory->getId()));
        }

        return $this->render('SoftUniBundle:productcategory:edit.html.twig', array(
            'productCategory' => $productCategory,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a productCategory entity.
     *
     * @Route("/{id}", name="admin_product-category_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param ProductCategory $productCategory
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, ProductCategory $productCategory)
    {
        $form = $this->createDeleteForm($productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->get('softuni.product_category_manager');
            $manager->removeCategory($productCategory);

            $this->addFlash('success', 'Product category was successfully deleted.');
        }

        return $this->redirectToRoute('admin_product-category_index');
    }

    /**
     * Creates a form to delete a productCategory entity.
     *
     * @param ProductCategory $productCategory The productCategory entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ProductCategory $productCategory)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_product-category_delete', array('id' => $productCategory->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
