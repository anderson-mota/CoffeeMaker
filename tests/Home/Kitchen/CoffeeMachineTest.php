<?php

namespace Tests\Home\Kitchen;

use PHPUnit\Framework\TestCase;
use App\Home\Kitchen\CoffeeMachine;
use App\Home\Kitchen\CoffeePreferences;
use App\Home\Kitchen\CoffeeBeans;
use App\Home\Kitchen\CoffeeFilter;
use App\Home\Kitchen\GroundCoffee;
use App\Home\Kitchen\CoffeeGrinder;

class CoffeeMachineTest extends TestCase
{
    /** @var CoffeeMachine */
    private $coffeeMachine;

    /** @var CoffeePreferences */
    private $coffeePreferences;

    /** @var CoffeeFilter */
    private $filter;

    public function setUp() : void
    {
        $this->coffeePreferences = new CoffeePreferences;
        $this->filter = new CoffeeFilter;
        $this->coffeeMachine = new CoffeeMachine($this->coffeePreferences, $this->filter);
    }

    public function testConstruct() : void
    {
        $this->assertAttributeInstanceOf(CoffeePreferences::class, 'coffeePreferences', $this->coffeeMachine);
        $this->assertAttributeInstanceOf(CoffeeFilter::class, 'filter', $this->coffeeMachine);
    }

    public function testMakeCoffee() : void
    {
        $intensity = CoffeeMachine::INTENSITY_LIGHT;

        $this->coffeePreferences = $this->mockCoffeePreferences(['getIntensity']);
        $this->coffeeMachine = $this->mockCoffeeMachine(['start', 'grindCoffeeBeans', 'addBoilingWater']);

        $this->coffeePreferences->expects($this->once())
            ->method('getIntensity')
            ->willReturn($intensity);

        $this->coffeeMachine->expects($this->once())
            ->method('start')
            ->willReturn($this->coffeeMachine);

        $this->coffeeMachine->expects($this->once())
            ->method('grindCoffeeBeans')
            ->with($this->isInstanceOf(CoffeeBeans::class), $this->isInstanceOf(CoffeeGrinder::class))
            ->willReturn($this->coffeeMachine);

        $this->coffeeMachine->expects($this->once())
            ->method('addBoilingWater')
            ->willReturn($this->coffeeMachine);

        $this->coffeeMachine->makeCoffee();

        $this->assertAttributeSame($intensity, 'intensity', $this->coffeeMachine);
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
