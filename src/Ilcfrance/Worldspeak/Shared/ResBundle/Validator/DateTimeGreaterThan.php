<?php
namespace Ilcfrance\Worldspeak\Shared\ResBundle\Validator;

use Symfony\Component\Validator\Constraints\AbstractComparison;

/**
 * @Annotation
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class DateTimeGreaterThan extends AbstractComparison
{

	public $message = 'This value should be greater than {{ compared_value }}.';

	/**
	 * (non-PHPdoc)
	 *
	 * @see \Symfony\Component\Validator\Constraint::validatedBy()
	 *
	 * @return string Validator Class
	 */
	public function validatedBy()
	{
		return get_class($this) . 'Validator';
	}
}
