<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Locale Entity
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *         @ORM\Table(name="languages")
 *         @ORM\Entity(repositoryClass="Ilcfrance\Worldspeak\Shared\DataBundle\Repository\LocaleRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_Locale")
 *         @UniqueEntity(fields="prefix", message="Locale.prefix.unique")
 *         @Assert\Callback(callback="checkValidPrefix")
 */
class Locale
{

    /**
     *
     * @var string
     */
    const DIRECTION_LTR = "LTR";

    /**
     *
     * @var string
     */
    const DIRECTION_RTL = "RTL";

    /**
     *
     * @var integer
     */
    const STATUS_INACTIF = 1;

    /**
     *
     * @var integer
     */
    const STATUS_ACTIF = 2;

    /**
     *
     * @var string @ORM\Column(name="id", type="guid", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     *
     * @var string @ORM\Column(name="prefix", type="text", nullable=false)
     */
    protected $prefix;

    /**
     *
     * @var string @ORM\Column(name="name", type="text", nullable=true)
     *      @Assert\NotBlank(message="Locale.name.blank")
     */
    protected $name;

    /**
     *
     * @var string @ORM\Column(name="direction", type="text", nullable=false)
     *      @Assert\Choice(callback="choiceDirectionCallback")
     */
    protected $direction;

    /**
     *
     * @var integer @ORM\Column(name="status", type="smallint", nullable=false)
     *      @Assert\Choice(callback="choiceStatusCallback")
     */
    protected $status;

    /**
     * @ORM\OneToMany(targetEntity="Admin", mappedBy="preferedLocale")
     * @ORM\OrderBy({"username"="ASC"})
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_Locale_admins")
     */
    protected $admins;

    /**
     * @ORM\OneToMany(targetEntity="Teacher", mappedBy="preferedLocale")
     * @ORM\OrderBy({"username"="ASC"})
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_Locale_teachers")
     */
    protected $teachers;

    /**
     * @ORM\OneToMany(targetEntity="Trainee", mappedBy="preferedLocale")
     * @ORM\OrderBy({"username"="ASC"})
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_Locale_trainees")
     */
    protected $trainees;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->direction = self::DIRECTION_LTR;
        $this->status = self::STATUS_INACTIF;

        $this->admins = new ArrayCollection();
        $this->teachers = new ArrayCollection();
        $this->trainees = new ArrayCollection();
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
     * Set prefix
     *
     * @param string $prefix
     *
     * @return Locale
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Get prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Locale
     */
    public function setName($name)
    {
        $this->name = trim($name);

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set direction
     *
     * @param string $direction
     *
     * @return Locale
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * Get direction
     *
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Locale
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
     * Add Admin
     *
     * @param Admin $admin
     *
     * @return Locale
     */
    public function addAdmin(Admin $admin)
    {
        $this->admins[] = $admin;

        return $this;
    }

    /**
     * Remove Admin
     *
     * @param Admin $admin
     *
     * @return Locale
     */
    public function removeAdmin(Admin $admin)
    {
        $this->admins->removeElement($admin);

        return $this;
    }

    /**
     * Set Admin Collection
     *
     * @param Collection $admins
     *
     * @return Locale
     */
    public function setAdmins(Collection $admins)
    {
        $this->admins = $admins;

        return $this;
    }

    /**
     * Get Admin ArrayCollection
     *
     * @return ArrayCollection
     */
    public function getAdmins()
    {
        return $this->admins;
    }

    /**
     * Add Teacher
     *
     * @param Teacher $teacher
     *
     * @return Locale
     */
    public function addTeacher(Teacher $teacher)
    {
        $this->teachers[] = $teacher;

        return $this;
    }

    /**
     * Remove Teacher
     *
     * @param Teacher $teacher
     *
     * @return Locale
     */
    public function removeTeacher(Teacher $teacher)
    {
        $this->teachers->removeElement($teacher);

        return $this;
    }

    /**
     * Set Teacher collection
     *
     * @param Collection $teachers
     *
     * @return Locale
     */
    public function setTeachers(Collection $teachers)
    {
        $this->teachers = $teachers;

        return $this;
    }

    /**
     * Get Teacher ArrayCollection
     *
     * @return ArrayCollection
     */
    public function getTeachers()
    {
        return $this->teachers;
    }

    /**
     * Add Trainee
     *
     * @param Trainee $trainee
     *
     * @return Locale
     */
    public function addTrainee(Trainee $trainee)
    {
        $this->trainees[] = $trainee;

        return $this;
    }

    /**
     * Remove Trainee
     *
     * @param Trainee $trainee
     *
     * @return Locale
     */
    public function removeTrainee(Trainee $trainee)
    {
        $this->trainees->removeElement($trainee);

        return $this;
    }

    /**
     * Set Trainee Collection
     *
     * @param Collection $trainees
     *
     * @return Locale
     */
    public function setTrainees(Collection $trainees)
    {
        $this->trainees = $trainees;

        return $this;
    }

    /**
     * Get Trainee ArrayCollection
     *
     * @return ArrayCollection
     */
    public function getTrainees()
    {
        return $this->trainees;
    }

    /**
     * Choice Form direction
     *
     * @return array
     */
    public static function choiceDirection()
    {
        return array(
            'Locale.direction.choice.' . self::DIRECTION_LTR => self::DIRECTION_LTR,
            'Locale.direction.choice.' . self::DIRECTION_RTL => self::DIRECTION_RTL
        );
    }

    /**
     * Choice Validator direction
     *
     * @return array
     */
    public static function choiceDirectionCallback()
    {
        return array(
            self::DIRECTION_LTR,
            self::DIRECTION_RTL
        );
    }

    /**
     * Choice Form status
     *
     * @return array
     */
    public static function choiceStatus()
    {
        return array(
            'Locale.status.choice.' . self::STATUS_INACTIF => self::STATUS_INACTIF,
            'Locale.status.choice.' . self::STATUS_ACTIF => self::STATUS_ACTIF
        );
    }

    /**
     * Choice Validator status
     *
     * @return array
     */
    public static function choiceStatusCallback()
    {
        return array(
            self::STATUS_INACTIF,
            self::STATUS_ACTIF
        );
    }

    /**
     * Validator prefix
     *
     * @param ExecutionContextInterface $context
     */
    public function checkValidPrefix($context)
    {
        $sfLocales = Intl::getLocaleBundle()::getLocales();
        if (!in_array($this->getPrefix(), $sfLocales)) {
            $context->addViolationAt('prefix', 'Locale.prefix.invalid', array(), null);
        }
    }
}
