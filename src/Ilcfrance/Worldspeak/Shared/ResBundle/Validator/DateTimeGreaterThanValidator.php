<?php
namespace Ilcfrance\Worldspeak\Shared\ResBundle\Validator;

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
	 * @inheritDoc
	 */
	protected function compareValues($value1, $value2)
	{
		if ($value2 instanceof \DateTime) {
			return $value1 > $value2;
		}
		return $value1 > new \DateTime($value2);
	}
}
