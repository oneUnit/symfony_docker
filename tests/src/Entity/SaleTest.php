<?php

namespace tests\src\Entity;
 
use App\Entity\Sale;
use PHPUnit\Framework\TestCase;
 
class SaleTest extends TestCase{

    private $sale;

    protected function setUp(): void
    {
        $this->sale = new Sale();
    }

    protected function tearDown(): void
    {
        $this->sale = null;
    }

    public function testThatPrecisionIsConserved(): void
    {
        $gross_amount = "1246.5256";
        $net_amount = "834.53";
        $tax_rate = "0.19";
        $cost = "533.132";

        $this->sale->setGrossAmount($gross_amount);
        $this->sale->setNetAmount($net_amount);
        $this->sale->setTaxRate($tax_rate);
        $this->sale->setCost($cost);

        $this->assertTrue($this->sale->getGrossAmount() === $gross_amount);
        $this->assertTrue($this->sale->getNetAmount() === $net_amount);
        $this->assertTrue($this->sale->getTaxRate() === $tax_rate);
        $this->assertTrue($this->sale->getCost() === $cost);
    }

    public function testThatProfitIsCalculatedCorrectlyAndWithPrecision(): void
    {
        $gross_amount = "1246.5256";
        $net_amount = "834.53";
        $tax_rate = "0.19";
        $cost = "533.132";

        $this->sale->setGrossAmount($gross_amount);
        $this->sale->setNetAmount($net_amount);
        $this->sale->setTaxRate($tax_rate);
        $this->sale->setCost($cost);

        $awaited_value = "475.8680";

        $this->assertTrue($this->sale->getProfit() === $awaited_value);
    }

    public function testThatProfitPercentageIsCalculatedCorrectlyAndWithPrecision(): void
    {
        $gross_amount = "1246.5256";
        $net_amount = "834.53";
        $tax_rate = "0.19";
        $cost = "533.132";

        $this->sale->setGrossAmount($gross_amount);
        $this->sale->setNetAmount($net_amount);
        $this->sale->setTaxRate($tax_rate);
        $this->sale->setCost($cost);

        $awaited_value = "0.3817";

        $this->assertTrue($this->sale->getProfitPercent() === $awaited_value);
    }

}