<?php

namespace SoftUniBundle\Controller;

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
     * @Route("/product/list", name="product_list")
     * @param Request $request
     */
    public function productListAction(Request $request)
    {

    }
}
