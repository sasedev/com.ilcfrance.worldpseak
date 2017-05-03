<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Entity;

use Ilcfrance\Worldspeak\Shared\DataBundle\Document\TeacherAvatar;
use Ilcfrance\Worldspeak\Shared\ResBundle\Validator as ExtraAssert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Encoder\Pbkdf2PasswordEncoder;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Teacher Entity
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *         @ORM\Table(name="teachers")
 *         @ORM\Entity(repositoryClass="Ilcfrance\Worldspeak\Shared\DataBundle\Repository\TeacherRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_teacher")
 *         @UniqueEntity(fields="username", message="Teacher.username.unique")
 *         @UniqueEntity(fields="email", message="Teacher.email.unique")
 *         @UniqueEntity(fields="codeMA", message="Teacher.codeMA.unique")
 */
class Teacher implements UserInterface, \Serializable
{

	/**
	 *
	 * @var integer
	 */
	const SEXE_MISS = 1;

	/**
	 *
	 * @var integer
	 */
	const SEXE_MRS = 2;

	/**
	 *
	 * @var integer
	 */
	const SEXE_MR = 3;

	/**
	 *
	 * @var integer
	 */
	const LOCKOUT_UNLOCKED = 1;

	/**
	 *
	 * @var integer
	 */
	const LOCKOUT_LOCKED = 2;

	/**
	 *
	 * @var integer
	 */
	const REGISTERMAIL_NOTSENT = 1;

	/**
	 *
	 * @var integer
	 */
	const REGISTERMAIL_SENT = 2;

	/**
	 *
	 * @var integer
	 */
	const REGISTERMAIL_DISABLED = 3;

	/**
	 *
	 * @var integer
	 */
	const TYPE_INTERNAL = 1;

	/**
	 *
	 * @var integer
	 */
	const TYPE_EXTERNAL = 2;

	/**
	 *
	 * @var integer
	 */
	const FTYPE_EN = 100;

	/**
	 *
	 * @var integer
	 */
	const HEALTH_OK = 1;

	/**
	 *
	 * @var integer
	 */
	const HEALTH_BUGGY = 2;

	/**
	 *
	 * @var guid @ORM\Column(name="id", type="guid", nullable=false)
	 *      @ORM\Id
	 *      @ORM\GeneratedValue(strategy="UUID")
	 */
	protected $id;

	/**
	 *
	 * @var string @ORM\Column(name="codema", type="text", nullable=true)
	 */
	protected $codeMA;

	/**
	 *
	 * @var string @ORM\Column(name="username", type="text", nullable=false)
	 */
	protected $username;

	/**
	 *
	 * @var string @ORM\Column(name="email", type="text", nullable=true)
	 *      @Assert\Email(checkMX=true, checkHost=true, groups={"admRegistration", "admUpdateMail", "updateMail"})
	 */
	protected $email;

	/**
	 *
	 * @var string @ORM\Column(name="clearpassword", type="text", nullable=true)
	 *      @Assert\Length(min="8", max="250", groups={"updatePass"})
	 */
	protected $clearPassword;

	/**
	 *
	 * @var string @ORM\Column(name="passwd", type="text", nullable=true)
	 */
	protected $password;

	/**
	 *
	 * @var string @ORM\Column(name="salt", type="text", nullable=true)
	 */
	protected $salt;

	/**
	 *
	 * @var string @ORM\Column(name="recoverycode", type="text", nullable=true)
	 */
	protected $recoveryCode;

	/**
	 *
	 * @var \DateTime @ORM\Column(name="recoveryexpiration", type="datetimetz",
	 *      nullable=true)
	 */
	protected $recoveryExpiration;

	/**
	 *
	 * @var integer @ORM\Column(name="locked", type="smallint", nullable=false)
	 *      @Assert\Choice(callback="choiceLockoutCallback", groups={"admRegistration","admLockout"})
	 */
	protected $lockout;

