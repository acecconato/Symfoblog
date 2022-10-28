<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Profile;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        /** @var User[] $users */
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user
                ->setUsername($faker->userName())
                ->setEmail($faker->email())
                ->setPassword($faker->password());

            $manager->persist($user);
            $users[] = $user;
        }

        $labels = [
            'SEO',
            'WCAG',
            'Digital',
            'Marketing',
            'Programmation',
        ];

        $categories = [];
        foreach ($labels as $label) {
            $category = new Category();
            $category->setLabel($label);

            $manager->persist($category);
            $categories[] = $category;
        }

        for ($i = 0; $i < 20; $i++) {
            $post = new Post();
            $post
                ->setTitle($faker->sentence(variableNbWords: true))
                ->setContent($faker->text(maxNbChars: 3000))
                ->addCategory($categories[rand(0, count($categories) - 1)])
                ->setAuthor($users[rand(0, count($users) - 1)]);

            if (rand(0, 1)) {
                $post->setImage('https://loremflickr.com/1920/1080');
            }

            $manager->persist($post);
        }

        foreach ($users as $user) {
            $user->setProfile(
                (new Profile())
                    ->setAvatar('https://loremflickr.com/150/150')
                    ->setDescription($faker->text(maxNbChars: 600))
            );

            $manager->persist($user);
        }

        $manager->flush();
    }
}
