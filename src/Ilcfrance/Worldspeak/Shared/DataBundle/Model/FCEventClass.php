<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Model;

/**
 * Model Class Used for Jquery Fullcalendar 1.6
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
abstract class FCEventClass
{

	/**
	 * Date Start of Event
	 *
	 * @return \DateTime
	 */
	abstract protected function getDtStart();

	/**
	 * Date End of Event
	 *
	 * @return \DateTime
	 */
	abstract protected function getDtEnd();

	/**
	 * Date Start of Event in timestamp
	 *
	 * @return \DateTime
	 */
	public function getEvStart()
	{
		return $this->getDtStart()->getTimestamp();
	}

	/**
	 * Date End of Event in timestamp
	 *
	 * @return \DateTime
	 */
	public function getEvEnd()
	{
		return $this->getDtEnd()->getTimestamp();
	}

	/**
	 * is AllDay ? generaly false
	 *
	 * @return string
	 */
	public function getAllDay()
	{
		return 'false';
	}

	/**
	 * Background color of Event
	 *
	 * @return string
	 */
	public function getBackgroundColor()
	{
		return 'white';
	}

	/**
	 * Border color of Event
	 *
	 * @return string
	 */
	public function getBorderColor()
	{
		return 'grey';
	}

	/**
	 * Text color of Event
	 *
	 * @return string
	 */
	public function getTextColor()
	{
		return 'black';
	}
}