	/**
	 *
	 * @var \DateTime @ORM\Column(name="dtcrea", type="datetimetz",
	 *      nullable=true)
	 */
	protected $dtCrea;

	/**
	 *
	 * @var integer @ORM\Column(name="logins", type="bigint", nullable=false)
	 */
	protected $logins;

	/**
	 *
	 * @var \DateTime @ORM\Column(name="lastlogin", type="datetimetz",
	 *      nullable=true)
	 */
	protected $lastLogin;

	/**
	 *
	 * @var \DateTime @ORM\Column(name="lastactivity", type="datetimetz",
	 *      nullable=true)
	 */
	protected $lastActivity;

	/**
	 *
	 * @var string @ORM\Column(name="lastname", type="text", nullable=true)
	 */
	protected $lastName;

	/**
	 *
	 * @var string @ORM\Column(name="firstname", type="text", nullable=true)
	 */
	protected $firstName;

	/**
	 *
	 * @var integer @ORM\Column(name="sexe", type="smallint", nullable=true)
	 *      @Assert\Choice(callback="choiceSexeCallback",
	 *      groups={"admRegistration","admProfile", "profile"})
	 */
	protected $sexe;

	/**
	 *
	 * @var \DateTime @ORM\Column(name="birthday", type="date", nullable=true)
	 *      @Assert\Date(groups={"admProfile", "profile"})
	 */
	protected $birthday;

	/**
	 *
	 * @var string @ORM\Column(name="address", type="text", nullable=true)
	 */
	protected $address;

	/**
	 *
	 * @var string @ORM\Column(name="country", type="text", nullable=true)
	 *      @Assert\Country(groups={"admProfile", "profile"})
	 */
	protected $country;

	/**
	 *
	 * @var string @ORM\Column(name="phone", type="text", nullable=true)
	 *      @ExtraAssert\Phone(message="_phone.invalid", groups={"admProfile",
	 *      "profile"})
	 */
	protected $phone;

	/**
	 *
	 * @var string @ORM\Column(name="mobile", type="text", nullable=true)
	 *      @ExtraAssert\Phone(message="_phone.invalid", groups={"admProfile",
	 *      "profile"})
	 */
	protected $mobile;

	/**
	 *
	 * @var string @ORM\Column(name="coursphone", type="text", nullable=true)
	 *      @ExtraAssert\Phone(message = "_phone.invalid",
	 *      groups={"admRegistration", "admUpdateCoursPhone", "teacherCoursPhone"})
	 */
	protected $coursPhone;

	/**
	 *
	 * @var Locale @ORM\ManyToOne(targetEntity="Locale", inversedBy="teachers")
	 *      @ORM\JoinColumns({
	 *      @ORM\JoinColumn(name="preferedlang", referencedColumnName="id")
	 *      })
	 */
	protected $preferedLocale;

	/**
	 *
	 * @var TeacherAvatar @ORM\Column(name="avatar", type="TeacherAvatar",
	 *      nullable=true)
	 */
	protected $avatar;

	/**
	 *
	 * @var integer @ORM\Column(name="registerMail", type="smallint",
	 *      nullable=false)
	 *      @Assert\Choice(callback="choiceRegisterMailCallback",
	 *      groups={"admRegistration","admProfile"})
	 */
	protected $registerMail;

	/**
	 *
	 * @var integer @ORM\Column(name="type", type="integer", nullable=false)
	 *      @Assert\Choice(callback="choiceTypeCallback",
	 *      groups={"admRegistration","admUpdateType"})
	 */
	protected $type;

	/**
	 *
	 * @var integer @ORM\Column(name="ftype", type="integer", nullable=false)
	 *      @Assert\Choice(callback="choiceFtypeCallback",
	 *      groups={"admRegistration","admUpdateFtype",
	 *      "profile"})
	 */
	protected $ftype;

	/**
	 *
	 * @var integer @ORM\Column(name="buggy", type="integer", nullable=false)
	 */
	protected $buggy;

