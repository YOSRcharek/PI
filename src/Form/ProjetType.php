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
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomProjet', TextType::class, [
                    'constraints' => [
                        new Assert\NotBlank(['message' => 'Le nom du projet ne doit pas être vide.']),
                        new Assert\Length(['min' => 5, 'minMessage' => 'Le nom du projet doit faire au moins {{ limit }} caractères.']),
                    ],
                ])  
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
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La date de début ne doit pas être vide.']),
                ],
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La date de fin ne doit pas être vide.']),
                   
                ],
            ])
            ->add('description', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La description du projet ne doit pas être vide.']),
                    new Assert\Length(['min' => 10, 'minMessage' => 'La description du projet doit faire au moins {{ limit }} caractères.']),
                ],
            ])
            ->add('association', EntityType::class, [
                'class' => Association::class,
                'choices' => $options['associations'],
                'choice_label' => 'nom',
                'label' => 'Association:',
                'placeholder' => 'Sélectionner une association',
                'required' => false,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
            'associations' => null,
        ]);
    }
}
