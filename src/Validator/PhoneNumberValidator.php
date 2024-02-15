<?php

namespace Eckinox\AddressBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Validates that the provided phone number is a valid phone number.
 *
 * To be valid, a phone number must have at least:
 * - the area code (ex.: `418`)
 * - the telephone prefix (ex.: `321`)
 * - the line number (ex.: `9012`)
 *
 * Numbers may be prefixed with a county code (ex.: `+1` or `1`).
 *
 * The following formats, or combinations of these formats, are accepted:
 * - Raw numbers (`4183219012`)
 * - Spaced out parts (`418 321 9012`)
 * - Kebab case numbers (`418-321-9012`)
 * - Dot notation (`418.321.9012`)
 * - Parenthesized area code (`(418) 321-9012`)
 * - Any combination of these (ex.: `+1.418.321-9012` )
 *
 * The number may be followed by notes, extensions, or anything other string without any validation.
 */
class PhoneNumberValidator extends ConstraintValidator
{
    /**
     * @param string|null $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PhoneNumber) {
            throw new UnexpectedTypeException($constraint, PhoneNumber::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) to take care of that
        if ($value === null || $value === '') {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $numberIsValid = $this->isNumberValid($value, $constraint->allowSuffix);

        if (!$numberIsValid) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }

    public function isNumberValid(string $value, bool $allowSuffix): bool
    {
        return preg_match('~' . PhoneNumber::getPattern($allowSuffix) . '~', $value) === 1;
    }
}