	/**
	 *
	 * @var Collection @ORM\ManyToMany(targetEntity="Role",
	 *      inversedBy="teachers")
	 *      @ORM\JoinTable(name="teachers_roles",
	 *      joinColumns={
	 *      @ORM\JoinColumn(name="teacher", referencedColumnName="id")
	 *      },
	 *      inverseJoinColumns={
	 *      @ORM\JoinColumn(name="role", referencedColumnName="id")
	 *      }
	 *      )
	 */
	protected $teacherRoles;

	/**
	 *
	 * @var Collection @ORM\OneToMany(targetEntity="TeacherAvailability",
	 *      mappedBy="teacher")
	 *      @ORM\OrderBy({"dtStart"="ASC"})
	 */
	protected $availabilities;

	/**
	 *
	 * @var Collection @ORM\OneToMany(targetEntity="Cours", mappedBy="teacher")
	 *      @ORM\OrderBy({"dtStart"="ASC"})
	 */
	protected $courses;

	/**
	 *
	 * @var Collection @ORM\OneToMany(targetEntity="TeacherNotif", mappedBy="teacher")
	 *      @ORM\OrderBy({"dtStart"="DESC"})
	 */
	protected $notifications;

	/**
	 *
	 * @var Collection @ORM\OneToMany(targetEntity="TeacherLog", mappedBy="teacher")
	 *      @ORM\OrderBy({"dtCrea"="DESC"})
	 */
	protected $logs;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->dtCrea = new \DateTime('now');
		$this->lockout = self::LOCKOUT_UNLOCKED;
		$this->registerMail = self::REGISTERMAIL_NOTSENT;
		$this->logins = 0;
		$this->setSalt(md5(uniqid(null, true)));
		$this->type = self::TYPE_INTERNAL;
		$this->ftype = self::FTYPE_EN;

		$this->buggy = self::HEALTH_OK;

		$this->teacherRoles = new ArrayCollection();
		$this->availabilities = new ArrayCollection();
		$this->courses = new ArrayCollection();
		$this->notifications = new ArrayCollection();
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
	 * Set codeMA
	 *
	 * @param string $codeMA
	 *
	 * @return Teacher
	 */
	public function setCodeMA($codeMA)
	{
		$this->codeMA = $codeMA;

		return $this;
	}

	/**
	 * Get codeMA
	 *
	 * @return string
	 */
	public function getCodeMA()
	{
		return $this->codeMA;
	}

	/**
	 * Set username
	 *
	 * @param string $username
	 *
	 * @return Teacher
	 */
	public function setUsername($username)
	{
		$this->username = trim(strtolower($username));

		return $this;
	}

	/**
	 * Get username
	 * (non-PHPdoc) @see
	 * \Symfony\Component\Security\Core\User\UserInterface::getUsername()
	 *
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Set email
	 *
	 * @param string $email
	 *
	 * @return Teacher
	 */
	public function setEmail($email)
	{
		$this->email = trim(strtolower($email));

		return $this;
	}

	/**
	 * Get email
	 *
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Set clearPassword
	 *
	 * @param string $clearPassword
	 *
	 * @return Teacher
	 */
	public function setClearPassword($clearPassword)
	{
		$this->clearPassword = $clearPassword;

		return $this->setPassword($clearPassword);
	}

	/**
	 * Get clearPassword
	 *
	 * @return string
	 */
	public function getClearPassword()
	{
		return $this->clearPassword;
	}

	/**
	 * Set password
	 *
	 * @param string $password
	 *
	 * @return Teacher
	 */
	public function setPassword($password)
	{
		$encoder = new Pbkdf2PasswordEncoder('sha512', true, 1000);
		$this->password = $encoder->encodePassword($password, $this->getSalt());

		return $this;
	}

