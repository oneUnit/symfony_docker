<?php

namespace tests\src\Entity;
 
use App\Entity\Region;
use PHPUnit\Framework\TestCase;
 
class RegionTest extends TestCase{

    private $region;

    protected function setUp(): void
    {
        $this->region = new Region();
    }

    protected function tearDown(): void
    {
        $this->region = null;
    }


    public function testThatRegionNameAndCountryCodeIsCorrectlySet(): void
    {
        $this->region->setName("Hamburg");
        $this->region->setCountryCode("DE");

        $this->assertTrue($this->region->getName() === "Hamburg");
        $this->assertTrue($this->region->getCountryCode() == "DE");
    }

    public function testThatCountryCodeIsAlwaysUpperCase(): void
    {
        $this->region->setCountryCode("de");

        $this->assertTrue($this->region->getCountryCode() == "DE");

        $this->region->setCountryCode("eN");

        $this->assertTrue($this->region->getCountryCode() == "EN");
    }
}