<?php

namespace App\Form;

use App\Entity\Association;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File as FileConstraint; 

class AssoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
        ->add('nom', TextType::class, [
                'label' => 'Nom:',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z]+$/',
                        'message' => "Le nom de l'association doit contenir uniquement des lettres.",
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description:',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('domaineActivite', TextType::class, [
                'label' => 'Domaine:',
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse:',
                'required' => false,

                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Telephone:',
                'required' => false,
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
                ]
            ])
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Association::class,
        ]);
    }
}
