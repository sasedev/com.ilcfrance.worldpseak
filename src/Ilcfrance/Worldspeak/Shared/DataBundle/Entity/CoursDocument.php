<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Entity;

use Ilcfrance\Worldspeak\Shared\DataBundle\Document\TraineeFile;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CoursDocument Entity
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *         @ORM\Table(name="coursdocuments")
 *         @ORM\Entity(repositoryClass="Ilcfrance\Worldspeak\Shared\DataBundle\Repository\CoursDocumentRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_coursdocument")
 *         @Assert\Callback(callback="checkValidContent")
 */
class CoursDocument
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
	 * @var \DateTime @ORM\Column(name="dtcrea", type="datetimetz",
	 *      nullable=true)
	 */
	protected $dtCrea;

	/**
	 *
	 * @var Cours @ORM\ManyToOne(targetEntity="Cours", inversedBy="documents")
	 *      @ORM\JoinColumns({
	 *      @ORM\JoinColumn(name="cours", referencedColumnName="id")
	 *      })
	 */
	protected $cours;

	/**
	 *
	 * @var TraineeFile @ORM\Column(name="traineefile", type="TraineeFile", nullable=false)
	 */
	protected $traineeFile;

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
	 * Get id
	 *
	 * @return guid
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set dtcrea
	 *
	 * @param \DateTime $dtcrea
	 *
	 * @return TimeCreditDocument
	 */
	public function setDtCrea($dtcrea)
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

	/**
	 * Set cours
	 *
	 * @param Cours $cours
	 *
	 * @return CoursDocument
	 */
	public function setCours($cours)
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

	/**
	 * Set traineeFile
	 *
	 * @param TraineeFile $traineeFile
	 *
	 * @return CoursDocument
	 */
	public function setTraineeFile(TraineeFile $traineeFile)
	{
		$this->traineeFile = $traineeFile;

		return $this;
	}

	/**
	 * Get traineeFile
	 *
	 * @return TraineeFile
	 */
	public function getTraineeFile()
	{
		return $this->traineeFile;
	}

	/**
	 * Set msg
	 *
	 * @param string $msg
	 *
	 * @return CoursDocument
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

	/**
	 * Validator content
	 *
	 * @param ExecutionContextInterface $context
	 */
	public function checkValidContent($context)
	{
		if (($this->getMsg() == null || trim($this->getMsg()) == '') && $this->getTraineeFile() == null) {
			$context->addViolationAt('traineeFile', 'CoursDocument.traineeFile.null', array(), null);
			$context->addViolationAt('msg', 'CoursDocument.msg.null', array(), null);
		}
	}
}
