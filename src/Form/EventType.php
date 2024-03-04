<?php

namespace App\Form;

use App\Entity\Event;
use App\Repository\TypeEventRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Expression;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;




use App\Entity\TypeEvent;




class EventType extends AbstractType

{
    private $typeEventRepository;
 
    private $propertyMappingFactory;
    private $storage;

public function __construct(TypeEventRepository $typeEventRepository)
{
    $this->typeEventRepository = $typeEventRepository;
  
}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            ->add('nomEvent')
            ->add('description')
            ->add('dateDebut')
            ->add('dateFin')
            ->add('localisation')
            ->add('latitude')
             ->add('longitude')
            ->add('capaciteMax')
            ->add('image', FileType::class, [
                'label' => 'Image (JPEG/PNG file)',
                'required' => false,
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Ajouter une image JPG/PNG valide',
                    ]),
                ],
            ])
                
           // ->add('capaciteActuelle')
            ->add('type', EntityType::class, [
                'class' => TypeEvent::class,
                'choice_label' => 'nom', // ou le champ que vous souhaitez afficher dans le dropdown
                'label' => 'Type d\'événement',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez sélectionner un type d\'événement.']),
                ],
            ]);

        //->add('volontaires')
        //->add('Association')
    }
    public function getTypes(): array
    {
        
        $types = $this->typeEventRepository->findAll(); // Assurez-vous que votre repository renvoie les types d'événements

        $choices = [];
        foreach ($types as $type) {
            // Vous pouvez ajuster la clé et la valeur en fonction de vos besoins
            $choices[$type->getNom()] = $type->getId();
        }

        return $choices;
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
