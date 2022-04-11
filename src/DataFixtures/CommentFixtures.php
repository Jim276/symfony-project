<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 0;$i < 5; $i++) {
            $comment = new Comment();
            $comment->setContent('Super comment'.$i);
            $comment->setAuthor($this->getReference(UserFixtures::USER_REFERENCE));
            $comment->setPost($this->getReference(PostFixtures::POST_REFERENCE));
            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class, PostFixtures::class];
    }
}
