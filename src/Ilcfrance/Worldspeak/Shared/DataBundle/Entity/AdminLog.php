<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * AdminLog Entity
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *         @ORM\Table(name="administratorlogs")
 *         @ORM\Entity(repositoryClass="Ilcfrance\Worldspeak\Shared\DataBundle\Repository\AdminLogRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_AdminLog")
 */
class AdminLog
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
     * @var Admin @ORM\ManyToOne(targetEntity="Admin", inversedBy="logs")
     *      @ORM\JoinColumns({@ORM\JoinColumn(name="administrator", referencedColumnName="id")})
     *      @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_AdminLog_admin")
     */
    protected $admin;

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
     * Set Admin
     *
     * @param Admin $admin
     *
     * @return AdminLog
     */
    public function setAdmin(Admin $admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin
     *
     * @return Admin
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set dtCrea
     *
     * @param DateTime $dtCrea
     *
     * @return AdminLog
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
     * @return AdminLog
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
