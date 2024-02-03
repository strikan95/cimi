<?php

namespace App\Tests;

use App\Core\Listing\Entity\Embeddable\ListingLocation;
use App\Core\Listing\Entity\Listing;
use App\Core\Listing\Entity\ListingStructure;
use App\Core\Listing\Form\ListingInfoType;
use App\Core\Listing\Form\ListingLocationType;
use App\Core\Listing\Form\ListingStructureType;
use App\Core\Listing\Form\ListingType;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TestDto
{
    public $title;
    public $description;
}

class ListingBuilder {
    private $steps;
    private $form;

    function getFormForCurrentStep() {}
    function resolveCurrentStep() {}
    function getNextStep() {}
    function getPreviousStep() {}
    function storeIntermediateStep() {}

    // api/v1/build-a-listing/{draft}/step
    // get the draft
    // generate form for the current step
    // fill form
    // validate data
    // get raw data from form
    // NOT LAST STEP                                    // LAST STEP
    // update draft.data                                // generate complete form with all field
    // save draft                                       // fill form
    // return response with next step and options       // validate data
                                                        // get the entity
                                                        // save entity
                                                        // remove draft
                                                        // return response with entity id
}

class ListingWizardTest extends KernelTestCase
{
    #[NoReturn] public function testForm(): void
    {
        $kernel = self::bootKernel();

        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get('doctrine.orm.entity_manager');
        $structureTypes = $em->getRepository(ListingStructure::class)->findAll();

        $property = new Listing();

        $location = new ListingLocation();
        $location->setCountry('Hrvatska');
        $location->setCity('Osijek');
        $location->setZipCode(31000);
        $location->setStreet('Hutlerova');
        $location->setStreetNumber('1A');
        $property->setLocation($location);
        //$property->setStructureType($structureTypes[0]);

        $formFactory = self::getContainer()->get('form.factory');
        $form = $formFactory->create(
            ListingType::class,
            $property,
            ['step' => 'location']
        );

        $form->submit(['structureType' => 'Apartment']);


        $formFields = array_keys($form->all());
        $data = [];
        foreach ($formFields as $field) {
            $getter = 'get' . ucfirst($field);
            if (method_exists($property, $getter)) {
                $data[$field] = $property->$getter();
            }
        }

        dd($data);

    }

    /** #[NoReturn] public function testSomething(): void
    {
        $kernel = self::bootKernel();

        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $formFactory = $kernel->getContainer()->get('form.factory');
        $formFactory->

        $user = $em->getRepository(User::class)->find(1);

        $dto = new TestDto();
        $dto->title = 'test title';
        $dto->description = 'test description';

        $draft = new PropertyListingDraft();
        $draft->setOwner($user);
        $draft->setData($dto);

        $em->persist($draft);
        $em->flush();
        $em->clear();

        $draft = $em->getRepository(PropertyListingDraft::class)->find($draft->getId());
        $this->assertNotNull($draft);
        dd($draft->getData());

        //$this->assertSame('test', $kernel->getEnvironment());
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);
    } */
}
