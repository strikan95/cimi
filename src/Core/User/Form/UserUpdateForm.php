<?php

namespace App\Core\User\Form;

use App\Core\Listing\Entity\Listing;
use App\Core\Listing\Form\ImageType;
use App\Core\User\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserUpdateForm extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ): void {
        $builder
            ->add('firstName', TextType::class, [
                'validation_groups' => $options['validation_groups'],
            ])

            ->add('lastName', TextType::class, [
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