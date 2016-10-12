<?php

namespace Torann\AddressFormat;

class Address
{
    /**
     * Country address formats.
     *
     * @var array
     */
    protected static $countries;

    /**
     * This map specifies the content on how to format the address
     * See this URL for origin reference
     *
     * https://code.google.com/p/libaddressinput/source/browse/trunk
     * /src/com/android/i18n/addressinput/AddressField.java?r=111
     *
     * @var array
     */
    protected static $address_map = [
        'S' => 'admin_area',        // state
        'C' => 'locality',          // city
        'N' => 'recipient',         // name
        'O' => 'organization',      // organization
        'D' => 'dependent_locality',
        'Z' => 'postal_code',
        'X' => 'sorting_code',
        'A' => 'street_address',
        'R' => 'country',
    ];

    /**
     * This map specifies the item to a `itemprop` attribute
     *
     * @var array
     */
    protected static $itemprops = [
        'admin_area' => 'addressRegion',    // state
        'locality' => 'addressLocality',    // city
        'recipient' => 'name',              // name
        'organization' => 'affiliation',    // organization
        'dependent_locality' => '',
        'postal_code' => 'postalCode',
        'sorting_code' => '',
        'street_address' => 'streetAddress',
        'country' => 'addressCountry',
    ];

    /**
     * The input map will hold all the information we put in to the class
     *
     * @var array
     */
    protected static $structure = [
        'admin_area' => '',         // state
        'locality' => '',           // city
        'recipient' => '',          // name
        'organization' => '',       // organization
        'street_address' => '',     // street
        'street_address_2' => '',   // street 2
        'dependent_locality' => '',
        'postal_code' => '',
        'sorting_code' => '',
        'country' => '',
        'country_iso' => 'us',
    ];


    /**
     * Return the formatted address in HTML, using the
     * given country code. Just a shortcut method.
     *
     * @param array $data
     *
     * @return string
     */
    public static function formatHtml(array $data)
    {
        return self::format($data, true);
    }

    /**
     * Return the formatted address in HTML or plain
     * text, using the given country code.
     *
     * @param array $data
     * @param bool  $html
     *
     * @return string
     */
    public static function format(array $data, $html = false)
    {
        // Merge in defaults
        $data = array_merge(self::$structure, $data);

        // Load country option
        $formatted_address = self::getFormat($data['country_iso']);

        // Replace the street values
        foreach (self::$address_map as $id => $key) {
            $value = $data[$key];

            // Skip the second address
            if ($key == 'street_address_2') continue;

            // Append second street address
            if ($key == 'street_address') {
                $value = $value . ($data['street_address_2'] ? ($html ? '<br>' : '%n') . $data['street_address_2'] : '');
            }

            // Wrap with SPAN for HTML layouts
            if ($html === true && $value) {
                $value = "<span" . self::getItemProp($key) . ">{$value}</span>";
            }

            $formatted_address = str_replace("%{$id}", $value, $formatted_address);
        }

        // Add new lines
        $formatted_address = trim(str_replace('%n', "\n", $formatted_address));

        // Remove blank lines from the resulting address
        $formatted_address = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $formatted_address);

        return $formatted_address;
    }

    /**
     * Get a country's address format.
     *
     * @param string $country
     *
     * @return mixed
     */
    public static function getFormat($country)
    {
        if (self::$countries === null) {
            self::setFormats(include(__DIR__ . '/countries.php'));
        }

        // Ensure it's upper cased
        $country = strtoupper($country);

        // Return international format for missing
        if (array_key_exists($country, self::$countries) === false) {
            return '%N%n%O%n%A%n%C, %S %Z %R';
        }

        return self::$countries[$country];
    }

    /**
     * Set country formats.
     *
     * @param array|null $countries
     */
    public static function setFormats($countries)
    {
        self::$countries = $countries;
    }

    /**
     * Get country formats.
     *
     * @return array|null
     */
    public static function getFormats()
    {
        return self::$countries;
    }

    /**
     * Get item property HTML attribute.
     *
     * @param string $key
     *
     * @return string
     */
    protected static function getItemProp($key)
    {
        if ($prop = self::$itemprops[$key]) {
            return " itemprop=\"{$prop}\"";
        }

        return '';
    }
}
