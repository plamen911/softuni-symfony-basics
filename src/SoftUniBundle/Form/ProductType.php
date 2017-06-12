<?php

namespace SoftUniBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, ['label' => 'Title', 'required' => true, 'attr' => ['placeholder' => 'Title...']])
            ->add('subtitle', TextType::class, ['label' => 'Subtitle', 'required' => true, 'attr' => ['placeholder' => 'Subtitle...']])
            ->add('description', TextareaType::class, ['label' => 'Description', 'required' => false, 'attr' => ['placeholder' => 'Description...']])
            ->add('file', FileType::class, ['label' => 'Upload Image', 'required' => false])
            ->add('price', NumberType::class, ['label' => 'Price', 'required' => false, 'attr' => ['placeholder' => 'Price...']])
            ->add('rank', NumberType::class, ['label' => 'Rank', 'required' => false, 'attr' => ['placeholder' => 'Rank...']])
            // ->add('categories')
            ->add('categories', EntityType::class, [
                'class' => 'SoftUniBundle\Entity\ProductCategory',
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SoftUniBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'softunibundle_product';
    }


}
