<?php

namespace App\Core\Listing\Form;

use App\Core\Listing\Entity\Embeddable\ListingLocation;
use App\Core\Listing\Entity\Listing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListingLocationType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country')
            ->add('city')
            ->add('postCode')
            ->add('street')
            ->add('streetNumber')
            ->add('longitude')
            ->add('latitude')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ListingLocation::class,
            'csrf_protection' => false,
        ]);
    }

    public function mapDataToForms(mixed $viewData, \Traversable $forms)
    {
        // TODO: Implement mapDataToForms() method.
        return null;
    }

    public function mapFormsToData(\Traversable $forms, mixed &$viewData)
    {
        // TODO: Implement mapFormsToData() method.
        return null;
    }
}