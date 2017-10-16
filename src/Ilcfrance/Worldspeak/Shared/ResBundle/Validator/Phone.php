<?php
namespace Ilcfrance\Worldspeak\Shared\ResBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Phone Constraint
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *         @Annotation
 */
class Phone extends Constraint
{

    public $message = 'the "%string%" has not a valid EPP phone number format (+CCC.NNNNNNNNNNxEEEE)';

    public $format = "/^\+[0-9]{1,3}\.[0-9]{4,14}(?:x.+)?$/";

    /**
     * Get requiredOptions
     *
     * @return array
     */
    public function requiredOptions()
    {
        return array();
    }

    /**
     * Get defaultOption
     *
     * @return string
     */
    public function defaultOption()
    {
        return 'format';
    }

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
