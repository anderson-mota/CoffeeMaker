<?php

namespace App\Home\Kitchen;

use App\Logger;
use App\Home\HomeAppliances;
use App\Home\Kitchen\CoffeePreferences;

class CoffeeMachine extends HomeAppliances
{
    const LEVEL_LIGHT = 1;
    const LEVEL_MEDIUM = 2;
    const LEVEL_DARK = 3;
    const ALLOW_LEVELS = [
        self::LEVEL_LIGHT,
        self::LEVEL_MEDIUM,
        self::LEVEL_DARK,
    ];

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
        $this->level = $this->coffeePreferences->getLevel();

        try {
            $this->start()
                ->setLevel($this->level);
        } catch (\Exception $exception) {
            $this->turnOnTheRedLight()
                ->alertWarning('Internal error, retry please.');

            Logger::write($exception->getMessage());
        }

        $this->grindCoffeeBeans()
            ->addBoilingWater();
    }

    public function setLevel(int $level) : void
    {
        if (in_array($level, self::ALLOW_LEVELS)) {
            throw new \Exception('Level not allow', 1);
        }

        $this->level = $level;
    }

    public function grindCoffeeBeans()
    {
    }

    public function addBoilingWater()
    {
    }
}
