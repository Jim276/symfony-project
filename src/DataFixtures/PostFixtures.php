<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public const POST_REFERENCE = 'post';

    public function load(ObjectManager $manager): void
    {
        for($i=0; $i<10; $i++) {
            $post = new Post();
            $post->setTitle('post '.$i);
            $post->setContent("Pas très beau mais fonctionnel !");
            $post->setPublicationDate(new \DateTime());
            $post->setSlug('post '.$i);
            $post->addCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE));
            $post->setAuthor($this->getReference(UserFixtures::USER_REFERENCE));
            $manager->persist($post);
        }
        $manager->flush();
        $this->addReference(self::POST_REFERENCE,$post);
    }
    
    public function getDependencies()
    {
        return [CategoryFixtures::class, UserFixtures::class];
    }
}
