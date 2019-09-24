<?php

namespace App\DataFixtures\ORM;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class Fixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Create a doctor entity
        $doctor = new User();
        $doctor->setEmail('doctor1@meinpraxis.com')
            ->setFullName('Dr. John Doe')
            ->setPassword('123456')
            ->setSecretary(false);
        $manager->persist($doctor);

        $doctor = new User();
        $doctor->setEmail('doctor2@meinpraxis.com')
            ->setFullName('Dr. Richard Miles')
            ->setPassword('123456')
            ->setSecretary(false);
        $manager->persist($doctor);

        // Create a secretary entity
        $secretary = new User();
        $secretary->setEmail('secretary@meinpraxis.com')
            ->setFullName('Cornelia Brown')
            ->setPassword('123456')
            ->setSecretary(true);
        $manager->persist($secretary);

        // Flush DB
        $manager->flush();
    }
}