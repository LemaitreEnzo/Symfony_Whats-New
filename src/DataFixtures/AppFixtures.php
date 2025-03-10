<?php

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use App\Factory\FunFactFactory;
use App\Factory\PromoFactory;
use App\Factory\ScheduleFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        PromoFactory::createPromo();
        UserFactory::createMany(10);
        CategoryFactory::createMany(5);
        FunFactFactory::createMany(50);
        ScheduleFactory::createMany(2);
    }
}
