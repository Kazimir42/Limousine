<?php

namespace App\Form;

use App\Entity\Investissement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvestissementEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('type')
            ->add('risk', ChoiceType::class, [
                'choices' => [
                    'Safe' => 'Safe',
                    'Balanced' => 'Balanced',
                    'Risky' => 'Risky'
                ],
                'multiple' => false
            ])
            ->add('devise', ChoiceType::class, [
                'choices' => [
                    'EUR' => 'EUR',
                    'USD' => 'USD',
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
