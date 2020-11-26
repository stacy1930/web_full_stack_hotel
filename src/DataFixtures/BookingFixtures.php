<?php

namespace App\DataFixtures;

use DateTime;
use DateTimeZone;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\Room;
use App\Entity\Option;
use App\Entity\Booking;
use App\Entity\Customer;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BookingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->seed(0);
        // Option
        // 2 options petitdej / lit supp
        $optionPdej = new Option();
        $optionPdej
            ->setName('Petit déjeuné')
            ->setPrice(6.50);
        $manager->persist($optionPdej);

        $optionJ = new Option();
        $optionJ
            ->setName('Jacuzzi')
            ->setPrice(47.99);
        $manager->persist($optionJ);

        $optionTele = new Option();
        $optionTele
            ->setName('télévision')
            ->setPrice(8.00);
        $manager->persist($optionTele);

        $manager->flush();
        // room
        // Créer 10 room
        $rooms = [];
        for ($i = 0; $i < 10; $i++) {
            $room = new Room();
            $room
                ->setName($faker->word)
                ->setNumber($i + 1)
                ->setPrice($faker->randomFloat(2, 50, 150))
                ->addOption($optionTele)
                ->addOption($optionPdej);

            if ($i % 3 == 0) {
                $room->addOption($optionJ);
            }
            $manager->persist($room);
            $rooms[] = $room;
        }
        $manager->flush();
        // customer créer 50 customers
        $customers = [];
        for ($j = 0; $j < 50; $j++) {
            $customer = new Customer();
            $gender = ($j % 2 == 0) ? 'male' : 'female';
            $customer
                ->setEmail($faker->safeEmail)
                ->setLastname($faker->lastName)
                ->setFirstname($faker->firstName($gender));

            $manager->persist($customer);
            $customers[] = $customer;
        }
        $manager->flush();

        // booking 10/30 par room
        $nbCustomer = count($customers) - 1;
        foreach ($rooms as $room) {
            $nbBooking = $faker->numberBetween(10, 30);

            for ($k = 0; $k < $nbBooking; $k++) {

                $startDate = $faker->dateTimeBetween('-6 month', '+6 month', 'Europe/Paris');
                $startDate->setTime(0, 0, 0, 0);

                $nbNight = $faker->numberBetween(1, 10);
                // clone pour avoir deux zones memoires differente
                $endDate = (clone $startDate)->modify("+$nbNight days");

                $createdDate = (clone $startDate)->modify("-$nbNight days");

                $booking = new Booking();
                $booking
                    ->setCreatedAt($createdDate)
                    ->setRoom($room)
                    ->setCustomer($customers[$faker->numberBetween(0, $nbCustomer)])
                    ->setComment($faker->sentence)
                    ->setStartDate($startDate)
                    ->setEndDate($endDate);

                $manager->persist($booking);
            }
            $manager->flush();
        }

        // $user = $this->getReference('admin');
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class
        );
    }
}
