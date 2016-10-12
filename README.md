# Address Format

[![Build Status](https://travis-ci.org/Torann/address-format.svg?branch=master)](https://travis-ci.org/Torann/address-format)
[![Latest Stable Version](https://poser.pugx.org/torann/address-format/v/stable.png)](https://packagist.org/packages/torann/address-format)
[![Total Downloads](https://poser.pugx.org/torann/address-format/downloads.png)](https://packagist.org/packages/torann/address-format)
[![Patreon donate button](https://img.shields.io/badge/patreon-donate-yellow.svg)](https://www.patreon.com/torann)
[![Donate weekly to this project using Gratipay](https://img.shields.io/badge/gratipay-donate-yellow.svg)](https://gratipay.com/~torann)
[![Donate to this project using Flattr](https://img.shields.io/badge/flattr-donate-yellow.svg)](https://flattr.com/profile/torann)
[![Donate to this project using Paypal](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4CJA2A97NPYVU)

A PHP library to parse street addresses to localized formats. The address formats are based on the formats supplied by Google's libaddressinput.

## Installation

- [Address Format on Packagist](https://packagist.org/packages/torann/address-format)
- [Address Format on GitHub](https://github.com/torann/address-format)

### Composer

From the command line run:

```bash
$ composer require torann/address-format
```

## Usage

### Formatting

`format(array $data, $html = false)`

**Arguments:**

- `$data` - An array of address elements
- `$html` - When set to true the address elements will be wrapped with `span` tags.

> **NOTE:** The wrapping span tags contain itemprop attributes that adhere to the [PostalAddress](http://schema.org/PostalAddress) schema.

**Usage:**

```php
$address = \Torann\AddressFormat\Address::format([
    'recipient' => 'Jane Doe',
    'organization' => 'Whitworth Institute Inc.',
    'street_address' => '20341 Whitworth Institute',
    'street_address_2' => '405 N. Whitworth',
    'locality' => 'Seattle',
    'admin_area' => 'WA',
    'postal_code' => '98052',
    'country_iso' => 'US',
]);
```

The above code will produce the following:

```
Jane Doe
Whitworth Institute Inc.
20341 Whitworth Institute
405 N. Whitworth
Seattle, WA 98052
```

> **NOTE:** The `country_iso` attribute is used to determine the address's format. The default is set to _US_.

## Custom Country Formats

This allows you to set your own formats.

`setFormats(array $countries)`

**Arguments:**

- `$countries` - An array of country ISO codes and corresponding formats values.

**Usage:**

```php
\Torann\AddressFormat\Address::setFormats([
    'GB' => '%N%n%O%n%A%n%C%n%Z %R',
    'US' => '%N%n%O%n%A%n%C, %S %Z %R',
]);
```

## Available Attributes

| Attribute          | Format Key | Common Name   |
|--------------------|------------|---------------|
| admin_area         | S          | state         |
| locality           | C          | city          |
| recipient          | N          | person's name |
| organization       | O          | organization  |
| dependent_locality | D          |               |
| postal_code        | Z          | zip code      |
| sorting_code       | X          |               |
| street_address     | A          |               |
| country            | R          |               |

## Change Log

#### v1.0.0

- First release
