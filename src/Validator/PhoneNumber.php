<?php

namespace Eckinox\AddressBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PhoneNumber extends Constraint
{
    public string $message = 'phone_number.invalid_format';

    public bool $allowSuffix = false;

    public static function getPattern(bool $allowSuffix = false): string
    {
        // Optionnally starts with a country code, ex.: "+1" or "1"
        $pattern = "^(\\+?[0-9]{1,2}[\\s\\.-]?)?";

        // 3-digit area code
        $pattern .= "\\(?[0-9]{3}\\)?[\\s\\.-]?";

        // 3-digit telephone prefix (city code)
        $pattern .= "[0-9]{3}[\\s\\.-]?";

        // 4-digit line number
        $pattern .= '[0-9]{4}';

        if ($allowSuffix) {
            // Optionally end with notes, extension number, etc.
            $pattern .= "([\\s#].*)?";
        }

        $pattern .= '$';

        return $pattern;
    }
}
