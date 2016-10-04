<?php

namespace Somtel\WoraPayBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateCardTokenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        return $builder
            ->add('id', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('last4', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('status', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'allow_extra_fields' => true
        ]);
    }

    public function getName()
    {
        return 'updateCardToken';
    }
}