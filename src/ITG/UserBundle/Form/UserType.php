<?php

namespace ITG\UserBundle\Form;

use AppBundle\Entity\RoleSet;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ITG\MillBundle\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add('username', TextType::class, [
                'constraints' => [
                    new Assert\Length(['min' => 3]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank(['groups' => ['new']]),
                    new Assert\Length(['min' => 4]),
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\Email()
                ]
            ])
            ->add('roles', CollectionType::class, [
                'allow_delete' => true,
                'allow_add' => true
            ])
            ->add('roleSets', EntityType::class, [
                'class' => RoleSet::class,
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'data_class' => User::class,
            'allow_extra_fields' => true,
            'validation_groups' => ['Default','User'],
            'constraints' => [
                new Assert\UniqueEntity(['fields' => 'username'])
            ]
        ]);
    }

    public function getName()
    {
        return 'user';
    }
}