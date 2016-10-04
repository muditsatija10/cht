<?php

namespace ITG\MillBundle\Form;

use ITG\MillBundle\Entity\DateRange;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add('start', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'input' => 'datetime',
                'empty_data' => '0000-12-12'
            ])
            ->add('end', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'input' => 'datetime',
                'empty_data' => '9999-12-12'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'allow_extra_fields' => true,
            'data_class' => DateRange::class
        ]);
    }

    public function getName()
    {
        return 'date_range';
    }
}