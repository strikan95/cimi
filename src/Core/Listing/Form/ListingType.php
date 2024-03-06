<?php

namespace App\Core\Listing\Form;

use App\Core\Listing\Entity\Amenity;
use App\Core\Listing\Entity\Listing;
use App\Core\Listing\Entity\StructureType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListingType extends AbstractType
{
    const STEPS = [
        'title',
        'description',
        'location',
        'structure-type',
        'images',
        'amenities'
    ];

    private ?string $currentStep = null;

    public function __construct(private EntityManagerInterface $em) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $step = $options['step'];
        $this->currentStep = $step;

        switch ($step) {
            case 'title':
                $builder
                    ->add('title')
                ;
                break;
            case 'description':
                $builder
                    ->add('description')
                ;
                break;
            case 'location':
                $builder
                    ->add(
                        'location',
                        ListingLocationType::class,
                        ['allow_extra_fields' => true]
                    )
                ;
                break;
            case 'structure-type':
                $builder
                    ->add('structureType', EntityType::class, [
                        'class' => StructureType::class,
                        'choices' => $this->getChoices(),
                        'choice_value' => 'name'
                        //'choices' => array_map(function ($type) {
                        //    return $type['id'];
                        //}, $this->getOptions())
                    ])
                ;
                break;
            case 'images':
                $builder->add('images', CollectionType::class, [
                    'entry_type' => TextType::class,
                    'allow_add' => true,
                    'allow_delete' => true
                    ])
                ;
            case 'amenities':
                $builder->add('amenities', EntityType::class, [
                    'class' => Amenity::class,
                    'choices' => $this->getChoices(),
                    'multiple'=> true,
                    'choice_value' => 'name'
                ]);
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

    /**
     * @throws Exception
     */
    public function getChoices(): ?array
    {
        return match ($this->currentStep) {
            'structure-type' => $this->getStructureChoices(),
            'amenities' => $this->getAmenityChoices(),
            default => null
        };
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