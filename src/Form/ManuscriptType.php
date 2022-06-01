<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Question;
use App\Entity\Manuscript;

use Symfony\Component\Form\AbstractType;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class ManuscriptType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // $builder
        //     ->add('title')
        //     ->add('content')
        //     ->add('description')
        //     ->add('questions', EntityType::class, [
        //         'class' => Question::class,
        //         'multiple' => true,
        //         'expanded' => true
        //     ])
        //     ->remove('users')
        // ;

        $builder
        ->add('title', TextType::class, [
            'label' => 'Titre',
            'attr' => [
                'placeholder' => 'Ajouter un titre',
                'class' => 'shadow-none'
            ],         
        ])

        ->add('description', TextType::class, [
            'attr' => [
            'rows' => '225',
            'class' => 'shadow-none',
            'placeholder' => 'Ajouter une description',
            ]
        ])

        ->add('genres', EntityType::class, [
            'class' => Genre::class,
            'label' => 'Genre',
            'multiple' => true,
            'expanded' => true,
            // 'by_reference' => false,
            'required' => false,
            'attr' => [
                'class' => 'd-lg-flex justify-content-between mt-2 shadow-none'
            ]
            ])

        // Pourrait me servir pour que l'utilisateur écrvient ses propres questions 
        // ->add('genres', CollectionType::class, [
        //     'entry_type' => Genre::class,
        //     'entry_options' => ['label' => false],
        //     'label' => 'Genres',
        //     // 'multiple' => true,
        //     // 'expanded' => true,
        // ])

        ->add('explicitContent', CheckboxType::class, [
            'label'    => 'Contenu explicite',
            'required' => false,

        ])

        ->add('readership', ChoiceType::class, [
            'label' => 'Public visé',
            'choices'  => [
                'Enfant : 8-13 ans' => 'Enfant : 8-13 ans',
                'Adolescent : 14-18 ans' => 'Adolescent : 14-17 ans',
                'Adultes : 18 ans et plus ' => 'Adulte : 18 ans et plus',
            ],
            'attr' => [
                'class' => 'shadow-none'
            ]
        ])


        ->add('imageFile', FileType::class, [
            'label' => false,
            'attr' => [
            'placeholder' => 'Ajouter une couverture',
            'class' => 'shadow-none'
            ],
            'data_class' => null,
            'required' => false
        ])

        ->remove('questions', EntityType::class, [
            'class' => Question::class,
            'choice_label' => 'content',
            'multiple' => true,
            'expanded' => true,
            'by_reference' => false,
            'required' => false
        ])
        ->remove('users')
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Manuscript::class,
        ]);
    }
}
