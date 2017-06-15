<?php

namespace SoftUniBundle\Service;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;
use SoftUniBundle\Entity\Product;
use SoftUniBundle\Entity\ProductCategory;
use SoftUniBundle\Repository\ProductCategoryRepository;
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
     * @var ProductCategoryRepository $productCategoryRepo
     */
    private $productCategoryRepo;

    /**
     * ProductCategoryManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param ContainerInterface $container
     * @param EntityRepository $productCategoryRepo
     */
    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container, EntityRepository $productCategoryRepo)
    {
        $this->em = $entityManager;
        $this->container = $container;
        $this->productCategoryRepo = $productCategoryRepo;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->productCategoryRepo->getClassName();
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @param ProductCategory $productCategory
     */
    public function createCategory(ProductCategory $productCategory)
    {
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

    /**
     * @param ProductCategory $productCategory
     */
    public function editCategory(ProductCategory $productCategory)
    {
        $this->createCategory($productCategory);
    }

    /**
     * @param ProductCategory $productCategory
     */
    public function removeCategory(ProductCategory $productCategory)
    {
        $this->em->remove($productCategory);
        $this->em->flush();
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param null $limit
     * @param null $offset
     * @return array
     */
    public function findCategoryBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->productCategoryRepo->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param ProductCategory $productCategory
     * @param Product $product
     */
    public function addProduct(ProductCategory $productCategory, Product $product)
    {
        $productCategory->addProduct($product);
        $this->em->persist($productCategory);
        $this->em->flush();
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function getParentIdBySlug($slug)
    {
        if (empty($slug)) {
            return null;
        }

        return $this->productCategoryRepo->findOneBy(['slug' => $slug])->getId();
    }
}
