<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class ExempleTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
        $this->assertTrue(3==3, "legalite n'est pas ");
    }
}
