<?php

namespace App\Form;

use App\Entity\Dons;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class DonsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant')
            // Si vous voulez afficher la date dans un format spécifique, vous pouvez l'ajouter ici
            
            ->add('type', EntityType::class, [
                'class' => 'App\Entity\TypeDons',
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un type',
                'required' => true,
            ])
            ->add('association', EntityType::class, [
                'class' => 'App\Entity\Association',
                'choice_label' => 'nom',
                'placeholder' => 'Choisir une association',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dons::class,
        ]);
    }
}
