<?php

namespace Somtel\RemitOneBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class createBeneficiaryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*
         *
         */
        return $builder
            ->add('username', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('session_token', PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('name', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('benef_fname', PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('benef_lname', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('benef_name', PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('address1', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('city', PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])->add('country_id', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('dob', PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('telephone', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('mobile', PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])->add('email', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('id_type', PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('id_details', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('card_number', PasswordType::class, [
                
            ])->add('account_number', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('bank', PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('bank_branch', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('bank_branch_city', PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])->add('bank_branch_state', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('bank_branch_telephone', PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('bank_branch_manager', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('benef_bank_swift_code', PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])->add('benef_bank_ifsc_code', PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])->add('benef_bank_account_name', PasswordType::class, [
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
        return 'login';
    }
}