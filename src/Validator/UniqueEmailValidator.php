<?php
// UniqueEmailValidator.php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\AssociationRepository;

class UniqueEmailValidator extends ConstraintValidator
{
    private $associationRepository;

    public function __construct(AssociationRepository $associationRepository)
    {
        $this->associationRepository = $associationRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint UniqueEmail */

        if (!$this->associationRepository->isEmailUnique($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
