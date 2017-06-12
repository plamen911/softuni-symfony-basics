<?php

namespace SoftUniBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use SoftUniBundle\Entity\Product;
use SoftUniBundle\Entity\ProductCategory;

/**
 * Class ProductCategoryManager
 * @package SoftUniBundle\Service
 * @author Plamen Markov <plamen@lynxlake.org>
 */
class ProductCategoryManager
{
    private $em;
    private $container;
    /**
     * @var ProductCategory $productCategory
     */
    private $productCategory;

    /**
     * ProductCategoryManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param ContainerInterface $container
     */
    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
    }

    /**
     * @param ProductCategory $productCategory
     */
    public function setCategory(ProductCategory $productCategory)
    {
        $this->productCategory = $productCategory;
    }

    /**
     * @return ProductCategory
     */
    public function getClass()
    {
        return $this->productCategory;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    public function createCategory()
    {
        $productCategory = $this->productCategory;

        $productCategory->setSlug($this->container->get('slugger')->slugify($productCategory->getTitle()));
        $productCategory->setUpdatedAt(new \DateTime());
        $productCategory->upload();

        $this->em->persist($productCategory);
        $this->em->flush();
    }

    public function editCategory()
    {
        $this->createCategory();
    }

    public function removeCategory()
    {
        $this->em->remove($this->productCategory);
        $this->em->flush();
    }

    public function findCategoryBy()
    {

    }

    /**
     * @param Product $product
     */
    public function addProduct(Product $product)
    {

    }
}