<?php

namespace App\Shared\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

abstract class BaseFixture extends Fixture
{
    /** @var ObjectManager */
    private ObjectManager $manager;
    protected Generator $faker;

    private array $referencesIndex = [];

    private int $createdCount = 0;

    abstract protected function loadData(ObjectManager $manager);
    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->faker = Factory::create();
        $this->loadData($manager);
    }

    protected function createManyAlternative(
        int $count,
        callable $factory,
        mixed $data = null,
    ): void {
        for ($i = 0; $i < $count; $i++) {
            $entity = $factory($i, $data);
            $this->manager->persist($entity);
            // store for usage later as App\Entity\ClassName_#COUNT#
            $this->addReference(
                get_class($entity) . '_' . $i + $this->createdCount,
                $entity,
            );
        }

        $this->createdCount += $count;
    }

    protected function createOne(
        string $className,
        callable $factory,
        mixed $data = null,
    ): void {
        $entity = new $className();
        $factory($entity, $data);
        $this->manager->persist($entity);
        // store for usage later as App\Entity\ClassName_UUID
        $this->addReference($className . '_' . $this->faker->uuid(), $entity);
    }

    protected function createMany(
        string $className,
        int $count,
        callable $factory,
        mixed $data = null,
    ): void {
        for ($i = 0; $i < $count; $i++) {
            $entity = new $className();
            $factory($entity, $i, $data);
            $this->manager->persist($entity);
            // store for usage later as App\Entity\ClassName_#COUNT#
            $this->addReference(
                $className . '_' . $i + $this->createdCount,
                $entity,
            );
        }

        $this->createdCount += $count;
    }

    protected function getRandomReference(string $className)
    {
        $this->loadReferenceIndex($className);

        $randomReferenceKey = $this->faker->randomElement(
            $this->referencesIndex[$className],
        );
        return $this->getReference($randomReferenceKey);
    }

    protected function getRandomNReferences(
        string $className,
        int $min,
        int $max,
    ): Collection {
        $this->loadReferenceIndex($className);
        $refs = $this->referencesIndex[$className];

        $n = rand($min, $max);
        $arr = new ArrayCollection();

        for ($i = 0; $i < $n; $i++) {
            $randomReferenceKey = $this->faker->randomElement($refs);
            unset($refs[$randomReferenceKey]);
            $arr[] = $this->getReference($randomReferenceKey);
        }

        return $arr;
    }

    protected function getReferenceAtIndex(
        string $className,
        int $index,
    ): object {
        $this->loadReferenceIndex($className);

        return $this->getReference($this->referencesIndex[$className][$index]);
    }

    protected function getReferencesCount(string $className): int
    {
        $this->loadReferenceIndex($className);

        return count($this->referencesIndex[$className]);
    }

    private function loadReferenceIndex(string $className): void
    {
        if (!isset($this->referencesIndex[$className])) {
            $this->referencesIndex[$className] = [];
            foreach (
                $this->referenceRepository->getReferencesByClass()
                as $key => $ref
            ) {
                $this->referencesIndex[$key] = array_keys($ref);
            }
        }

        if (empty($this->referencesIndex[$className])) {
            die(
                sprintf('Cannot find any references for class "%s"', $className)
            );
        }
    }
}
