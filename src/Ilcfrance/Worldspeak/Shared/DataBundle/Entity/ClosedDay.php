<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ClosedDay Entity
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *         @ORM\Table(name="closeddays")
 *         @ORM\Entity(repositoryClass="Ilcfrance\Worldspeak\Shared\DataBundle\Repository\ClosedDayRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_closedday")
 *         @UniqueEntity(fields="day", message="ClosedDay.unique")
 */
class ClosedDay
{

	/**
	 *
	 * @var guid @ORM\Column(name="id", type="guid", nullable=false)
	 *      @ORM\Id
	 *      @ORM\GeneratedValue(strategy="UUID")
	 */
	protected $id;

	/**
	 *
	 * @var \DateTime @ORM\Column(type="date", name="day")
	 */
	protected $day;

	/**
	 *
	 * @var \DateTime @ORM\Column(name="dtcrea", type="datetimetz",
	 *      nullable=true)
	 */
	protected $dtCrea;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->dtCrea = new \DateTime('now');
	}

	/**
	 * Get id
	 *
	 * @return guid
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set day
	 *
	 * @param \DateTime $day
	 *
	 * @return ClosedDay
	 */
	public function setDay(\DateTime $day)
	{
		$day->setTime(0, 0, 0);
		$this->day = $day;

		return $this;
	}

	/**
	 * Get day
	 *
	 * @return \DateTime
	 */
	public function getDay()
	{
		return $this->day;
	}

	/**
	 * Set dtCrea
	 *
	 * @param \DateTime $dtCrea
	 *
	 * @return ClosedDay
	 */
	public function setDtCrea(\DateTime $dtCrea)
	{
		$this->dtCrea = $dtCrea;

		return $this;
	}

	/**
	 * Get dtcrea
	 *
	 * @return \DateTime
	 */
	public function getDtCrea()
	{
		return $this->dtCrea;
	}
}
