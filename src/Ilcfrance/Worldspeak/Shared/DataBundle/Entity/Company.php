<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Company Entity
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *         @ORM\Table(name="companies")
 *         @ORM\Entity(repositoryClass="Ilcfrance\Worldspeak\Shared\DataBundle\Repository\CompanyRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_company")
 *         @UniqueEntity(fields="prefix", message="Company.prefix.unique")
 *         @UniqueEntity(fields="codeMA", message="Company.codeMA.unique")
 */
class Company
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
	 * @var string @ORM\Column(name="codema", type="text", nullable=true)
	 */
	protected $codeMA;

	/**
	 *
	 * @var string @ORM\Column(name="prefix", type="text", nullable=false)
	 *      @Assert\Regex(pattern="/^[a-z][a-z0-9]{1,19}$/", message="Company.prefix.invalid")
	 */
	protected $prefix;

	/**
	 *
	 * @var string @ORM\Column(name="name", type="text", nullable=false)
	 *      @Assert\NotNull(message="Company.name.notblank")
	 *      @Assert\NotBlank(message="Company.name.notblank")
	 */
	protected $name;

	/**
	 *
	 * @var \DateTime @ORM\Column(name="dtcrea", type="datetimetz", nullable=true)
	 */
	protected $dtCrea;

	/**
	 *
	 * @var string @ORM\Column(name="service", type="text", nullable=true)
	 */
	protected $service;

	/**
	 *
	 * @var string @ORM\Column(name="address", type="text", nullable=true)
	 */
	protected $address;

	/**
	 *
	 * @var string @ORM\Column(name="postalcode", type="text", nullable=true)
	 */
	protected $postalCode;

	/**
	 *
	 * @var string @ORM\Column(name="town", type="text", nullable=true)
	 */
	protected $town;

	/**
	 *
	 * @var string @ORM\Column(name="country", type="text", nullable=true)
	 *      @Assert\Country()
	 */
	protected $country;

	/**
	 *
	 * @var integer @ORM\Column(name="autoinc", type="bigint", nullable=false)
	 */
	protected $autoinc;

	/**
	 *
	 * @var Collection @ORM\OneToMany(targetEntity="Trainee", mappedBy="company")
	 *      @ORM\OrderBy({"username" = "ASC"})
	 */
	protected $trainees;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->autoinc = 1;
		$this->dtCrea = new \DateTime('now');
		$this->trainees = new ArrayCollection();
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
	 * @return Company
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
	 * Set prefix
	 *
	 * @param string $prefix
	 *
	 * @return Company
	 */
	public function setPrefix($prefix)
	{
		$this->prefix = trim(strtolower($prefix));

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
	 * @return Company
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
	 * Set dtCrea
	 *
	 * @param \DateTime $dtCrea
	 *
	 * @return Company
	 */
	public function setDtCrea(\DateTime $dtCrea)
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
	 * Set service
	 *
	 * @param string $service
	 *
	 * @return Company
	 */
	public function setService($service)
	{
		$this->service = $service;

		return $this;
	}

	/**
	 * Get service
	 *
	 * @return string
	 */
	public function getService()
	{
		return $this->service;
	}

	/**
	 * Set address
	 *
	 * @param string $address
	 *
	 * @return Company
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
	 * Set postalCode
	 *
	 * @param string $postalCode
	 *
	 * @return Company
	 */
	public function setPostalCode($postalCode)
	{
		$this->postalCode = $postalCode;

		return $this;
	}

	/**
	 * Get postalCode
	 *
	 * @return string
	 */
	public function getPostalCode()
	{
		return $this->postalCode;
	}

	/**
	 * Set town
	 *
	 * @param string $town
	 *
	 * @return Company
	 */
	public function setTown($town)
	{
		$this->town = $town;

		return $this;
	}

	/**
	 * Get town
	 *
	 * @return string
	 */
	public function getTown()
	{
		return $this->town;
	}

	/**
	 * Set country
	 *
	 * @param string $country
	 *
	 * @return Company
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
	 * Set autoinc
	 *
	 * @param integer $autoinc
	 *
	 * @return Company
	 */
	public function setAutoinc($autoinc)
	{
		$this->autoinc = $autoinc;

		return $this;
	}

	/**
	 * Get autoinc
	 *
	 * @return integer
	 */
	public function getAutoinc()
	{
		return $this->autoinc;
	}

	/**
	 * Add Trainee
	 *
	 * @param Trainee $trainee
	 *
	 * @return Company
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
	 * @return Company
	 */
	public function removeTrainee(Trainee $trainee)
	{
		$this->trainees->removeElement($trainee);

		return $this;
	}

	/**
	 * Set Trainee collection
	 *
	 * @param Collection $traines
	 *
	 * @return Company
	 */
	public function setTrainees(Collection $traines)
	{
		$this->trainees = $traines;

		return $this;
	}

	/**
	 * Get Trainee collection
	 *
	 * @return ArrayCollection
	 */
	public function getTrainees()
	{
		return $this->trainees;
	}

	/**
	 * string representation of the object
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->getName() . " (" . $this->getPrefix() . ")";
	}
}
