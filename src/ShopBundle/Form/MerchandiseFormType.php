<?php

namespace ShopBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MerchandiseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", TextType::class,['required'=>'true'])
            ->add("price", NumberType::class,['required'=>'true','invalid_message'=>'Price must be a number'])
            ->add('promoPrice',NumberType::class,['required'=>'true','invalid_message'=>'Promotion Price must be a number'])
            ->add('image',FileType::class)
            ->add("submit", SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getName()
    {
        return 'shop_bundle_merchandise_form_type';
    }
}
