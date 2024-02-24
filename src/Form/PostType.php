<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
            'attr' => [
                'class' => 'form-control col-6',
                'id' => 'title',
                'placeholder' => 'Enter title',
                'maxlength' => 10, // Ajoutez cette ligne pour définir la longueur maximale du champ
            ],
            'constraints' => [
                new Length([
                    'max' => 10, // Définissez également la longueur maximale ici pour le contrôle côté serveur
                    'maxMessage' => 'Le titre ne doit pas dépasser {{ limit }} caractères.', // Message d'erreur personnalisé
                ]),
            ],
        ])


            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control-resize',
                    'id' => 'title',
                    'placeholder' => 'Enter Content of your post',
                ],
               
            ])
            
            
            ->add('imageFile', FileType::class, [
                'label' => 'Image',
                'required' => false, // image is not required
            ])
            ->add('videoFile', FileType::class, [
                'label' => 'Video',
                'required' => false,
            ])


            ->add('quote', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control-resize col-6 mb-6',
                    'id' => 'quote',
                    'placeholder' => 'enter quote',
                    'required' => false,
                ],
                
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[^0-9]*$/',
                        'message' => 'The quote field cannot contain numbers.'
                    ])
                ]
            ])



                
         ->add('save', SubmitType::class, [
                    'label' => 'Save',
                    'attr' => [
                        'class' => 'btn btn-primary mt-3',
                    ],])
          
       
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
