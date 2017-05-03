<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TraineeNotif Entity
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *         @ORM\Table(name="traineenotifs")
 *         @ORM\Entity(repositoryClass="Ilcfrance\Worldspeak\Shared\DataBundle\Repository\TraineeNotifRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_traineenotif")
 */
class TraineeNotif
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
	const TYPE_TXT_SURVEYBEGIN = 10;

	/**
	 *
	 * @var integer
	 */
	const TYPE_TXT_SURVEYEND = 11;

	/**
	 *
	 * @var integer
	 */
	const TYPE_EMAIL_24H_BEFORE_COURS = 20;

	/**
	 *
	 * @var integer
	 */
	const TYPE_EMAIL_15D_AFTER_COURS = 21;

	/**
	 *
	 * @var integer
	 */
	const TYPE_EMAIL_30D_AFTER_COURS = 22;

	/**
	 *
	 * @var integer
	 */
	const TYPE_EMAIL_SURVEYBEGIN = 23;

	/**
	 *
	 * @var integer
	 */
	const TYPE_EMAIL_SURVEYEND = 24;

	/**
	 *
	 * @var guid @ORM\Column(name="id", type="guid", nullable=false)
	 *      @ORM\Id
	 *      @ORM\GeneratedValue(strategy="UUID")
	 */
	protected $id;

	/**
	 *
	 * @var Trainee @ORM\ManyToOne(targetEntity="Trainee", inversedBy="notifications")
	 *      @ORM\JoinColumns({@ORM\JoinColumn(name="trainee", referencedColumnName="id")})
	 */
	protected $trainee;

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
	 * @var TimeCredit @ORM\ManyToOne(targetEntity="TimeCredit", inversedBy="traineeNotifs")
	 *      @ORM\JoinColumns({@ORM\JoinColumn(name="timecredit", referencedColumnName="id")})
	 */
	protected $timeCredit;

	/**
	 *
	 * @var Cours @ORM\ManyToOne(targetEntity="Cours", inversedBy="traineeNotifs")
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
	 * Set trainee
	 *
	 * @param Trainee $trainee
	 *
	 * @return TraineeNotif
	 */
	public function setTrainee($trainee)
	{
		$this->trainee = $trainee;

		return $this;
	}

	/**
	 * Get trainee
	 *
	 * @return Trainee
	 */
	public function getTrainee()
	{
		return $this->trainee;
	}

	/**
	 * Set dtCrea
	 *
	 * @param \DateTime $dtCrea
	 *
	 * @return TraineeNotif
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
	 * @return TraineeNotif
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
	 * @return TraineeNotif
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
	 * @return TraineeNotif
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
	 * @return TraineeNotif
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
	 * @return TraineeNotif
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
