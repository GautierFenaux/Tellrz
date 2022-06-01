<?php

namespace App\Form;

use App\Entity\Reporting;
use App\Entity\Manuscript;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ReportingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content',TextType::class, ['label' => 'Contenu'])
            ->add('details',TextType::class, [
                'label' => 'Détails',
                'attr' => ['rows' => '225']
            ]);
            // , TextType::class, ['label' => 'Fournissez un lien vers le passage signalé'])
            // ->add('manuscript')
            // ->add('chapter')
            // ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reporting::class,
        ]);
    }
}
