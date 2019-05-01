<?php

namespace App\Home\Kitchen;

use App\Home\HomeAppliances;
use App\Home\Kitchen\CoffeePreferences;

class CoffeeMachine extends HomeAppliances
{
    /** @var CoffeePreferences */
    protected $coffeePreferences;

    /** @var string */
    protected $level;

    public function __construct(CoffeePreferences $coffeePreferences)
    {
        $this->coffeePreferences = $coffeePreferences;
    }

    public function makeCoffee() : void
    {
        $level = $this->coffeePreferences->getLevel();

        $this->start()
            ->setLevel($level);

        $this->grindCoffeeBeans()
            ->addBoilingWater();
    }

    public function setLevel(string $level) : void
    {
        $this->level = $level;
    }

    public function grindCoffeeBeans()
    {
    }

    public function addBoilingWater()
    {
    }
}
