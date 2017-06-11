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

class ProductCategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, ['label' => 'Title', 'required' => true, 'attr' => ['placeholder' => 'Title...']])
            ->add('description', TextareaType::class, ['label' => 'Description', 'required' => false, 'attr' => ['placeholder' => 'Description...']])
            //->add('image', FileType::class, ['label' => 'Upload Image', 'mapped' => false])
            ->add('file', FileType::class, ['label' => 'Upload Image', 'mapped' => false])
            ->add('rank', NumberType::class, ['label' => 'Rank', 'required' => false, 'attr' => ['placeholder' => 'Rank...']])
            ->add('parent', EntityType::class, [
                'class' => 'SoftUniBundle\Entity\ProductCategory',
                'choice_label' => 'title',
                'label' => 'Parent',
                'required' => true,
                'placeholder' => '- select -',
                'empty_data' => null
            ])
            ->add('products');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SoftUniBundle\Entity\ProductCategory'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'softunibundle_productcategory';
    }


}
