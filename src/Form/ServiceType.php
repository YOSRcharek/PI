<?php

namespace App\Form;

use App\Entity\Service;
use App\Entity\Categorie;
use App\Entity\Commentaire;
use App\Entity\Association;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomService')
            ->add('description')
            ->add('disponibilite')
           
            ->add('Categorie',EntityType::class , [
                'class' => 'App\Entity\Categorie',
                'choice_label' => 'nomCategorie'
            
            ])
            ->add('Commentaire',EntityType::class , [
                'class' => 'App\Entity\Commentaire',
                'choice_label' => 'message'
             
            ])
            // ->add('association',EntityType::class , [
            //     'class' => 'App\Entity\Association',
            //     'choice_label' => 'nom'
                
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
