<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute] // Supprime @Annotation si présent
class IsAuthenticatedUser extends Constraint
{
    public string $message = 'You can only create a rating for yourself.';
}
