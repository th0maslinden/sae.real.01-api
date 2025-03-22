<?php

namespace App\Validator;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class IsAuthenticatedUserValidator extends ConstraintValidator
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof IsAuthenticatedUser) {
            throw new UnexpectedValueException($constraint, IsAuthenticatedUser::class);
        }

        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return; // L'utilisateur n'est pas connecté, on ne fait rien
        }

        if ($value !== $user) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
