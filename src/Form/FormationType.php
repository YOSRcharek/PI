<?php

namespace App\Form;

use App\Entity\Formation;
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
use Symfony\Component\Form\Extension\Core\Type\DateType;



class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'attr' => [
                'class' => 'form-control col-6',
                'placeholder' => 'Enter nom',
            ],
        ])
        
        ->add('lieu', TextType::class, [
            'attr' => [
                'class' => 'form-control col-6',
                'placeholder' => 'Enter lieu',
            ],
        ])
        ->add('dateFin', DateType::class, [
            'widget' => 'single_text',
            'attr' => [
                'class' => 'form-control col-6',
                'widget' => 'choice',
                'input'  => 'datetime_immutable'
            ],
        ])

        ->add('dateDebut', DateType::class, [
            'widget' => 'single_text',
            'attr' => [
                'class' => 'form-control col-6',
                'widget' => 'choice',
             'input'  => 'datetime_immutable'
            ],
        ])
        ->add('association')
        
        ->add('type')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
