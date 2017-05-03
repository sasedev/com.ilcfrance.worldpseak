<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Entity;

use Ilcfrance\Worldspeak\Shared\DataBundle\Document\TraineeAvatar;
use Ilcfrance\Worldspeak\Shared\ResBundle\Validator as ExtraAssert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Encoder\Pbkdf2PasswordEncoder;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trainee Entity
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *         @ORM\Table(name="trainees")
 *         @ORM\Entity(repositoryClass="Ilcfrance\Worldspeak\Shared\DataBundle\Repository\TraineeRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_trainee")
 *         @UniqueEntity(fields="username", message="Trainee.username.unique")
 *         @UniqueEntity(fields="email", message="Trainee.email.unique", groups={"admRegistration", "admUpdateMail", "updateMail"})
 *         @UniqueEntity(fields="codeMA", message="Trainee.codeMA.unique")
 */
class Trainee implements UserInterface, \Serializable
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
	 * @var Company @ORM\ManyToOne(targetEntity="Company", inversedBy="trainees")
	 *      @ORM\JoinColumns({@ORM\JoinColumn(name="company", referencedColumnName="id")})
	 *      @Assert\NotNull
	 */
	protected $company;

	/**
	 *
	 * @var string @ORM\Column(name="projects", type="text", nullable=true)
	 */
	protected $project;

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
	 *      @Assert\Choice(callback="choiceLockoutCallback", groups={"admRegistration", "admLockout"})
	 */
	protected $lockout;

	/**
	 *
	 * @var \DateTime @ORM\Column(name="dtcrea", type="datetimetz", nullable=true)
	 */
	protected $dtCrea;

	/**
	 *
	 * @var integer @ORM\Column(name="logins", type="bigint", nullable=false)
	 */
	protected $logins;

	/**
	 *
	 * @var \DateTime @ORM\Column(name="lastlogin", type="datetimetz", nullable=true)
	 */
	protected $lastLogin;

	/**
	 *
	 * @var \DateTime @ORM\Column(name="lastactivity", type="datetimetz", nullable=true)
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
	 *      @Assert\Choice(callback="choiceSexeCallback", groups={"admRegistration","admProfile", "profile"})
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
	 *      @ExtraAssert\Phone(message="_phone.invalid", groups={"admRegistration", "admProfile", "profile"})
	 */
	protected $phone;

	/**
	 *
	 * @var string @ORM\Column(name="mobile", type="text", nullable=true)
	 *      @ExtraAssert\Phone(message="_phone.invalid", groups={"admRegistration", "admProfile", "profile"})
	 */
	protected $mobile;

	/**
	 *
	 * @var Locale @ORM\ManyToOne(targetEntity="Locale", inversedBy="trainees")
	 *      @ORM\JoinColumns({
	 *      @ORM\JoinColumn(name="preferedlang", referencedColumnName="id")
	 *      })
	 */
	protected $preferedLocale;

	/**
	 *
	 * @var TraineeAvatar @ORM\Column(name="avatar", type="TraineeAvatar",
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
	 * @var string @ORM\Column(name="cef", type="text", nullable=true)
	 *      @Assert\Choice(callback="choiceCefCallback",
	 *      groups={"admRegistration","admCef", "teacherCef"})
	 */
	protected $cef;

	/**
	 *
	 * @var string @ORM\Column(name="job", type="text", nullable=true)
	 */
	protected $job;

	/**
	 *
	 * @var string @ORM\Column(name="responsabilities", type="text",
	 *      nullable=true)
	 */
	protected $responsabilities;

	/**
	 *
	 * @var string @ORM\Column(name="needs", type="text", nullable=true)
	 */
	protected $needs;

	/**
	 *
	 * @var string @ORM\Column(name="outsideinterests", type="text",
	 *      nullable=true)
	 */
	protected $outsideInterests;

	/**
	 *
	 * @var string @ORM\Column(name="comments", type="text", nullable=true)
	 */
	protected $comments;

	/**
	 *
	 * @var integer @ORM\Column(name="buggy", type="integer", nullable=false)
	 */
	protected $buggy;

	/**
	 *
	 * @var Collection @ORM\ManyToMany(targetEntity="Role",
	 *      inversedBy="trainees")
	 *      @ORM\JoinTable(name="trainees_roles",
	 *      joinColumns={
	 *      @ORM\JoinColumn(name="trainee", referencedColumnName="id")
	 *      },
	 *      inverseJoinColumns={
	 *      @ORM\JoinColumn(name="role", referencedColumnName="id")
	 *      }
	 *      )
	 */
	protected $traineeRoles;

	/**
	 *
	 * @var Collection @ORM\OneToMany(targetEntity="TimeCredit",
	 *      mappedBy="trainee")
	 *      @ORM\OrderBy({"dtCrea"="DESC"})
	 */
	protected $credits;

	/**
	 *
	 * @var Collection @ORM\OneToMany(targetEntity="TraineeNotif", mappedBy="trainee")
	 *      @ORM\OrderBy({"dtStart"="ASC"})
	 */
	protected $notifications;

	/**
	 *
	 * @var Collection @ORM\OneToMany(targetEntity="TraineeLog", mappedBy="trainee")
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

		$this->buggy = self::HEALTH_OK;

		$this->traineeRoles = new ArrayCollection();
		$this->credits = new ArrayCollection();
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
	 * Set company
	 *
	 * @param Company $company
	 *
	 * @return Trainee
	 */
	public function setCompany(Company $company = null)
	{
		$this->company = $company;

		return $this;
	}

	/**
	 * Get company
	 *
	 * @return Company
	 */
	public function getCompany()
	{
		return $this->company;
	}

	/**
	 * Set codeMA
	 *
	 * @param string $codeMA
	 *
	 * @return Trainee
	 */
	public function setCodeMA($codeMA)
	{
		$this->codeMA = $codeMA;

		return $this;
	}

	/**
	 * Set project
	 *
	 * @param string $project
	 *
	 * @return Trainee
	 */
	public function setProject($project)
	{
		$this->project = trim($project);

		return $this;
	}

	/**
	 * Get project
	 *
	 * @return string
	 */
	public function getProject()
	{
		return $this->project;
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
	 * @return Trainee
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
	 * @return Trainee
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
	 * @return Trainee
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
	 * @return Trainee
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
	 * @return Trainee
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
	 * @return Trainee
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
	 * @return Trainee
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
	 * @return Trainee
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
	 * @return Trainee
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
	 * @return Trainee
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
	 * @return Trainee
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
	 * @return Trainee
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
	 * @return Trainee
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
	 * @return Trainee
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
	 * @return Trainee
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
	 * @return Trainee
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
	 * @return Trainee
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
	 * @return Trainee
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
	 * @return Trainee
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
	 * @return Trainee
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
	 * Set preferedLocale
	 *
	 * @param Locale $preferedLocale
	 *
	 * @return Trainee
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
	 * @param TraineeAvatar $avatar
	 *
	 * @return Trainee
	 */
	public function setAvatar(TraineeAvatar $avatar = null)
	{
		$this->avatar = $avatar;

		return $this;
	}

	/**
	 * Get avatar
	 *
	 * @return TraineeAvatar
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
	 * @return Trainee
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
	 * Set cef
	 *
	 * @param string $cef
	 *
	 * @return Trainee
	 */
	public function setCef($cef)
	{
		$this->cef = $cef;

		return $this;
	}

	/**
	 * Get cef
	 *
	 * @return string
	 */
	public function getCef()
	{
		return $this->cef;
	}

	/**
	 * Set job
	 *
	 * @param string $job
	 *
	 * @return Trainee
	 */
	public function setJob($job)
	{
		$this->job = $job;

		return $this;
	}

	/**
	 * Get job
	 *
	 * @return string
	 */
	public function getJob()
	{
		return $this->job;
	}

	/**
	 * Set responsabilities
	 *
	 * @param string $responsabilities
	 *
	 * @return Trainee
	 */
	public function setResponsabilities($responsabilities)
	{
		$this->responsabilities = $responsabilities;

		return $this;
	}

	/**
	 * Get responsabilities
	 *
	 * @return string
	 */
	public function getResponsabilities()
	{
		return $this->responsabilities;
	}

	/**
	 * Set needs
	 *
	 * @param string $needs
	 *
	 * @return Trainee
	 */
	public function setNeeds($needs)
	{
		$this->needs = $needs;

		return $this;
	}

	/**
	 * Get needs
	 *
	 * @return string
	 */
	public function getNeeds()
	{
		return $this->needs;
	}

	/**
	 * Set outsideInterests
	 *
	 * @param string $outsideInterests
	 *
	 * @return Trainee
	 */
	public function setOutsideInterests($outsideInterests)
	{
		$this->outsideInterests = $outsideInterests;

		return $this;
	}

	/**
	 * Get outsideInterests
	 *
	 * @return string
	 */
	public function getOutsideInterests()
	{
		return $this->outsideInterests;
	}

	/**
	 * Set comments
	 *
	 * @param string $comments
	 *
	 * @return Trainee
	 */
	public function setComments($comments)
	{
		$this->comments = $comments;

		return $this;
	}

	/**
	 * Get comments
	 *
	 * @return string
	 */
	public function getComments()
	{
		return $this->comments;
	}

	/**
	 * Set buggy
	 *
	 * @param integer $buggy
	 *
	 * @return Cours
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
	 * @return Trainee
	 */
	public function addTraineeRole(Role $role)
	{
		$this->traineeRoles[] = $role;

		return $this;
	}

	/**
	 * Remove Role
	 *
	 * @param Role $role
	 *
	 * @return Trainee
	 */
	public function removeTraineeRole(Role $role)
	{
		$this->traineeRoles->removeElement($role);

		return $this;
	}

	/**
	 * Set Role Collection
	 *
	 * @param Collection $roles
	 *
	 * @return Trainee
	 */
	public function setTraineeRoles(Collection $roles = null)
	{
		$this->traineeRoles = $roles;

		return $this;
	}

	/**
	 * Get Role ArrayCollection
	 *
	 * @return ArrayCollection
	 */
	public function getTraineeRoles()
	{
		return $this->traineeRoles;
	}

	/**
	 * Add TimeCredit
	 *
	 * @param TimeCredit $timeCredit
	 *
	 * @return Trainee
	 */
	public function addCredit(TimeCredit $timeCredit)
	{
		$this->credits[] = $timeCredit;

		return $this;
	}

	/**
	 * Remove TimeCredit
	 *
	 * @param TimeCredit $timeCredit
	 *
	 * @return Trainee
	 */
	public function removeCredit(TimeCredit $timeCredit)
	{
		$this->credits->removeElement($timeCredit);

		return $this;
	}

	/**
	 * Set TimeCredit Collection
	 *
	 * @param Collection $timeCredits
	 *
	 * @return Trainee
	 */
	public function setCredits(Collection $timeCredits = null)
	{
		$this->credits = $timeCredits;

		return $this;
	}

	/**
	 * Get TimeCredit ArrayCollection
	 *
	 * @return ArrayCollection
	 */
	public function getCredits()
	{
		return $this->credits;
	}

	/**
	 * Add TraineeNotif
	 *
	 * @param TraineeNotif $traineeNotif
	 *
	 * @return Trainee
	 */
	public function addNotification(TraineeNotif $traineeNotif)
	{
		$this->notifications[] = $traineeNotif;

		return $this;
	}

	/**
	 * Remove TraineeNotif
	 *
	 * @param TraineeNotif $traineeNotif
	 *
	 * @return Trainee
	 */
	public function removeNotification(TraineeNotif $traineeNotif)
	{
		$this->notifications->removeElement($traineeNotif);

		return $this;
	}

	/**
	 * Set TraineeNotif Collection
	 *
	 * @param Collection $traineeNotifs
	 *
	 * @return Trainee
	 */
	public function setNotifications(Collection $traineeNotifs = null)
	{
		$this->notifications = $traineeNotifs;

		return $this;
	}

	/**
	 * Get TraineeNotif ArrayCollection
	 *
	 * @return ArrayCollection
	 */
	public function getNotifications()
	{
		return $this->notifications;
	}

	/**
	 * Add TraineeLog
	 *
	 * @param TraineeLog $traineeLog
	 *
	 * @return Trainee
	 */
	public function addLog(TraineeLog $traineeLog)
	{
		$this->logs[] = $traineeLog;

		return $this;
	}

	/**
	 * Remove TraineeLog
	 *
	 * @param TraineeLog $traineeLog
	 *
	 * @return Trainee
	 */
	public function removeLog(TraineeLog $traineeLog)
	{
		$this->logs->removeElement($traineeLog);

		return $this;
	}

	/**
	 * Set TraineeLog Collection
	 *
	 * @param Collection $traineeLogs
	 *
	 * @return Trainee
	 */
	public function setLogs(Collection $traineeLogs = null)
	{
		$this->logs = $traineeLogs;

		return $this;
	}

	/**
	 * Get TraineeLog ArrayCollection
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
	 * Get calculated fullName From username, firstName and lastName
	 *
	 * @return string
	 */
	public function getFullname2()
	{
		if (null == $this->getLastName() && null == $this->getFirstName()) {
			return $this->getUsername();
		} elseif (null != $this->getFirstName() && null != $this->getLastName()) {
			return $this->getLastName() . " " . $this->getFirstName();
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
	 * @return Trainee
	 */
	public function isActiveNow()
	{
		return $this->setLastActivity(new \DateTime());
	}

	/**
	 * Clear traineeRoles
	 *
	 * @return Trainee
	 */
	public function emptyRoles()
	{
		$this->traineeRoles = new ArrayCollection();

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
		return $this->traineeRoles->toArray();
	}

	/**
	 * Serializes the Trainee.
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
	 * Unserializes the Trainee.
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
			'Trainee.lockout.choice.' . self::LOCKOUT_UNLOCKED => self::LOCKOUT_UNLOCKED,
			'Trainee.lockout.choice.' . self::LOCKOUT_LOCKED => self::LOCKOUT_LOCKED
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
			'Trainee.sexe.choice.' . self::SEXE_MISS => self::SEXE_MISS,
			'Trainee.sexe.choice.' . self::SEXE_MRS => self::SEXE_MRS,
			'Trainee.sexe.choice.' . self::SEXE_MR => self::SEXE_MR
		);
	}

	/**
	 * Choice Validator lockout
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
			'Trainee.registerMail.choice.' . self::REGISTERMAIL_NOTSENT => self::REGISTERMAIL_NOTSENT,
			'Trainee.registerMail.choice.' . self::REGISTERMAIL_SENT => self::REGISTERMAIL_SENT,
			'Trainee.registerMail.choice.' . self::REGISTERMAIL_DISABLED => self::REGISTERMAIL_DISABLED
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
	 * Choice Form cef
	 *
	 * @return multitype:string
	 */
	public static function choiceCef()
	{
		return array(
			'0 / A1-' => '0 / A1-',
			'0.1 / A1-' => '0.1 / A1-',
			'0.2 / A1-' => '0.2 / A1-',
			'0.3 / A1-' => '0.3 / A1-',
			'0.4 / A1' => '0.4 / A1',
			'0.5 / A1' => '0.5 / A1',
			'0.6 / A1' => '0.6 / A1',
			'0.7 / A1+' => '0.7 / A1+',
			'0.8 / A1+' => '0.8 / A1+',
			'0.9 / A1+' => '0.9 / A1+',
			'1 / A2-' => '1 / A2-',
			'1.1 / A2-' => '1.1 / A2-',
			'1.2 / A2-' => '1.2 / A2-',
			'1.3 / A2-' => '1.3 / A2-',
			'1.4 / A2' => '1.4 / A2',
			'1.5 / A2' => '1.5 / A2',
			'1.6 / A2' => '1.6 / A2',
			'1.7 / A2+' => '1.7 / A2+',
			'1.8 / A2+' => '1.8 / A2+',
			'1.9 / A2+' => '1.9 / A2+',
			'2 / B1-' => '2 / B1-',
			'2.1 / B1-' => '2.1 / B1-',
			'2.2 / B1-' => '2.2 / B1-',
			'2.3 / B1-' => '2.3 / B1-',
			'2.4 / B1' => '2.4 / B1',
			'2.5 / B1' => '2.5 / B1',
			'2.6 / B1' => '2.6 / B1',
			'2.7 / B1+' => '2.7 / B1+',
			'2.8 / B1+' => '2.8 / B1+',
			'2.9 / B1+' => '2.9 / B1+',
			'3 / B2-' => '3 / B2-',
			'3.1 / B2-' => '3.1 / B2-',
			'3.2 / B2-' => '3.2 / B2-',
			'3.3 / B2-' => '3.3 / B2-',
			'3.4 / B2' => '3.4 / B2',
			'3.5 / B2' => '3.5 / B2',
			'3.6 / B2' => '3.6 / B2',
			'3.7 / B2+' => '3.7 / B2+',
			'3.8 / B2+' => '3.8 / B2+',
			'3.9 / B2+' => '3.9 / B2+',
			'4 / C1-' => '4 / C1-',
			'4.1 / C1-' => '4.1 / C1-',
			'4.2 / C1-' => '4.2 / C1-',
			'4.3 / C1-' => '4.3 / C1-',
			'4.4 / C1' => '4.4 / C1',
			'4.5 / C1' => '4.5 / C1',
			'4.6 / C1' => '4.6 / C1',
			'4.7 / C1+' => '4.7 / C1+',
			'4.8 / C1+' => '4.8 / C1+',
			'4.9 / C1+' => '4.9 / C1+',
			'5 / C2' => '5 / C2'
		);
	}

	/**
	 * Choice Validator cef
	 *
	 * @return multitype:string
	 */
	public static function choiceCefCallback()
	{
		return array(
			'0 / A1-',
			'0.1 / A1-',
			'0.2 / A1-',
			'0.3 / A1-',
			'0.4 / A1',
			'0.5 / A1',
			'0.6 / A1',
			'0.7 / A1+',
			'0.8 / A1+',
			'0.9 / A1+',
			'1 / A2-',
			'1.1 / A2-',
			'1.2 / A2-',
			'1.3 / A2-',
			'1.4 / A2',
			'1.5 / A2',
			'1.6 / A2',
			'1.7 / A2+',
			'1.8 / A2+',
			'1.9 / A2+',
			'2 / B1-',
			'2.1 / B1-',
			'2.2 / B1-',
			'2.3 / B1-',
			'2.4 / B1',
			'2.5 / B1',
			'2.6 / B1',
			'2.7 / B1+',
			'2.8 / B1+',
			'2.9 / B1+',
			'3 / B2-',
			'3.1 / B2-',
			'3.2 / B2-',
			'3.3 / B2-',
			'3.4 / B2',
			'3.5 / B2',
			'3.6 / B2',
			'3.7 / B2+',
			'3.8 / B2+',
			'3.9 / B2+',
			'4 / C1-',
			'4.1 / C1-',
			'4.2 / C1-',
			'4.3 / C1-',
			'4.4 / C1',
			'4.5 / C1',
			'4.6 / C1',
			'4.7 / C1+',
			'4.8 / C1+',
			'4.9 / C1+',
			'5 / C2'
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

		if (null == $this->getCef() || trim($this->getCef()) == '') {
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
