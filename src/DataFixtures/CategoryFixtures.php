<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORY_REFERENCE = 'category';

    public function load(ObjectManager $manager)
    {
        for($i = 1;$i < 6; $i++) {
            $category = new Category();
            $category->setName('category '.$i);
            $manager->persist($category);
        }

        $manager->flush();
        $this->addReference(self::CATEGORY_REFERENCE,$category);
    }
}
