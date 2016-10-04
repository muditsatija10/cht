<?php

namespace ITG\LogBundle\Form;

use AppBundle\Entity\Log;
use Doctrine\DBAL\Types\ObjectType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', TextType::class, ['required' => false, 'empty_data' => 'LOG'])
            ->add('request', TextareaType::class, ['required' => false])
            ->add('response', TextareaType::class, ['required' => false])
            ->add('payload', TextareaType::class, ['required' => false])
            ->add('extra', TextareaType::class, ['required' => false])
            ->add('project', TextType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Log::class,
            'allow_extra_fields' => true
        ));
    }

    public function getName()
    {
        return 'log';
    }
}