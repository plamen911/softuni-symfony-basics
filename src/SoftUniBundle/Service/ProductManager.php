<?php

namespace SoftUniBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use SoftUniBundle\Entity\Product;

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
     * @var Product $product
     */
    private $product;

    /**
     * ProductManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param ContainerInterface $container
     */
    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @return Product
     */
    public function getClass()
    {
        return $this->product;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    public function createProduct()
    {

    }

    public function removeProduct()
    {

    }

    public function findProductBy()
    {

    }
}