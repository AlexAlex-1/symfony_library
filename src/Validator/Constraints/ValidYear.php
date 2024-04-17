<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * Class ValidYear
 */
class ValidYear extends Constraint
{
    public $message = 'This is not a valid year.';
}
