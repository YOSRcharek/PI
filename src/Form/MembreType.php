<?php

namespace App\Form;

use App\Entity\Membre;
use App\Entity\Association;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints as Assert;

class MembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomMembre', TextType::class, [
                'label' => 'Nom:',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z]+$/',
                        'message' => "Le nom de l'association doit contenir uniquement des lettres.",
                    ]),
                ],
            ])
            ->add('prenomMembre', TextType::class, [
                'label' => 'Nom:',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z]+$/',
                        'message' => "Le nom de l'association doit contenir uniquement des lettres.",
                    ]),
                ],
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Telephone:',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^\d+$/',
                        'message' => "Le téléphone de l'association doit contenir uniquement des chiffres.",
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'max' => 8,
                        'exactMessage' => "Le téléphone de l'association doit avoir une longueur de 8 chiffres.",
                    ]),
                ],
            ])
            ->add('emailMembre', EmailType::class, [
                'label' => 'Email:',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email([
                        'message' => "L'email '{{ value }}' n'est pas une adresse email valide.",
                        'mode' => 'strict',
                    ]),
                ],
            ])
            ->add('fonction')
           ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}
