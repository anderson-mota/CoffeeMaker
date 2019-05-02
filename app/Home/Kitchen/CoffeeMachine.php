<?php

namespace App\Home\Kitchen;

use App\Logger;
use App\Home\HomeAppliances;
use App\Home\Kitchen\CoffeePreferences;

class CoffeeMachine extends HomeAppliances
{
    const INTENSITY_LIGHT = 1;
    const INTENSITY_MEDIUM = 2;
    const INTENSITY_DARK = 3;
    const ALLOW_INTENSITIES = [
        self::INTENSITY_LIGHT,
        self::INTENSITY_MEDIUM,
        self::INTENSITY_DARK,
    ];

    /** @var CoffeePreferences */
    protected $coffeePreferences;

    /** @var string */
    protected $intensity;

    public function __construct(CoffeePreferences $coffeePreferences)
    {
        $this->coffeePreferences = $coffeePreferences;
    }

    public function makeCoffee() : void
    {
        $this->intensity = $this->coffeePreferences->getIntensity();

        try {
            $this->start()
                ->setIntensity($this->intensity)
                ->grindCoffeeBeans()
                ->addBoilingWater();
        } catch (\Exception $exception) {
            $this->turnOnTheRedLight()
                ->alertWarning('Internal error, retry please.');

            Logger::write($exception->getMessage());
        }
    }

    public function setIntensity(int $intensity) : CoffeeMachine
    {
        if (!in_array($intensity, self::ALLOW_INTENSITIES)) {
            throw new \Exception('Level not allow', 1);
        }

        $this->intensity = $intensity;

        return $this;
    }

    public function grindCoffeeBeans()
    {
    }

    public function addBoilingWater()
    {
    }
}
