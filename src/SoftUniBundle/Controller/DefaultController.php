<?php

namespace SoftUniBundle\Controller;

use SoftUniBundle\Entity\Product;
use SoftUniBundle\Entity\ProductCategory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 * @package SoftUniBundle\Controller
 * @author Plamen Markov <plamen@lynxlake.org>
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('SoftUniBundle:Default:index.html.twig');
    }

    /**
     * @Route("/category/list", name="category_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $productCategories = $em->getRepository('SoftUniBundle:ProductCategory')->findBy(['parentId' => null], ['rank' => 'DESC', 'title' => 'ASC']);

        return $this->render('SoftUniBundle:default:index.html.twig', [
            'productCategories' => $productCategories
        ]);
    }

    /**
     * @Route("/category/{id}/product/list", name="category_product_list", requirements={"id": "\d+"})
     * @param ProductCategory $productCategory
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function productListAction(ProductCategory $productCategory)
    {
        return $this->render('SoftUniBundle:default:products.html.twig', [
            'productCategory' => $productCategory
        ]);
    }

    /**
     * @Route("/product/{id}/view", name="product_view", requirements={"id": "\d+"})
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function productViewAction(Product $product)
    {
        return $this->render('SoftUniBundle:default:product-show.html.twig', [
            'product' => $product
        ]);
    }
}
