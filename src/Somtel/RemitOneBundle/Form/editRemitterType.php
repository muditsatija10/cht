<?php

namespace Somtel\RemitOneBundle\Form;

use ITG\MillBundle\Validator\Constraints\Email;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class editRemitterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add('username', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])->add('session_token', PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('name', PasswordType::class, [])
            ->add('fname', TextType::class)
            ->add('lname', TextType::class)
            ->add('address1', TextType::class)
            ->add('city', TextType::class)
            ->add('country_id', TextType::class)
            ->add('dob', TextType::class,[
                'constraints' => [
                    new Assert\Date()
                ]
            ])
            ->add('telephone', TextType::class)
            ->add('mobile', TextType::class)
            ->add('email', TextType::class)
            ->add('id1_type', TextType::class)
            ->add('id1_scan', TextType::class)
            ->add('id1_details', TextType::class)
            ->add('id1_expiry', TextType::class)
            ->add('card_number', TextType::class)
            ->add('postcode', TextType::class)
            ->add('account_number', TextType::class)
            ->add('bank', TextType::class)
            ->add('bank_branch', TextType::class)
            ->add('bank_branch_city', TextType::class)
            ->add('bank_branch_state', TextType::class)
            ->add('bank_branch_telephone', TextType::class)
            ->add('bank_branch_manager', TextType::class)
            ->add('benef_bank_swift_code', TextType::class)
            ->add('benef_bank_ifsc_code', TextType::class)
            ->add('benef_bank_account_name', TextType::class)
            ->add('source_country_id', TextType::class)
            ->add('nationality', TextType::class)
            ->add('toc', TextType::class)
            
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