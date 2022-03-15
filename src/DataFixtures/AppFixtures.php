<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Lesson;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager): void
    {
        $this->faker = Faker\Factory::create();
        // $product = new Product();
        // $manager->persist($product);
        $admin = new Admin();
        $hashpwd = $this->encoder->hashPassword($admin, "123");
        $admin->setRoles(['ROLE_ADMIN'])->setEmail($this->faker->email)->setFirstname($this->faker->firstname)->setLastname($this->faker->lastName)->setPassword($hashpwd);
        $admin->setBadgeNum($this->faker->ean8);
        $manager->persist($admin);

        $mentor = new User();
        $mentor->setRoles(['ROLE_MENTOR'])->setEmail($this->faker->email)->setFirstname($this->faker->firstname)->setLastname($this->faker->lastName)->setPassword($hashpwd);
        $manager->persist($mentor);

        $user = new User();
        $user = $user->setRoles(['ROLE_USER'])->setEmail($this->faker->email)->setFirstname($this->faker->firstname)->setLastname($this->faker->lastName)->setPassword($hashpwd)->setMentor($mentor);
        $manager->persist($user);

        $lesson = new Lesson();
        $lesson->setName('History')->setStart(new DateTime('now'))->setEnd(new DateTime('now + 2 hours'));

        $manager->flush();
    }
}
