<?php
namespace Ilcfrance\Worldspeak\Shared\ResBundle\Validator;

use Symfony\Component\Validator\Constraint;
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
     *
     * {@inheritdoc}
     * @see Constraint::validatedBy()
     */
    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }
}
