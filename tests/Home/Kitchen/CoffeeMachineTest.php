<?php

namespace Tests\Home\Kitchen;

use PHPUnit\Framework\TestCase;
use App\Home\Kitchen\CoffeeMachine;
use App\Home\Kitchen\CoffeePreferences;
use App\Home\Kitchen\CoffeeBeans;
use App\Home\Kitchen\CoffeeFilter;
use App\Home\Kitchen\GroundCoffee;
use App\Home\Kitchen\CoffeeGrinder;
use App\Home\Kitchen\Ingredient;

class CoffeeMachineTest extends TestCase
{
    /** @var CoffeeMachine */
    private $coffeeMachine;

    /** @var CoffeePreferences */
    private $coffeePreferences;

    /** @var CoffeeFilter */
    private $filter;

    public function setUp(): void
    {
        $this->coffeePreferences = new CoffeePreferences;
        $this->filter = new CoffeeFilter;
        $this->coffeeMachine = new CoffeeMachine($this->coffeePreferences, $this->filter);
    }

    public function testConstruct(): void
    {
        $this->assertAttributeInstanceOf(CoffeePreferences::class, 'coffeePreferences', $this->coffeeMachine);
        $this->assertAttributeInstanceOf(CoffeeFilter::class, 'filter', $this->coffeeMachine);
    }

    public function testGrindCoffeeBeans()
    {
        $intensity = CoffeeMachine::INTENSITY_LIGHT;
        $groundCoffee = $this->createMock(GroundCoffee::class);

        $this->filter = $this->mockFilter(['dump']);
        $coffeeGrinder = $this->mockCoffeeGrinder(['grind']);
        $coffeeBeans = $this->mockCoffeeBeans(['takeBeansByIntensity']);
        $coffeeBeans->expects($this->once())
            ->method('takeBeansByIntensity')
            ->with($intensity)
            ->willReturn($coffeeBeans);

        $coffeeGrinder->expects($this->once())
            ->method('grind')
            ->with($coffeeBeans)
            ->willReturn($groundCoffee);

        $this->filter->expects($this->once())
            ->method('dump')
            ->with($groundCoffee);

        $this->coffeeMachine = new CoffeeMachine($this->coffeePreferences, $this->filter);
        $this->coffeeMachine->setIntensity($intensity);
        $coffeeMachine = $this->coffeeMachine->grindCoffeeBeans($coffeeBeans, $coffeeGrinder);

        $this->assertInstanceOf(CoffeeMachine::class, $coffeeMachine);
    }

    public function testAddBoilingWater()
    {
        $intensity = CoffeeMachine::INTENSITY_LIGHT;
        $hotWater = $this->createMock(Ingredient::class);
        $this->filter = $this->mockFilter(['dump']);
        $this->coffeeMachine = $this->mockCoffeeMachine(['heatTheWater']);

        $this->coffeeMachine->expects($this->once())
            ->method('heatTheWater')
            ->with($intensity)
            ->willReturn($hotWater);

        $this->filter->expects($this->once())
            ->method('dump')
            ->with($hotWater);

        $this->coffeeMachine->setIntensity($intensity);
        $this->coffeeMachine->addBoilingWater();
    }

    private function mockCoffeeMachine(array $methods)
    {
        return $this->getMockBuilder(CoffeeMachine::class)
            ->setMethods($methods)
            ->setConstructorArgs([$this->coffeePreferences, $this->filter])
            ->getMock();
    }

    private function mockCoffeePreferences(array $methods)
    {
        return $this->getMockBuilder(CoffeePreferences::class)
            ->setMethods($methods)
            ->getMock();
    }

    private function mockCoffeeBeans(array $methods)
    {
        return $this->getMockBuilder(CoffeeBeans::class)
            ->setMethods($methods)
            ->getMock();
    }

    private function mockFilter(array $methods)
    {
        return $this->getMockBuilder(CoffeeFilter::class)
            ->setMethods($methods)
            ->getMock();
    }

    private function mockCoffeeGrinder(array $methods)
    {
        return $this->getMockBuilder(CoffeeGrinder::class)
            ->setMethods($methods)
            ->getMock();
    }
}
