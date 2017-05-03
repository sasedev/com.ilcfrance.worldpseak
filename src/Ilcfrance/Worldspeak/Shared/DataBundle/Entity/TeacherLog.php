<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TeacherLog Entity
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *         @ORM\Table(name="teacherlogs")
 *         @ORM\Entity(repositoryClass="Ilcfrance\Worldspeak\Shared\DataBundle\Repository\TeacherLogRepository")
 *         @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_teacherlog")
 *         @ORM\HasLifecycleCallbacks
 */
class TeacherLog
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
	 * @var Teacher @ORM\ManyToOne(targetEntity="Teacher", inversedBy="logs")
	 *      @ORM\JoinColumns({@ORM\JoinColumn(name="teacher", referencedColumnName="id")})
	 */
	protected $teacher;

	/**
	 *
	 * @var \DateTime @ORM\Column(name="dtcrea", type="datetimetz", nullable=true)
	 */
	protected $dtCrea;

	/**
	 *
	 * @var string @ORM\Column(name="msg", type="text", nullable=true)
	 */
	protected $msg;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->dtCrea = new \DateTime('now');
	}

	/**
	 *
	 * @return guid
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set Teacher
	 *
	 * @param Teacher $teacher
	 *
	 * @return TeacherLog
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
	 * @return TeacherLog
	 */
	public function setDtCrea(\DateTime $dtCrea)
	{
		$this->dtCrea = $dtCrea;

		return $this;
	}

	/**
	 * Get dtCrea
	 *
	 * @return DateTime
	 */
	public function getDtCrea()
	{
		return $this->dtCrea;
	}

	/**
	 * Set msg
	 *
	 * @param string $msg
	 *
	 * @return TeacherLog
	 */
	public function setMsg($msg)
	{
		$this->msg = $msg;

		return $this;
	}

	/**
	 * Get msg
	 *
	 * @return string
	 */
	public function getMsg()
	{
		return $this->msg;
	}
}