	/**
	 * Get password
	 * (non-PHPdoc) @see
	 * \Symfony\Component\Security\Core\User\UserInterface::getPassword()
	 *
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Set salt
	 *
	 * @param string $salt
	 *
	 * @return Teacher
	 */
	public function setSalt($salt)
	{
		$this->salt = $salt;

		return $this;
	}

	/**
	 * Get salt
	 * (non-PHPdoc) @see
	 * \Symfony\Component\Security\Core\User\UserInterface::getSalt()
	 *
	 * @return string
	 */
	public function getSalt()
	{
		return $this->salt;
	}

	/**
	 * Set recoveryCode
	 *
	 * @param string $recoveryCode
	 *
	 * @return Teacher
	 */
	public function setRecoveryCode($recoveryCode)
	{
		$this->recoveryCode = urlencode(base64_encode($recoveryCode));

		return $this;
	}

	/**
	 * Get recoveryCode
	 *
	 * @return string
	 */
	public function getRecoveryCode()
	{
		return $this->recoveryCode;
	}

	/**
	 * Set recoveryExpiration
	 *
	 * @param \DateTime $recoveryExpiration
	 *
	 * @return Teacher
	 */
	public function setRecoveryExpiration(\DateTime $recoveryExpiration = null)
	{
		$this->recoveryExpiration = $recoveryExpiration;

		return $this;
	}

	/**
	 * Get recoveryExpiration
	 *
	 * @return \DateTime
	 */
	public function getRecoveryExpiration()
	{
		return $this->recoveryExpiration;
	}

	/**
	 * Set lockout
	 *
	 * @param integer $lockout
	 *
	 * @return Teacher
	 */
	public function setLockout($lockout)
	{
		$this->lockout = $lockout;

		return $this;
	}

	/**
	 * Get lockout
	 *
	 * @return integer
	 */
	public function getLockout()
	{
		return $this->lockout;
	}

	/**
	 * Set dtCrea
	 *
	 * @param \DateTime $dtCrea
	 *
	 * @return Teacher
	 */
	public function setDtCrea(\DateTime $dtCrea = null)
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
	 * Set logins
	 *
	 * @param integer $logins
	 *
	 * @return Teacher
	 */
	public function setLogins($logins)
	{
		$this->logins = $logins;

		return $this;
	}

	/**
	 * Get logins
	 *
	 * @return integer
	 */
	public function getLogins()
	{
		return $this->logins;
	}

	/**
	 * Set lastLogin
	 *
	 * @param \DateTime $lastLogin
	 *
	 * @return Teacher
	 */
	public function setLastLogin(\DateTime $lastLogin = null)
	{
		$this->lastLogin = $lastLogin;

		return $this;
	}

	/**
	 * Get lastLogin
	 *
	 * @return \DateTime
	 */
	public function getLastLogin()
	{
		return $this->lastLogin;
	}

	/**
	 * Set lastActivity
	 *
	 * @param \DateTime $lastActivity
	 *
	 * @return Teacher
	 */
	public function setLastActivity(\DateTime $lastActivity = null)
	{
		$this->lastActivity = $lastActivity;

		return $this;
	}

	/**
	 * Get lastActivity
	 *
	 * @return \DateTime
	 */
	public function getLastActivity()
	{
		return $this->lastActivity;
	}

	/**
	 * Set lastName
	 *
	 * @param string $lastName
	 *
	 * @return Teacher
	 */
	public function setLastName($lastName)
	{
		$this->lastName = $lastName;

		return $this;
	}

	/**
	 * Get lastName
	 *
	 * @return string
	 */
	public function getLastName()
	{
		return $this->lastName;
	}

	/**
	 * Set firstName
	 *
	 * @param string $firstName
	 *
	 * @return Teacher
	 */
	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;

