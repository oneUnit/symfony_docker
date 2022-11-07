<?php

namespace tests\src\Entity;
 
use App\Entity\Product;
use PHPUnit\Framework\TestCase;
 
class ProductTest extends TestCase{

    private $product;

    protected function setUp(): void
    {
        $this->product = new Product();
    }

    protected function tearDown(): void
    {
        $this->product = null;
    }


    public function testThatNameIsCorrectlySet(): void
    {
        $this->product->setName("Best Product");

        $this->assertTrue($this->product->getName() === "Best Product");
    }
}