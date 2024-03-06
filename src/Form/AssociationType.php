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
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File as FileConstraint; 
use Symfony\Component\Validator\Constraints\Image as ImageConstraint; 

class AssociationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $demande = $options['data'] ; // Fetching Demande object if passed

        $builder
        
        ->add('nom', TextType::class, [
            'label' => 'Nom:',
            'constraints' => [
                new Assert\NotBlank(['message' => 'The association name cannot be blank.']),
                new Assert\Regex([
                    'pattern' => '/^[a-zA-Z]+$/',
                    'message' => 'The association name should contain only letters.',
                ]),
            ],
        ])
            ->add('description', TextareaType::class, [
                'label' => 'Description:',
                'required' => false,
                'constraints' => [
                new Assert\NotBlank(['message' => 'The description cannot be blank.']),
            ],
            ])
            ->add('domaineActivite', TextType::class, [
                'label' => 'Domaine:',
                'constraints' => [
                new Assert\NotBlank(['message' => 'The domaine cannot be blank.']),
            ]
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
                        new Assert\NotBlank(['message' => 'The telephone number cannot be blank.']),
                        new Assert\Regex([
                            'pattern' => '/^\d+$/',
                            'message' => 'The telephone number should contain only digits.',
                        ]),
                        new Assert\Length([
                            'min' => 8,
                            'max' => 8,
                            'exactMessage' => 'The telephone number must be exactly 8 digits long.',
                        ]),
                    ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email:',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email([
                        'message' => "L'email '{{ value }}' n'est pas une adresse email valide.",
                        'mode' => 'strict',
                    ]),
                ],
            ])
            ->add('status')
            ->add('ActiveCompte')
            ->add('dateDemande', DateTimeType::class, [
                'data' => $demande ? $demande->getDateDemande() : new \DateTime(),
                'required' => false,
                'mapped' => false,
            ])
            ->add('document', FileType::class, [
                    'label' => 'Document:',
                    'required' => true,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new FileConstraint([
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                'application/pdf',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid PDF document',
                        ]),
                    ],
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe:',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'The password cannot be blank.',
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'The password must be at least {{ limit }} characters long.',
               ]),
                    new Assert\Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])/',
                        'message' => 'The password must contain at least one uppercase and one lowercase letter.',
                    ]),
                ],
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
