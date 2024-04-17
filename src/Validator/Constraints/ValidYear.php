<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class ValidYear
 */
class ValidYear extends Constraint
{
    public $message = 'This is not a valid year.';
}
