<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class ShowAllFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nbr', ChoiceType::class, [
                'choices' => [
                    '5 per page' => 5,
                    '10 per page' => 10,
                    '15 per page' => 15,
                    '20 per page' => 20,
                    'label' => 'To Be Completed Before',
                ],
                'attr' => [
                    'label' => '',
                    'onchange' => 'this.form.submit()',
                ],
            ]);
    }
}?>