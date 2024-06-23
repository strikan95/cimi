<?php

namespace App\Core\Renting\Form;

use App\Core\Renting\Entity\RentPeriod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RentPeriodType extends AbstractType
{
    const DATE_FORMAT = 'yyyy-MM-dd';

    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ): void {
        $builder
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'format' => self::DATE_FORMAT,
            ])

            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'format' => self::DATE_FORMAT,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RentPeriod::class,
            'csrf_protection' => false,
        ]);
    }
}
