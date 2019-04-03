<?php

namespace App\Form;

use App\Entity\Agent;
use App\Entity\Company;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddAgentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('active')
            ->add(
                'company',
                EntityType::class,
                [
                    'class' => Company::class,
                    'choice_label' => 'name',
                ]
            )
            ->add('save', SubmitType::class, ['label' => 'Add Agent']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Agent::class,
            ]
        );
    }
}
