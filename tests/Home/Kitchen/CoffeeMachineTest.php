<?php

namespace Tests\Home\Kitchen;

use PHPUnit\Framework\TestCase;
use App\Home\Kitchen\CoffeeMachine;
use App\Home\Kitchen\CoffeePreferences;

class CoffeeMachineTest extends TestCase
{
    /** @var CoffeeMachine */
    private $coffeeMachine;

    /** @var CoffeePreferences */
    private $coffeePreferences;

    public function setUp() : void
    {
        $this->coffeePreferences = new CoffeePreferences();
        $this->coffeeMachine = new CoffeeMachine($this->coffeePreferences);
    }

    public function testConstruct() : void
    {
        $this->assertAttributeInstanceOf(CoffeePreferences::class, 'coffeePreferences', $this->coffeeMachine);
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
            ->willReturn($this->coffeeMachine);

        $this->coffeeMachine->expects($this->once())
            ->method('addBoilingWater')
            ->willReturn($this->coffeeMachine);

        $this->coffeeMachine->makeCoffee();

        $this->assertAttributeSame($intensity, 'intensity', $this->coffeeMachine);
    }

    public function xtestGrindCoffeeBeans()
    {
        $this->coffeeMachine->grindCoffeeBeans();
    }

    private function mockCoffeeMachine(array $methods)
    {
        return $this->getMockBuilder(CoffeeMachine::class)
            ->setMethods($methods)
            ->setConstructorArgs([$this->coffeePreferences])
            ->getMock();
    }

    private function mockCoffeePreferences(array $methods)
    {
        return $this->getMockBuilder(CoffeePreferences::class)
            ->setMethods($methods)
            ->getMock();
    }
}