		return $this;
	}

	/**
	 * Get firstName
	 *
	 * @return string
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}

	/**
	 * Set sexe
	 *
	 * @param integer $sexe
	 *
	 * @return Teacher
	 */
	public function setSexe($sexe)
	{
		$this->sexe = $sexe;

		return $this;
	}

	/**
	 * Get sexe
	 *
	 * @return integer
	 */
	public function getSexe()
	{
		return $this->sexe;
	}

	/**
	 * Set birthday
	 *
	 * @param \DateTime $birthday
	 *
	 * @return Teacher
	 */
	public function setBirthday(\DateTime $birthday = null)
	{
		$this->birthday = $birthday;

		return $this;
	}

	/**
	 * Get birthday
	 *
	 * @return \DateTime
	 */
	public function getBirthday()
	{
		return $this->birthday;
	}

	/**
	 * Set address
	 *
	 * @param string $address
	 *
	 * @return Teacher
	 */
	public function setAddress($address)
	{
		$this->address = $address;

		return $this;
	}

	/**
	 * Get address
	 *
	 * @return string
	 */
	public function getAddress()
	{
		return $this->address;
	}

	/**
	 * Set country
	 *
	 * @param string $country
	 *
	 * @return Teacher
	 */
	public function setCountry($country)
	{
		$this->country = $country;

		return $this;
	}

	/**
	 * Get country
	 *
	 * @return string
	 */
	public function getCountry()
	{
		return $this->country;
	}

	/**
	 * Set phone
	 *
	 * @param string $phone
	 *
	 * @return Teacher
	 */
	public function setPhone($phone)
	{
		$this->phone = $phone;

		return $this;
	}

	/**
	 * Get phone
	 *
	 * @return string
	 */
	public function getPhone()
	{
		return $this->phone;
	}

	/**
	 * Set mobile
	 *
	 * @param string $mobile
	 *
	 * @return Teacher
	 */
	public function setMobile($mobile)
	{
		$this->mobile = $mobile;

		return $this;
	}

	/**
	 * Get mobile
	 *
	 * @return string
	 */
	public function getMobile()
	{
		return $this->mobile;
	}

	/**
	 * Set coursPhone
	 *
	 * @param string $coursPhone
	 *
	 * @return Teacher
	 */
	public function setCoursPhone($coursPhone)
	{
		$this->coursPhone = $coursPhone;

		return $this;
	}

	/**
	 * Get coursPhone
	 *
	 * @return string
	 */
	public function getCoursPhone()
	{
		return $this->coursPhone;
	}

	/**
	 * Set preferedLocale
	 *
	 * @param Locale $preferedLocale
	 *
	 * @return Teacher
	 */
	public function setPreferedLocale(Locale $preferedLocale = null)
	{
		$this->preferedLocale = $preferedLocale;

		return $this;
	}

	/**
	 * Get preferedLocale
	 *
	 * @return Locale
	 */
	public function getPreferedLocale()
	{
		return $this->preferedLocale;
	}

	/**
	 * Set avatar
	 *
	 * @param TeacherAvatar $avatar
	 *
	 * @return Teacher
	 */
	public function setAvatar(TeacherAvatar $avatar = null)
	{
		$this->avatar = $avatar;

		return $this;
	}

	/**
	 * Get avatar
	 *
	 * @return TeacherAvatar
	 */
	public function getAvatar()
	{
		return $this->avatar;
	}

	/**
	 * Set registerMail
	 *
	 * @param integer $registerMail
	 *
	 * @return Teacher
	 */
	public function setRegisterMail($registerMail)
	{
		$this->registerMail = $registerMail;

		return $this;
	}

	/**
	 * Get registerMail
	 *
	 * @return integer
	 */
	public function getRegisterMail()
	{
		return $this->registerMail;
	}

	/**
	 * Set type
	 *
	 * @param integer $type
	 *
	 * @return Teacher
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
	 * Set ftype
	 *
	 * @param integer $type
	 *
	 * @return Teacher
	 */
	public function setFtype($type)
	{
		$this->ftype = $type;

		return $this;
	}

	/**
	 * Get ftype
	 *
	 * @return integer
	 */
	public function getFtype()
	{
		return $this->ftype;
	}

	/**
	 * Set buggy
	 *
	 * @param integer $buggy
	 *
	 * @return Teacher
	 */
	public function setBuggy($buggy)
	{
		$this->buggy = $buggy;

		return $this;
	}

	/**
	 * Get buggy
	 *
	 * @return integer
	 */
	public function getBuggy()
	{
		return $this->buggy;
	}

	/**
	 * Add Role
	 *
	 * @param Role $role
	 *
	 * @return Teacher
	 */
	public function addTeacherRole(Role $role)
	{
		$this->teacherRoles[] = $role;

		return $this;
	}

	/**
	 * Remove Role
	 *
	 * @param Role $role
	 *
	 * @return Teacher
	 */
	public function removeTeacherRole(Role $role)
	{
		$this->teacherRoles->removeElement($role);

		return $this;
	}

	/**
	 * Set Role Collection
	 *
	 * @param Collection $roles
	 *
	 * @return Teacher
	 */
	public function setTeacherRoles(Collection $roles = null)
	{
		$this->teacherRoles = $roles;

		return $this;
	}

	/**
	 * Get Role ArrayCollection
	 *
	 * @return ArrayCollection
	 */
	public function getTeacherRoles()
	{
		return $this->teacherRoles;
	}

	/**
	 * Add TeacherAvailability
	 *
	 * @param TeacherAvailability $availability
	 *
	 * @return Teacher
	 */
	public function addAvailability(TeacherAvailability $availability)
	{
		$this->availabilities[] = $availability;

		return $this;
	}

	/**
	 * Remove TeacherAvailability
	 *
	 * @param TeacherAvailability $availability
	 *
	 * @return Teacher
	 */
	public function removeAvailability(TeacherAvailability $availability)
	{
		$this->availabilities->removeElement($availability);

		return $this;
	}

	/**
	 * Set TeacherAvailability Collection
	 *
	 * @param Collection $availabilities
	 *
	 * @return Teacher
	 */
	public function setAvailabilities(Collection $availabilities = null)
	{
		$this->availabilities = $availabilities;

		return $this;
	}

	/**
	 * Get TeacherAvailability ArrayCollection
	 *
	 * @return ArrayCollection
	 */
	public function getAvailabilities()
	{
		return $this->availabilities;
	}

	/**
	 * Add Cours
	 *
	 * @param Cours $cours
	 *
	 * @return Teacher
	 */
	public function addCours(Cours $cours)
	{
		$this->courses[] = $cours;

		return $this;
	}

	/**
	 * Remove Cours
	 *
	 * @param Cours $cours
	 *
	 * @return Teacher
	 */
	public function removeCours(Cours $cours)
	{
		$this->courses->removeElement($cours);

		return $this;
	}

	/**
	 * Set Cours Collection
	 *
	 * @param Collection $courses
	 *
	 * @return Teacher
	 */
	public function setCourses(Collection $courses = null)
	{
		$this->courses = $courses;

		return $this;
	}

	/**
	 * Get Cours ArrayCollection
	 *
	 * @return ArrayCollection
	 */
	public function getCourses()
	{
		return $this->courses;
	}

	/**
	 * Add TeacherNotif
	 *
	 * @param TeacherNotif $teacherNotif
	 *
	 * @return Teacher
	 */
	public function addNotification(TeacherNotif $teacherNotif)
	{
		$this->notifications[] = $teacherNotif;

		return $this;
	}

	/**
	 * Remove TeacherNotif
	 *
	 * @param TeacherNotif $teacherNotif
	 *
	 * @return Teacher
	 */
	public function removeNotification(TeacherNotif $teacherNotif)
	{
		$this->notifications->removeElement($teacherNotif);

		return $this;
	}

	/**
	 * Set TeacherNotif Collection
	 *
	 * @param Collection $teacherNotifs
	 *
	 * @return Teacher
	 */
	public function setNotifications(Collection $teacherNotifs = null)
	{
		$this->notifications = $teacherNotifs;

		return $this;
	}

	/**
	 * Get TeacherNotif ArrayCollection
	 *
	 * @return ArrayCollection
	 */
	public function getNotifications()
	{
		return $this->notifications;
	}

	/**
	 * Add TeacherLog
	 *
	 * @param TeacherLog $teacherLog
	 *
	 * @return Teacher
	 */
	public function addLog(TeacherLog $teacherLog)
	{
		$this->logs[] = $teacherLog;

		return $this;
	}

	/**
	 * Remove TeacherLog
	 *
	 * @param TeacherLog $teacherLog
	 *
	 * @return Teacher
	 */
	public function removeLog(TeacherLog $teacherLog)
	{
		$this->logs->removeElement($teacherLog);

		return $this;
	}

	/**
	 * Set TeacherLog Collection
	 *
	 * @param Collection $teacherLogs
	 *
	 * @return Teacher
	 */
	public function setLogs(Collection $teacherLogs = null)
	{
		$this->logs = $teacherLogs;

		return $this;
	}

	/**
	 * Get TeacherLog ArrayCollection
	 *
	 * @return ArrayCollection
	 */
	public function getLogs()
	{
		return $this->logs;
	}

	/**
	 * Get calculated fullName From username, firstName and lastName
	 *
	 * @return string
	 */
	public function getFullname()
	{
		if (null == $this->getFirstName() && null == $this->getLastName()) {
			return $this->getUsername();
		} elseif (null != $this->getFirstName() && null != $this->getLastName()) {
			return $this->getFirstName() . " " . $this->getLastName();
		} else {
			if (null != $this->getLastName()) {
				return $this->getLastName();
			}
			if (null != $this->getFirstName()) {
				return $this->getFirstName();
			}
		}
	}

	/**
	 * Set the lastActivity to now
	 *
	 * @return Teacher
	 */
	public function isActiveNow()
	{
		return $this->setLastActivity(new \DateTime());
	}

	/**
	 * Clear teacherRoles
	 *
	 * @return Teacher
	 */
	public function emptyRoles()
	{
		$this->teacherRoles = new ArrayCollection();

		return $this;
	}

	/**
	 * Erases the user credentials.
	 * (non-PHPdoc) @see
	 * \Symfony\Component\Security\Core\User\UserInterface::eraseCredentials()
	 */
	public function eraseCredentials()
	{
		$this->clearPassword = null;
	}

	/**
	 * Get roles
	 * (non-PHPdoc) @see
	 * \Symfony\Component\Security\Core\User\UserInterface::getRoles()
	 *
	 * @return ArrayCollection
	 */
	public function getRoles()
	{
		return $this->teacherRoles->toArray();
	}

	/**
	 * Serializes the Teacher.
	 * The serialized data have to contain the fields used by the equals method
	 * and the username.
	 * (non-PHPdoc) @see Serializable::serialize()
	 *
	 * @return string
	 */
	public function serialize()
	{
		return serialize(array(
			$this->password,
			$this->salt,
			$this->username,
			$this->email,
			$this->lockout,
			$this->id
		));
	}

	/**
	 * Unserializes the Teacher.
	 * (non-PHPdoc) @see Serializable::unserialize()
	 *
	 * @param string $serialized
	 */
	public function unserialize($serialized)
	{
		$data = unserialize($serialized);
		// add a few extra elements in the array to ensure that we have enough
		// keys when
		// unserializing
		// older data which does not include all properties.
		$data = array_merge($data, array_fill(0, 2, null));

		list ($this->password, $this->salt, $this->username, $this->email, $this->lockout, $this->id) = $data;
	}

	/**
	 * Get a random char (for generated password)
	 *
	 * @param integer $length
	 * @param string $charset
	 *
	 * @return string
	 */
	public static function generateRandomChar($length, $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789#@!?+=*/-')
	{
		$str = '';
		$count = strlen($charset);
		while ($length--) {
			$str .= $charset[mt_rand(0, $count - 1)];
		}

		return $str;
	}

	/**
	 * Choice Form lockout
	 *
	 * @return multitype:string
	 */
	public static function choiceLockout()
	{
		return array(
			'Teacher.lockout.choice.' . self::LOCKOUT_UNLOCKED => self::LOCKOUT_UNLOCKED,
			'Teacher.lockout.choice.' . self::LOCKOUT_LOCKED => self::LOCKOUT_LOCKED
		);
	}

	/**
	 * Choice Validator lockout
	 *
	 * @return multitype:string
	 */
	public static function choiceLockoutCallback()
	{
		return array(
			self::LOCKOUT_UNLOCKED,
			self::LOCKOUT_LOCKED
		);
	}

	/**
	 * Choice Form sexe
	 *
	 * @return multitype:string
	 */
	public static function choiceSexe()
	{
		return array(
			'Teacher.sexe.choice.' . self::SEXE_MISS => self::SEXE_MISS,
			'Teacher.sexe.choice.' . self::SEXE_MRS => self::SEXE_MRS,
			'Teacher.sexe.choice.' . self::SEXE_MR => self::SEXE_MR
		);
	}

	/**
	 * Choice Validator sexe
	 *
	 * @return multitype:integer
	 */
	public static function choiceSexeCallback()
	{
		return array(
			self::SEXE_MISS,
			self::SEXE_MRS,
			self::SEXE_MR
		);
	}

	/**
	 * Choice Form registerMail
	 *
	 * @return multitype:string
	 */
	public static function choiceRegisterMail()
	{
		return array(
			'Teacher.registerMail.choice.' . self::REGISTERMAIL_NOTSENT => self::REGISTERMAIL_NOTSENT,
			'Teacher.registerMail.choice.' . self::REGISTERMAIL_SENT => self::REGISTERMAIL_SENT,
			'Teacher.registerMail.choice.' . self::REGISTERMAIL_DISABLED => self::REGISTERMAIL_DISABLED
		);
	}

	/**
	 * Choice Validator registerMail
	 *
	 * @return multitype:integer
	 */
	public static function choiceRegisterMailCallback()
	{
		return array(
			self::REGISTERMAIL_NOTSENT,
			self::REGISTERMAIL_SENT,
			self::REGISTERMAIL_DISABLED
		);
	}

	/**
	 * Choice Form type
	 *
	 * @return multitype:string
	 */
	public static function choiceType()
	{
		return array(
			'Teacher.type.choice.' . self::TYPE_INTERNAL => self::TYPE_INTERNAL,
			'Teacher.type.choice.' . self::TYPE_EXTERNAL => self::TYPE_EXTERNAL
		);
	}

	/**
	 * Choice Validator type
	 *
	 * @return multitype:integer
	 */
	public static function choiceTypeCallback()
	{
		return array(
			self::TYPE_INTERNAL,
			self::TYPE_EXTERNAL
		);
	}

	/**
	 * Choice Form ftype
	 *
	 * @return multitype:string
	 */
	public static function choiceFtype()
	{
		return array(
			'Teacher.ftype.choice.' . self::FTYPE_EN => self::FTYPE_EN
		);
	}

	/**
	 * Choice Validator ftype
	 *
	 * @return multitype:integer
	 */
	public static function choiceFtypeCallback()
	{
		return array(
			self::FTYPE_EN
		);
	}

	/**
	 * Update the buggy status
	 * @ORM\PreFlush()
	 */
	public function updateBuggy()
	{
		$this->setBuggy(self::HEALTH_OK);

		if (null == $this->getEmail() || trim($this->getEmail()) == '') {
			$this->setBuggy(self::HEALTH_BUGGY);
		}

		if (null == $this->getCoursPhone() || trim($this->getCoursPhone()) == '') {
			$this->setBuggy(self::HEALTH_BUGGY);
		}
	}

	/**
	 * string representation of the object
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->getFullname();
	}
}
