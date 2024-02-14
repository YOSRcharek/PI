<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Expression;




class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomEvent')
            ->add('description')
            ->add('dateDebut')
            ->add('dateFin')
            ->add('localisation')
            ->add('capaciteMax', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => "La capacité maximale ne doit pas être vide."]),
                    
                ],
            ])
            ->add('capaciteActuelle', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La capacité actuelle ne doit pas être vide.']),
                    
                ],
            ]);
            //->add('type')
            //->add('volontaires')
            //->add('Association')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'constraints' => [
               
            ],
            
        ]);
    }
    
}
