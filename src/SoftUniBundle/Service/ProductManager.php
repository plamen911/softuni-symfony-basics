<?php

namespace SoftUniBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;
use SoftUniBundle\Entity\Product;
use SoftUniBundle\Repository\ProductRepository;

/**
 * Class ProductManager
 * @package SoftUniBundle\Service
 * @author Plamen Markov <plamen@lynxlake.org>
 */
class ProductManager
{
    private $em;
    private $container;
    /**
     * @var ProductRepository $productRepo
     */
    private $productRepo;

    /**
     * ProductManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param ContainerInterface $container
     * @param EntityRepository $productRepo
     */
    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container, EntityRepository $productRepo)
    {
        $this->em = $entityManager;
        $this->container = $container;
        $this->productRepo = $productRepo;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->productRepo->getClassName();
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    public function createProduct(Product $product)
    {
        $product->setSlug($this->container->get('slugger')->slugify($product->getTitle()));
        $product->setUpdatedAt(new \DateTime());
        $product->upload();

        $this->em->persist($product);
        $this->em->flush();
    }

    public function editProduct(Product $product)
    {
        $this->createProduct($product);
    }

    /**
     * @param Product $product
     */
    public function removeProduct(Product $product)
    {
        $this->em->remove($product);
        $this->em->flush();
    }

    public function findProductBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->productRepo->findBy($criteria, $orderBy, $limit, $offset);
    }
}
