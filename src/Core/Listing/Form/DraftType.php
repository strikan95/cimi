<?php

namespace App\Core\Listing\Form;

use App\Core\Listing\Entity\Amenity;
use App\Core\Listing\Entity\Draft;
use App\Core\Listing\Entity\Listing;
use App\Core\Listing\Entity\StructureType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DraftType extends AbstractType
{
    public function __construct(private EntityManagerInterface $em) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',
                TextType::class,
                [
                    'validation_groups'=> $options['validation_groups']
                ])

            ->add('description',
                TextType::class,
                [
                    'validation_groups'=> $options['validation_groups']
                ])

            ->add(
                'location',
                ListingLocationType::class,
                [
                    'validation_groups'=> $options['validation_groups']
                ])

            ->add('structureType', EntityType::class, [
                'class' => StructureType::class,
                'choices' => $this->getStructureChoices(),
                'choice_value' => 'name',
                'validation_groups'=> $options['validation_groups']
                //'choices' => array_map(function ($type) {
                //    return $type['id'];
                //}, $this->getOptions())
                ])

            ->add('amenities', EntityType::class, [
                'class' => Amenity::class,
                'choices' => $this->getAmenityChoices(),
                'multiple'=> true,
                'choice_value' => 'name',
                'validation_groups'=> $options['validation_groups']
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('validation_groups');

        $resolver->setDefaults([
            'data_class' => Listing::class,
            'csrf_protection' => false
        ]);
    }

    private function getStructureChoices(): array
    {
        return $this->em->getRepository(StructureType::class)->findAll();
    }

    private function getAmenityChoices(): array
    {
        return $this->em->getRepository(Amenity::class)->findAll();
    }
}