<?php

namespace App\Core\User\Form;

use App\Core\Listing\Entity\Listing;
use App\Core\Listing\Form\ImageType;
use App\Core\User\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegisterType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ): void {
        $builder
            ->add('email', TextType::class, [
                'validation_groups' => $options['validation_groups'],
            ])

            ->add('password', TextType::class, [
                'validation_groups' => $options['validation_groups'],
            ])

            ->add('firstName', TextType::class, [
                'validation_groups' => $options['validation_groups'],
            ])

            ->add('lastName', TextType::class, [
                'validation_groups' => $options['validation_groups'],
            ])
            ->add('roles', TextType::class, [
                'validation_groups' => $options['validation_groups'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('validation_groups');

        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false,
        ]);
    }

}