<?php

namespace App\Core\Listing\Form;

use App\Core\Listing\Entity\Listing;
use App\Core\Listing\Entity\ListingStructure;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListingType extends AbstractType
{
    const STEPS = [
        'info',
        'location',
        'structure_type'
    ];

    private ?string $currentStep = null;

    public function __construct(private EntityManagerInterface $em) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $step = $options['step'];
        $this->setCurrentStep($step);

        switch ($step) {
            case 'info':
                $builder
                    ->add('title')
                    ->add('description')
                ;
                break;
            case 'location':
                $builder
                    ->add('location', ListingLocationType::class)
                ;
                break;
            case 'structure_type':
                $builder
                    ->add('structureType', EntityType::class, [
                        'class' => ListingStructure::class,
                        'choices' => $this->getChoices(),
                        'choice_value' => 'name'
                        //'choices' => array_map(function ($type) {
                        //    return $type['id'];
                        //}, $this->getOptions())
                    ] )
                ;
                break;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('step');

        $resolver->setDefaults([
            'data_class' => Listing::class,
            'csrf_protection' => false
        ]);
    }

    private function setCurrentStep(string $step): void
    {
        $this->step = $step;
    }

    public function getChoices(): ?array
    {
        switch ($this->currentStep) {
            case 'structure':
                return $this->getStructureChoices();
        }
        return null;
    }

    private function getStructureChoices(): array
    {
        return $this->em->getRepository(ListingStructure::class)->findAll();
    }
}