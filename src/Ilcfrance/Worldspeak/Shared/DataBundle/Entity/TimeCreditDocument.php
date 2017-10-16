<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ilcfrance\Worldspeak\Shared\DataBundle\Document\TeachingResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * TimeCreditDocument Entity
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *         @ORM\Table(name="timecreditdocuments")
 *         @ORM\Entity(
 *         repositoryClass="Ilcfrance\Worldspeak\Shared\DataBundle\Repository\TimeCreditDocumentRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_TimeCreditDocument")
 *         @Assert\Callback(callback="checkValidContent")
 */
class TimeCreditDocument
{

    /**
     *
     * @var integer
     */
    const NOTIFYBYMAIL_NOTSENT = 1;

    /**
     *
     * @var integer
     */
    const NOTIFYBYMAIL_SENT = 2;

    /**
     *
     * @var integer
     */
    const NOTIFYBYMAIL_DISABLED = 3;

    /**
     *
     * @var string @ORM\Column(name="id", type="guid", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     *
     * @var DateTime @ORM\Column(name="dtcrea", type="datetimetz",
     *      nullable=true)
     */
    protected $dtCrea;

    /**
     *
     * @var TimeCredit @ORM\ManyToOne(targetEntity="TimeCredit",
     *      inversedBy="documents")
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="timecredit", referencedColumnName="id")
     *      })
     *      @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_TimeCreditDocument_timeCredit")
     */
    protected $timeCredit;

    /**
     *
     * @var TeachingResource @ORM\Column(name="teachingresource",
     *      type="TeachingResource",
     *      nullable=true)
     */
    protected $teachingResource;

    /**
     *
     * @var string @ORM\Column(name="msg", type="text", nullable=true)
     */
    protected $msg;

    /**
     *
     * @var integer @ORM\Column(name="creationmail", type="integer", nullable=false)
     *      @Assert\Choice(callback="choiceNotifyByMailCallback", groups={"admCreation", "teacherCreation"})
     */
    protected $notifyByMail;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dtCrea = new DateTime('now');
        $this->notifyByMail = self::NOTIFYBYMAIL_NOTSENT;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dtCrea
     *
     * @param DateTime $dtCrea
     *
     * @return TimeCreditDocument
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
     * Set timeCredit
     *
     * @param TimeCredit $timeCredit
     *
     * @return TimeCreditDocument
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
     * Set teachingResource
     *
     * @param TeachingResource $teachingResource
     *
     * @return TimeCreditDocument
     */
    public function setTeachingResource(TeachingResource $teachingResource = null)
    {
        $this->teachingResource = $teachingResource;

        return $this;
    }

    /**
     * Get teachingResource
     *
     * @return TeachingResource
     */
    public function getTeachingResource()
    {
        return $this->teachingResource;
    }

    /**
     * Set msg
     *
     * @param string $msg
     *
     * @return TimeCreditDocument
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
     * Set notifyByMail
     *
     * @param integer $notifyByMail
     *
     * @return TimeCreditDocument
     */
    public function setNotifyByMail($notifyByMail)
    {
        $this->notifyByMail = $notifyByMail;

        return $this;
    }

    /**
     * Get notifyByMail
     *
     * @return integer
     */
    public function getNotifyByMail()
    {
        return $this->notifyByMail;
    }

    /**
     * Choice Form notifyByMail
     *
     * @return array
     */
    public static function choiceNotifyByMail()
    {
        return array(
            'TimeCreditDocument.notifyByMail.choice.' . self::NOTIFYBYMAIL_NOTSENT => self::NOTIFYBYMAIL_NOTSENT,
            'TimeCreditDocument.notifyByMail.choice.' . self::NOTIFYBYMAIL_SENT => self::NOTIFYBYMAIL_SENT,
            'TimeCreditDocument.notifyByMail.choice.' . self::NOTIFYBYMAIL_DISABLED => self::NOTIFYBYMAIL_DISABLED
        );
    }

    /**
     * Choice Form notifyByMail for Teachers
     *
     * @return array
     */
    public static function choiceNotifyByMailTeacher()
    {
        return array(
            'TimeCreditDocument.notifyByMail.choice.' . self::NOTIFYBYMAIL_SENT => self::NOTIFYBYMAIL_SENT
        );
    }

    /**
     * Choice Validator notifyByMail
     *
     * @return array
     */
    public static function choiceNotifyByMailCallback()
    {
        return array(
            self::NOTIFYBYMAIL_NOTSENT,
            self::NOTIFYBYMAIL_SENT,
            self::NOTIFYBYMAIL_DISABLED
        );
    }

    /**
     * Validator content
     *
     * @param ExecutionContextInterface $context
     */
    public function checkValidContent($context)
    {
        if (($this->getMsg() == null || trim($this->getMsg()) == '') && $this->getTeachingResource() == null) {
            $context->addViolationAt('teachingResource', 'TimeCreditDocument.teachingResource.null', array(), null);
            $context->addViolationAt('msg', 'TimeCreditDocument.msg.null', array(), null);
        }
    }
}
