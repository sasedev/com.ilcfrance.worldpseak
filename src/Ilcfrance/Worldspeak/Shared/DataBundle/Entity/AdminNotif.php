<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * AdminNotif Entity
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *         @ORM\Table(name="adminnotifs")
 *         @ORM\Entity(repositoryClass="Ilcfrance\Worldspeak\Shared\DataBundle\Repository\AdminNotifRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_AdminNotif")
 */
class AdminNotif
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
    const TYPE_TXT_40D_AFTER_COURS = 10;

    /**
     *
     * @var integer
     */
    const TYPE_TXT_TIMECREDIT_EDIT = 11;

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
     * @var DateTime @ORM\Column(name="dtstartnotif", type="datetimetz",
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
     * @var TimeCredit @ORM\ManyToOne(targetEntity="TimeCredit", inversedBy="adminNotifs")
     *      @ORM\JoinColumns({@ORM\JoinColumn(name="timecredit", referencedColumnName="id")})
     */
    protected $timeCredit;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dtCrea = new DateTime('now');
        $this->status = self::PENDING;
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
     * @return AdminNotif
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
     * Set dtStart
     *
     * @param DateTime $dtStart
     *
     * @return AdminNotif
     */
    public function setDtStart(\DateTime $dtStart)
    {
        $this->dtStart = $dtStart;

        return $this;
    }

    /**
     * Get dtStart
     *
     * @return DateTime
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
     * @return AdminNotif
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
     * @return AdminNotif
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
     * @return AdminNotif
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
}
