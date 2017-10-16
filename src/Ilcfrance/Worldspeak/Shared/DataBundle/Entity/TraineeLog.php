<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * TraineeLog Entity
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *         @ORM\Table(name="traineelogs")
 *         @ORM\Entity(repositoryClass="Ilcfrance\Worldspeak\Shared\DataBundle\Repository\TraineeLogRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_TraineeLog")
 */
class TraineeLog
{

    /**
     *
     * @var string @ORM\Column(name="id", type="guid", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     *
     * @var Trainee @ORM\ManyToOne(targetEntity="Trainee", inversedBy="logs")
     *      @ORM\JoinColumns({@ORM\JoinColumn(name="trainee", referencedColumnName="id")})
     *      @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_TraineeLog_trainee")
     */
    protected $trainee;

    /**
     *
     * @var DateTime @ORM\Column(name="dtcrea", type="datetimetz", nullable=true)
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
        $this->dtCrea = new DateTime('now');
    }

    /**
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Trainee
     *
     * @param Trainee $trainee
     *
     * @return TraineeLog
     */
    public function setTrainee(Trainee $trainee)
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
     * @param DateTime $dtCrea
     *
     * @return TraineeLog
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
     * @return TraineeLog
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
