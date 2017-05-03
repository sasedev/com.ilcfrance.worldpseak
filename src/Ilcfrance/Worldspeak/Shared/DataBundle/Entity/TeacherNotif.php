<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TeacherNotif Entity
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *         @ORM\Table(name="teachernotifs")
 *         @ORM\Entity(repositoryClass="Ilcfrance\Worldspeak\Shared\DataBundle\Repository\TeacherNotifRepository")
 *         @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_teachernotif")
 *         @ORM\HasLifecycleCallbacks
 */
class TeacherNotif
{

	/**
	 *
	 * @var integer
	 */
	const PENDING = 1;

	/**
	 *
	 * @var integer
	 */
	const DONE = 2;

	/**
	 *
	 * @var integer
	 */
	const ERROR = 3;

	/**
	 *
	 * @var integer
	 */
	const TYPE_TXT_COURS_EDIT = 10;

	/**
	 *
	 * @var integer
	 */
	const TYPE_TXT_TIMECREDIT_EDIT = 11;

	/**
	 *
	 * @var integer
	 */
	const TYPE_EMAIL_COURS_EDIT = 20;

	/**
	 *
	 * @var integer
	 */
	const TYPE_EMAIL_TIMECREDIT_EDIT = 21;

	/**
	 *
	 * @var guid @ORM\Column(name="id", type="guid", nullable=false)
	 *      @ORM\Id
	 *      @ORM\GeneratedValue(strategy="UUID")
	 */
	protected $id;

	/**
	 *
	 * @var Teacher @ORM\ManyToOne(targetEntity="Teacher", inversedBy="notifications")
	 *      @ORM\JoinColumns({@ORM\JoinColumn(name="teacher", referencedColumnName="id")})
	 */
	protected $teacher;

	/**
	 *
	 * @var \DateTime @ORM\Column(name="dtcrea", type="datetimetz",
	 *      nullable=true)
	 */
	protected $dtCrea;

	/**
	 *
	 * @var \DateTime @ORM\Column(name="dtstartnotif", type="datetimetz",
	 *      nullable=false)
	 */
	protected $dtStart;

	/**
	 *
	 * @var integer @ORM\Column(name="typeid", type="integer", nullable=false)
	 */
	protected $type;

	/**
	 *
	 * @var integer @ORM\Column(name="status", type="integer", nullable=false)
	 */
	protected $status;

	/**
	 *
	 * @var TimeCredit @ORM\ManyToOne(targetEntity="TimeCredit", inversedBy="teacherNotifs")
	 *      @ORM\JoinColumns({@ORM\JoinColumn(name="timecredit", referencedColumnName="id")})
	 */
	protected $timeCredit;

	/**
	 *
	 * @var Cours @ORM\ManyToOne(targetEntity="Cours", inversedBy="teacherNotifs")
	 *      @ORM\JoinColumns({@ORM\JoinColumn(name="cours", referencedColumnName="id")})
	 */
	protected $cours;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->dtCrea = new \DateTime('now');
		$this->status = self::PENDING;
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
	 * @return TeacherNotif
	 */
	public function setTeacher(Teacher $teacher)
	{
		$this->teacher = $teacher;

		return $this;
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
	 * Set dtCrea
	 *
	 * @param \DateTime $dtCrea
	 *
	 * @return TeacherNotif
	 */
	public function setDtCrea(\DateTime $dtCrea)
	{
		$this->dtCrea = $dtCrea;

		return $this;
	}

	/**
	 * Get dtCrea
	 *
	 * @return \DateTime
	 */
	public function getDtCrea()
	{
		return $this->dtCrea;
	}

	/**
	 * Set dtStart
	 *
	 * @param \DateTime $dtStart
	 *
	 * @return TeacherNotif
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
	 * Set type
	 *
	 * @param integer $type
	 *
	 * @return TeacherNotif
	 */
	public function setType($type)
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * Get type
	 *
	 * @return integer
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Set status
	 *
	 * @param integer $status
	 *
	 * @return TeacherNotif
	 */
	public function setStatus($status)
	{
		$this->status = $status;

		return $this;
	}

	/**
	 * Get status
	 *
	 * @return integer
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Set timeCredit
	 *
	 * @param TimeCredit $timeCredit
	 *
	 * @return TeacherNotif
	 */
	public function setTimeCredit(TimeCredit $timeCredit)
	{
		$this->timeCredit = $timeCredit;

		return $this;
	}

	/**
	 * Get timeCredit
	 *
	 * @return TimeCredit
	 */
	public function getTimeCredit()
	{
		return $this->timeCredit;
	}

	/**
	 * Set cours
	 *
	 * @param Cours $cours
	 *
	 * @return TeacherNotif
	 */
	public function setCours(Cours $cours = null)
	{
		$this->cours = $cours;

		return $this;
	}

	/**
	 * Get cours
	 *
	 * @return Cours
	 */
	public function getCours()
	{
		return $this->cours;
	}
}
