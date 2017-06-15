<?php

namespace SoftUniBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use SoftUniBundle\Entity\Product;
use SoftUniBundle\Entity\ProductCategory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
        return $this->categoryListAction();
    }

    /**
     * @Route("/category/list", name="category_list")
     * @param null $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryListAction($slug = null)
    {
        $manager = $this->get('softuni.product_category_manager');
        $parentId = $manager->getParentIdBySlug($slug);
        $productCategories = $manager->findCategoryBy(['parentId' => $parentId], ['rank' => 'DESC', 'title' => 'ASC']);

        return $this->render('SoftUniBundle:default:index.html.twig', [
            'productCategories' => $productCategories
        ]);
    }

    /**
     * @Route("/category/{slug}/subcategory/list", name="subcategory_list")
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function subcategoryListAction($slug)
    {
        return $this->categoryListAction($slug);
    }

    /**
     * @Route("/category/{slug}/product/list", name="category_product_list")
     * @ParamConverter("productCategory", options={"mapping": {"slug": "slug"}})
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
     * @Route("/product/{slug}/view", name="product_view")
     * @ParamConverter("product", options={"mapping": {"slug": "slug"}})
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function productViewAction(Product $product)
    {
        return $this->render('SoftUniBundle:default:product_show.html.twig', [
            'product' => $product
        ]);
    }
}
