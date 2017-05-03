<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Entity;

use Ilcfrance\Worldspeak\Shared\DataBundle\Document\AdminAvatar;
use Ilcfrance\Worldspeak\Shared\ResBundle\Validator as ExtraAssert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Encoder\Pbkdf2PasswordEncoder;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Admin Entity
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *         @ORM\Table(name="administrators")
 *         @ORM\Entity(repositoryClass="Ilcfrance\Worldspeak\Shared\DataBundle\Repository\AdminRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_admin")
 *         @UniqueEntity(fields="username", message="Admin.username.unique")
 *         @UniqueEntity(fields="email", message="Admin.email.unique")
 */
class Admin implements UserInterface, \Serializable
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
	 * @var guid @ORM\Column(name="id", type="guid", nullable=false)
	 *      @ORM\Id
	 *      @ORM\GeneratedValue(strategy="UUID")
	 */
	protected $id;

	/**
	 *
	 * @var string @ORM\Column(name="username", type="text", nullable=false)
	 */
	protected $username;

	/**
	 *
	 * @var string @ORM\Column(name="email", type="text", nullable=true)
	 *      @Assert\Email(checkMX=true, checkHost=true,
	 *      groups={"admRegistration", "admUpdateMail", "updateMail"})
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
	 *      @Assert\Choice(callback="choiceLockoutCallback",
	 *      groups={"admLockout"})
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
	 * @var Locale @ORM\ManyToOne(targetEntity="Locale", inversedBy="admins")
	 *      @ORM\JoinColumns({
	 *      @ORM\JoinColumn(name="preferedlang", referencedColumnName="id")
	 *      })
	 */
	protected $preferedLocale;

	/**
	 *
	 * @var AdminAvatar @ORM\Column(name="avatar", type="AdminAvatar",
	 *      nullable=true)
	 */
	protected $avatar;

	/**
	 *
	 * @var Collection @ORM\ManyToMany(targetEntity="Role", inversedBy="admins")
	 *      @ORM\JoinTable(name="administrators_roles",
	 *      joinColumns={
	 *      @ORM\JoinColumn(name="administrator", referencedColumnName="id")
	 *      },
	 *      inverseJoinColumns={
	 *      @ORM\JoinColumn(name="role", referencedColumnName="id")
	 *      }
	 *      )
	 */
	protected $adminRoles;

	/**
	 *
	 * @var Collection @ORM\OneToMany(targetEntity="AdminLog", mappedBy="admin")
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
		$this->logins = 0;
		$this->setSalt(md5(uniqid(null, true)));

		$this->adminRoles = new ArrayCollection();
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
	 * Set username
	 *
	 * @param string $username
	 *
	 * @return Admin
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
	 * @return Admin
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
	 * @return Admin
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
	 * @return Admin
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
	 * @return Admin
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
	 * @return Admin
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
	 * @return Admin
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
	 * @return Admin
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
	 * @return Admin
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
	 * @return Admin
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
	 * @return Admin
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
	 * @return Admin
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
	 * @return Admin
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
	 * @return Admin
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
	 * @return Admin
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
	 * @return Admin
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
	 * @return Admin
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
	 * @return Admin
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
	 * @return Admin
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
	 * @return Admin
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
	 * @return Admin
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
	 * @param AdminAvatar $avatar
	 *
	 * @return Admin
	 */
	public function setAvatar(AdminAvatar $avatar = null)
	{
		$this->avatar = $avatar;

		return $this;
	}

	/**
	 * Get avatar
	 *
	 * @return AdminAvatar
	 */
	public function getAvatar()
	{
		return $this->avatar;
	}

	/**
	 * Add Role
	 *
	 * @param Role $role
	 *
	 * @return Admin
	 */
	public function addAdminRole(Role $role)
	{
		$this->adminRoles[] = $role;

		return $this;
	}

	/**
	 * Remove Role
	 *
	 * @param Role $role
	 *
	 * @return Admin
	 */
	public function removeAdminRole(Role $role)
	{
		$this->adminRoles->removeElement($role);

		return $this;
	}

	/**
	 * set Role Collection
	 *
	 * @param Collection $roles
	 *
	 * @return Admin
	 */
	public function setAdminRoles(Collection $roles = null)
	{
		$this->adminRoles = $roles;

		return $this;
	}

	/**
	 * Get Role ArrayCollection
	 *
	 * @return ArrayCollection
	 */
	public function getAdminRoles()
	{
		return $this->adminRoles;
	}

	/**
	 * Add AdminLog
	 *
	 * @param AdminLog $adminLog
	 *
	 * @return Admin
	 */
	public function addLog(AdminLog $adminLog)
	{
		$this->logs[] = $adminLog;

		return $this;
	}

	/**
	 * Remove AdminLog
	 *
	 * @param AdminLog $adminLog
	 *
	 * @return Admin
	 */
	public function removeLog(AdminLog $adminLog)
	{
		$this->logs->removeElement($adminLog);

		return $this;
	}

	/**
	 * Set AdminLog Collection
	 *
	 * @param Collection $adminLogs
	 *
	 * @return Admin
	 */
	public function setLogs(Collection $adminLogs = null)
	{
		$this->logs = $adminLogs;

		return $this;
	}

	/**
	 * Get AdminLog ArrayCollection
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
	 * @return Admin
	 */
	public function isActiveNow()
	{
		return $this->setLastActivity(new \DateTime());
	}

	/**
	 * Clear adminRoles
	 *
	 * @return Admin
	 */
	public function emptyRoles()
	{
		$this->adminRoles = new ArrayCollection();

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
		return $this->adminRoles->toArray();
	}

	/**
	 * Serializes the Admin.
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
	 * Unserializes the Admin.
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
			'Admin.lockout.choice.' . self::LOCKOUT_UNLOCKED => self::LOCKOUT_UNLOCKED,
			'Admin.lockout.choice.' . self::LOCKOUT_LOCKED => self::LOCKOUT_LOCKED
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
			'Admin.sexe.choice.' . self::SEXE_MISS => self::SEXE_MISS,
			'Admin.sexe.choice.' . self::SEXE_MRS => self::SEXE_MRS,
			'Admin.sexe.choice.' . self::SEXE_MR => self::SEXE_MR
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
	 * string representation of the object
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->getFullname();
	}
}
