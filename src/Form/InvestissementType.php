<?php

namespace App\Form;

use App\Entity\Investissement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class InvestissementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('type', null, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => "Can't be blank"
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Input should be > at {{ limit }} plz',
                        'max' => 50,
                    ]),
                ],
            ])
            ->add('risk', ChoiceType::class, [
                'choices' => [
                    'Safe' => 'Safe',
                    'Balanced' => 'Balanced',
                    'Risky' => 'Risky'
                ],
                'multiple' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Investissement::class,
        ]);
    }
}
