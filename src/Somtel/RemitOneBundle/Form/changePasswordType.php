<?php

namespace Somtel\RemitOneBundle\Form;

use ITG\MillBundle\Validator\Constraints\Email;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class changePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add('username', TextType::class, [
                'constraints' => [
                    new Email()
                ]
            ])
            ->add('new_password', PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('session_token', PasswordType::class, [

            ])
            ->add('forgot_password_token', PasswordType::class, [

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