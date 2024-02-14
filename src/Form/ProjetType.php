<?php

namespace App\Form;

use App\Entity\Projet;
use App\Entity\Association;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomProjet')
            ->add('status')
            ->add('dateDebut')
            ->add('dateFin')
            ->add('description')
            ->add('association', EntityType::class, [
                            'class' => Association::class,
                            'choice_label' => 'id', // Remplacez 'id' par la propriété que vous souhaitez afficher
                            // Autres options de formulaire
]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}
