<?php
namespace Ilcfrance\Worldspeak\Trainee\FrontBundle\Model;

use Ilcfrance\Worldspeak\Shared\DataBundle\Model\FCEventClass;

/**
 * TeacherAvailability Intersections
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class AvailabilityIntersection extends FCEventClass
{

	/**
	 *
	 * @var integer
	 */
	protected $id;

	/**
	 *
	 * @var \DateTime
	 */
	protected $dtStart;

	/**
	 *
	 * @var \DateTime
	 */
	protected $dtEnd;

	/**
	 * Set id
	 *
	 * @param integer $id
	 *
	 * @return AvailabilityIntersection
	 */
	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set dtStart
	 *
	 * @param \DateTime $dtStart
	 *
	 * @return AvailabilityIntersection
	 */
	public function setDtStart(\DateTime $dtStart)
	{
		$this->dtStart = $dtStart;

		return $this;
	}

	/**
	 * (non-PHPdoc) @see \Ilcfrance\Worldspeak\Shared\DataBundle\Model\FCEventClass::getDtStart()
	 *
	 * @return \DateTime
	 */
	public function getDtStart()
	{
		return $this->dtStart;
	}

	/**
	 * Set dtEnd
	 *
	 * @param \DateTime $dtEnd
	 *
	 * @return AvailabilityIntersection
	 */
	public function setDtEnd($dtEnd)
	{
		$this->dtEnd = $dtEnd;

		return $this;
	}

	/**
	 * (non-PHPdoc) @see \Ilcfrance\Worldspeak\Shared\DataBundle\Model\FCEventClass::getDtEnd()
	 *
	 * @return \DateTime
	 */
	public function getDtEnd()
	{
		return $this->dtEnd;
	}
}
