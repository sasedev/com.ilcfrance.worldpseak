<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Entity;

use Ilcfrance\Worldspeak\Shared\DataBundle\Model\FCEventClass;
use Doctrine\ORM\Mapping as ORM;

/**
 * TeacherAvailability Entity
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *         @ORM\Table(name="teacheravailabilities")
 *         @ORM\Entity(
 *         repositoryClass="Ilcfrance\Worldspeak\Shared\DataBundle\Repository\TeacherAvailabilityRepository")
 *         @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_teacheravailability")
 *         @ORM\HasLifecycleCallbacks
 */
class TeacherAvailability extends FCEventClass
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
	 * @var Teacher @ORM\ManyToOne(targetEntity="Teacher",
	 *      inversedBy="availabilities")
	 *      @ORM\JoinColumns({
	 *      @ORM\JoinColumn(name="teacher", referencedColumnName="id")
	 *      })
	 */
	protected $teacher;

	/**
	 *
	 * @var \DateTime @ORM\Column(name="dtbegin", type="datetimetz",
	 *      nullable=false)
	 */
	protected $dtStart;

	/**
	 *
	 * @var \DateTime @ORM\Column(name="dtend", type="datetimetz",
	 *      nullable=false)
	 */
	protected $dtEnd;

	/**
	 *
	 * @var \DateTime @ORM\Column(name="dtcrea", type="datetimetz",
	 *      nullable=true)
	 */
	protected $dtCrea;

	/**
	 * Constructor
	 *
	 * @param Teacher $teacher
	 */
	public function __construct(Teacher $teacher = null)
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
	 * Set teacher
	 *
	 * @param Teacher $teacher
	 *
	 * @return TeacherAvailability
	 */
	public function setTeacher(Teacher $teacher)
	{
		$this->teacher = $teacher;

		return $this->teacher;
	}

	/**
	 * Get teacher
	 *
	 * @return Teacher
	 */
	public function getTeacher()
	{
		return $this->teacher;
	}

	/**
	 * Set dtStart
	 *
	 * @param \DateTime $dtStart
	 *
	 * @return TeacherAvailabilty
	 */
	public function setDtStart(\DateTime $dtStart)
	{
		$this->dtStart = $dtStart;

		return $this;
	}

	/**
	 * Get dtStart
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
	 * @return TeacherAvailabilty
	 */
	public function setDtEnd(\DateTime $dtEnd)
	{
		$this->dtEnd = $dtEnd;

		return $this;
	}

	/**
	 * Get dtEnd
	 *
	 * @return \DateTime
	 */
	public function getDtEnd()
	{
		return $this->dtEnd;
	}

	/**
	 * Set dtcrea
	 *
	 * @param \DateTime $dtcrea
	 *
	 * @return TeacherAvailabilty
	 */
	public function setDtCrea(\DateTime $dtcrea)
	{
		$this->dtCrea = $dtcrea;

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
