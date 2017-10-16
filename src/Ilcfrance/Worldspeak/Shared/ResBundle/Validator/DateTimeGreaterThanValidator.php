<?php
namespace Ilcfrance\Worldspeak\Shared\ResBundle\Validator;

use DateTime;
use Symfony\Component\Validator\Constraints\AbstractComparisonValidator;

/**
 * DateTimeGreaterThan ConstraintValidator
 * Validates values are greater than the previous (>).
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class DateTimeGreaterThanValidator extends AbstractComparisonValidator
{

    /**
     *
     * {@inheritdoc}
     * @see AbstractComparisonValidator::compareValues()
     */
    protected function compareValues($value1, $value2)
    {
        if ($value2 instanceof DateTime) {
            return $value1 > $value2;
        }
        return $value1 > new DateTime($value2);
    }
}
