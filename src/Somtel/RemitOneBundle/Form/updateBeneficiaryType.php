<?php

namespace Somtel\RemitOneBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class updateBeneficiaryType extends AbstractType
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
                        'required' => false
            ])
            ->add('benef_fname', PasswordType::class, [])
            ->add('benef_lname', TextType::class, [])
            ->add('benef_name', PasswordType::class, [])
            ->add('address1', TextType::class, [])
            ->add('city', PasswordType::class, [
            ])->add('country_id', TextType::class, [
            ])
            ->add('dob', PasswordType::class, [
            ])
            ->add('telephone', TextType::class, [
           
            ])
            ->add('mobile', PasswordType::class, [
           
            ])->add('email', TextType::class, [
             
            ])
            ->add('id_type', PasswordType::class, [
           
            ])
            ->add('id_details', TextType::class, [
             
            ])
            ->add('card_number', PasswordType::class, [
                
            ])->add('account_number', TextType::class, [
              
            ])
            ->add('bank', PasswordType::class, [
                
            ])
            ->add('bank_branch', TextType::class, [
                
            ])
            ->add('bank_branch_city', PasswordType::class, [
                
            ])->add('bank_branch_state', TextType::class, [
              
            ])
            ->add('bank_branch_telephone', PasswordType::class, [
                
            ])
            ->add('bank_branch_manager', TextType::class, [
                
            ])
            ->add('benef_bank_swift_code', PasswordType::class, [
                
            ])->add('benef_bank_ifsc_code', PasswordType::class, [
                
            ])->add('benef_bank_account_name', PasswordType::class, [
               
            ])->add('beneficiary_id', PasswordType::class, [
                
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