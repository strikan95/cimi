<?php

namespace App\Core\Listing;

use App\Core\Listing\Form\ListingType;
use Symfony\Component\Form\FormFactoryInterface;

class Wizard
{
    private array $steps = [
        'info',
        'location',
        'structure'
    ];

    private string $formClassname = ListingType::class;

    public function __construct(private FormFactoryInterface $formFactory)
    {

    }

    public function processStep(string $step, mixed $entity, mixed $data)
    {
        $form = $this->formFactory->create(
            ListingType::class,
            $entity,
            ['step' => $step]
        );
    }
}