<?php

namespace Somtel\RemitOneBundle\Form;

use ITG\MillBundle\Validator\Constraints\Email;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class confirmRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add('email_address', TextType::class, [
                'constraints' => [
                    new Email()
                ]
            ])
            ->add('email_verification_code', PasswordType::class, [
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
    
}