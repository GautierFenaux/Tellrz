<?php

namespace App\Form;


use App\Data\SearchData;
use App\Entity\Reporting;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class SearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
        ->add('authors', TextType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
            'placeholder' => 'Trouver un auteur '
            ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

}