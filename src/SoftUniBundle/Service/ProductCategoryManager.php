<?php

namespace SoftUniBundle\Service;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use SoftUniBundle\Entity\Product;
use SoftUniBundle\Entity\ProductCategory;
use SoftUniBundle\SoftUniBundle;

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

        // IMPORTANT: Updating (from the inverse side) bidirectional many-to-many relationships
        // PROBLEM: Changes made only to the inverse side of an association are ignored.
        // http://docs.doctrine-project.org/en/latest/reference/unitofwork-associations.html
        // ... my solution - dirty, but works
        $conn = $this->container->get('doctrine.dbal.default_connection');
        $conn->delete('products_categories', array('category_id' => $productCategory->getId()));
        foreach ($productCategory->getProducts() as $product) {
            $product->addCategory($productCategory);
            $this->em->persist($product);
        }

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

    public function findCategoryBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->em->getRepository('SoftUniBundle:ProductCategory')->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param Product $product
     */
    public function addProduct(Product $product)
    {
        $this->productCategory->addProduct($product);
    }
}
