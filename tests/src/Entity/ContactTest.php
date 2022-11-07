<?php

namespace tests\src\Entity;

use App\Entity\Contact;
use DateTime;
use PHPUnit\Framework\TestCase;
 
class ContactTest extends TestCase{

    private $contact;

    protected function setUp(): void
    {
        $this->contact = new Contact();
    }

    protected function tearDown(): void
    {
        $this->contact = null;
    }


    public function testThatCustomerFullNameAndContactTypeCanBeSetCorrectly(): void
    {
        $this->contact->setCustomerFullName("Peter Grayson");
        $this->contact->setType("Phone");


        $this->assertTrue($this->contact->getCustomerFullName() === "Peter Grayson");
        $this->assertTrue($this->contact->getType() === "Phone");
    }
}