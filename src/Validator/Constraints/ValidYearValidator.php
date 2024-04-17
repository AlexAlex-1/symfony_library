<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ValidYearValidator
 */
class ValidYearValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!checkdate(1, 1, $value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
