<?php

namespace App\Form;

use App\Entity\Report;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('reason', null, [
            'attr' => [
                'class' => 'form-control col-6',
            ],
        ])
        ->add('details', null, [
            'attr' => [
                'class' => 'form-control col-6',
            ],
            ])

        ->add('hidePost');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Report::class,
        ]);
    }
}
