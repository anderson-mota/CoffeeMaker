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
        $level = 'medium';
        $this->coffeePreferences = $this->mockCoffeePreferences(['getLevel']);
        $this->coffeeMachine = $this->mockCoffeeMachine(['start', 'setLevel', 'grindCoffeeBeans', 'addBoilingWater']);

        $this->coffeePreferences->expects($this->once())
            ->method('getLevel')
            ->willReturn($level);

        $this->coffeeMachine->expects($this->once())
            ->method('start')
            ->willReturn($this->coffeeMachine);

        $this->coffeeMachine->expects($this->once())
            ->method('setLevel')
            ->with($level)
            ->willReturn($this->coffeeMachine);

        $this->coffeeMachine->expects($this->once())
            ->method('grindCoffeeBeans')
            ->willReturn($this->coffeeMachine);

        $this->coffeeMachine->expects($this->once())
            ->method('addBoilingWater')
            ->willReturn($this->coffeeMachine);

        $this->coffeeMachine->makeCoffee();
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
