<?php

namespace App\Form;

use App\Entity\Manuscript;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Manuscript1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('explicitContent')
            ->add('readership')
            ->add('imageName')
            ->add('updatedAt')
            ->add('users')
            ->add('questions')
            ->add('author_id')
            ->add('genres')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Manuscript::class,
        ]);
    }
}
