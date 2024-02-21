<?php

namespace App\Form;

use App\Entity\Projet;
use App\Entity\Association;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomProjet')    
            ->add('status', ChoiceType::class, [
                'label' => 'Status:',
                'choices' => [
                    'Terminé' => 'Terminé',
                    'En cours' => 'En cours',
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateDebut', DateType::class, [
                    'widget' => 'single_text',
                    'html5' => true,
                ])

            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,

            ])
            ->add('description')
            ;

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}
