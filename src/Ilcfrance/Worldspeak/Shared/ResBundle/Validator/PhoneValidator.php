<?php
namespace Ilcfrance\Worldspeak\Shared\ResBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;

/**
 * PhoneValidator ConstraintValidator
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class PhoneValidator extends ConstraintValidator
{

    /**
     * Check if a Phonenumber is valid
     *
     * @param string $value
     * @param Constraint $constraint
     *
     * @return bool
     *
     */
    public function isValid($value, Constraint $constraint)
    {
        if (empty($value)) {
            return true;
        }

        $ret = $this->validateNumber($value, $constraint->format);

        if (! $ret) {
            $this->setMessage($constraint->message);
        }

        return $ret;
    }

    /**
     * Validate Phonenumber
     *
     * @param string $value
     * @param string $format
     *
     * @return bool
     *
     */
    protected function validateNumber($value, $format)
    {
        $ret = preg_match($format, trim($value));

        return ($ret !== false && $ret >= 1);
    }

    /**
     *
     * {@inheritdoc}
     * @see ConstraintValidatorInterface::validate()
     */
    public function validate($value, Constraint $constraint)
    {
        if (! empty($value)) {
            $matches = array();
            if (! preg_match($constraint->format, $value, $matches)) {
                $this->context->addViolation($constraint->message, array(
                    '%string%' => $value
                ));
            }
        }
    }
}
