<?php

namespace App\Home\Kitchen;

use App\Logger;
use App\Home\HomeAppliances;

class CoffeeMachine extends HomeAppliances
{
    use WaterHeater;

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

    /** @var CoffeeFilter */
    protected $filter;

    public function __construct(CoffeePreferences $coffeePreferences, CoffeeFilter $coffeeFilter)
    {
        $this->coffeePreferences = $coffeePreferences;
        $this->filter = $coffeeFilter;
    }

    public function makeCoffee() : void
    {
        $this->intensity = $this->coffeePreferences->getIntensity();

        $coffeeBeans = new CoffeeBeans;
        $coffeeGrinder = new CoffeeGrinder;

        try {
            $this->start()
                ->setIntensity($this->intensity)
                ->grindCoffeeBeans($coffeeBeans, $coffeeGrinder)
                ->addBoilingWater();
        } catch (\Exception $exception) {
            $this->turnOnTheRedLight()
                ->alertWarning('Internal error, retry please.');

            Logger::write($exception->getMessage());
        }
    }

    public function setIntensity(array $intensity) : CoffeeMachine
    {
        if (!in_array($intensity, self::ALLOW_INTENSITIES)) {
            throw new \Exception('Level not allow', 1);
        }

        $this->intensity = $intensity;

        return $this;
    }

    public function grindCoffeeBeans(CoffeeBeans $coffeeBeans, CoffeeGrinder $coffeeGrinder) : CoffeeMachine
    {
        $coffeeBeans = $coffeeBeans->takeBeansByIntensity($this->intensity);
        $groundCoffee = $coffeeGrinder->grind($coffeeBeans);
        $this->filter->dump($groundCoffee);

        return $this;
    }

    public function addBoilingWater()
    {
        $hotWater = $this->heatTheWater($this->intensity);
        $this->filter->dump($hotWater);
    }
}
