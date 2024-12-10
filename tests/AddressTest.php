<?php

use Torann\AddressFormat\Address;

class AddressTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldFormatAddress()
    {
        $expected = "Jane Doe\nWhitworth Institute Inc.\n20341 Whitworth Institute\n405 N. Whitworth\nSeattle, WA 98052";

        $address = Address::format([
            'recipient' => 'Jane Doe',
            'organization' => 'Whitworth Institute Inc.',
            'street_address' => '20341 Whitworth Institute',
            'street_address_2' => '405 N. Whitworth',
            'locality' => 'Seattle',
            'admin_area' => 'WA',
            'postal_code' => '98052',
            'country_iso' => 'US',
        ]);

        $this->assertEquals($address, $expected);
    }

    /**
     * @test
     */
    public function shouldGetFormats()
    {
        $formats = [
            'US' => 'Foo bar',
        ];

        Address::setFormats($formats);

        $this->assertEquals(Address::getFormats(), $formats);
    }

    /**
     * @test
     */
    public function shouldSetFormats()
    {
        Address::setFormats([
            'US' => '%N%n%O%n%A%n%C, %S %Z %R',
        ]);

        $expected = "Jane Doe\nWhitworth Institute Inc.\n20341 Whitworth Institute\n405 N. Whitworth\nSeattle, WA 98052 USA";

        $address = Address::format([
            'recipient' => 'Jane Doe',
            'organization' => 'Whitworth Institute Inc.',
            'street_address' => '20341 Whitworth Institute',
            'street_address_2' => '405 N. Whitworth',
            'locality' => 'Seattle',
            'admin_area' => 'WA',
            'postal_code' => '98052',
            'country' => 'USA',
            'country_iso' => 'US',
        ]);

        $this->assertEquals($address, $expected);
    }

    /**
     * @test
     */
    public function shouldResetAndReloadFormats()
    {
        Address::setFormats(null);

        $expected = include(__DIR__ . '/../src/countries.php');

        // This forces it to reload the country list
        Address::format([
            'recipient' => 'Jane Doe',
            'organization' => 'Whitworth Institute Inc.',
            'street_address' => '20341 Whitworth Institute',
            'street_address_2' => '405 N. Whitworth',
            'locality' => 'Seattle',
            'admin_area' => 'WA',
            'postal_code' => '98052',
            'country_iso' => 'US',
        ]);

        $this->assertEquals(Address::getFormats(), $expected);
    }
}
