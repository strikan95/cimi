<?php

namespace App\Core\Listing\Form;

use App\Core\Listing\Entity\Listing;
use App\Core\Listing\Entity\StructureType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListingStructureType extends AbstractType
{
    public function __construct(private EntityManagerInterface $em) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('structureType', EntityType::class, [
                'class' => StructureType::class,
                'choices' => $this->getOptions(),
                'choice_value' => 'name'
                //'choices' => array_map(function ($type) {
                //    return $type['id'];
                //}, $this->getOptions())
            ] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Listing::class,
            'csrf_protection' => false,
        ]);
    }

    public function getOptions(): array
    {
        $types = $this->em->getRepository(StructureType::class)->findAll();
        return $types;
    }
}