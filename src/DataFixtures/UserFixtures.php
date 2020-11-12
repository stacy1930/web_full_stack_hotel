<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    // Pour encoder les mdp
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $userRole = new Role();
        $userRole->setName('ROLE_USER');
        $manager->persist($userRole);

        $adminRole = new Role();
        $adminRole->setName('ROLE_ADMIN');
        $manager->persist($adminRole);

        $manager->flush();

        $user = new User();
        $user->setEmail($faker->safeEmail)
            ->setPassword($this->encoder->encodePassword($user, 'admin')) //Le code sera encoder dans la bdd
            ->addRole($userRole);

        $manager->persist($user); //Ouvre une transaction avec la BDD
        $manager->flush();

        $user = new User();
        $user->setEmail('admin@ex.com')
            ->setPassword($this->encoder->encodePassword($user, 'admin')) //Le code sera encoder dans la bdd
            ->addRole($userRole);

        $manager->persist($user); //Ouvre une transaction avec la BDD
        $manager->flush();

        $user = new User();
        $user->setEmail('user@ex.com')
            ->setPassword($this->encoder->encodePassword($user, 'admin')) //Le code sera encoder dans la bdd
            ->addRole($userRole)
            ->addRole($adminRole);

        $manager->persist($user); //Ouvre une transaction avec la BDD
        $manager->flush();
    }
}
