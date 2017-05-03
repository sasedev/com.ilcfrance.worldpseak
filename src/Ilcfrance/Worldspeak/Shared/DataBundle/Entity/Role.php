<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Role Entity
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *         @ORM\Table(name="roles")
 *         @ORM\Entity(repositoryClass="Ilcfrance\Worldspeak\Shared\DataBundle\Repository\RoleRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_role")
 *         @UniqueEntity(fields="name", message="Role.name.unique")
 */
class Role implements RoleInterface
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
	 * @var string @ORM\Column(name="name", type="text", length=102,
	 *      nullable=false)
	 *      @Assert\Length(max=100)
	 *      @Assert\Regex(pattern="/^ROLE\_[A-Z](([A-Z0-9\_]+[A-Z0-9])|[A-Z0-9])$/",
	 *      message="Role.name.regex")
	 */
	protected $name;

	/**
	 *
	 * @var string @ORM\Column(name="description", type="text", nullable=true)
	 */
	protected $description;

	/**
	 *
	 * @var Collection @ORM\ManyToMany(targetEntity="Admin",
	 *      mappedBy="adminRoles")
	 *      @ORM\JoinTable(name="administrators_roles",
	 *      joinColumns={
	 *      @ORM\JoinColumn(name="role", referencedColumnName="id")
	 *      },
	 *      inverseJoinColumns={
	 *      @ORM\JoinColumn(name="administrator", referencedColumnName="id")
	 *      }
	 *      )
	 */
	protected $admins;

	/**
	 *
	 * @var Collection @ORM\ManyToMany(targetEntity="Teacher",
	 *      mappedBy="teacherRoles")
	 *      @ORM\JoinTable(name="teachers_roles",
	 *      joinColumns={
	 *      @ORM\JoinColumn(name="role", referencedColumnName="id")
	 *      },
	 *      inverseJoinColumns={
	 *      @ORM\JoinColumn(name="teacher", referencedColumnName="id")
	 *      }
	 *      )
	 */
	protected $teachers;

	/**
	 *
	 * @var Collection @ORM\ManyToMany(targetEntity="Trainee",
	 *      mappedBy="traineeRoles")
	 *      @ORM\JoinTable(name="trainees_roles",
	 *      joinColumns={
	 *      @ORM\JoinColumn(name="role", referencedColumnName="id")
	 *      },
	 *      inverseJoinColumns={
	 *      @ORM\JoinColumn(name="trainee", referencedColumnName="id")
	 *      }
	 *      )
	 */
	protected $trainees;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->admins = new ArrayCollection();
		$this->teachers = new ArrayCollection();
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
	 * Set name
	 *
	 * @param string $roleName
	 *
	 * @return Role
	 */
	public function setName($roleName)
	{
		$this->name = trim(strtoupper($roleName));

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
	 * Set description
	 *
	 * @param string $roleDescription
	 *
	 * @return Role
	 */
	public function setDescription($roleDescription)
	{
		$this->description = $roleDescription;

		return $this;
	}

	/**
	 * Get description
	 *
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Add Admin
	 *
	 * @param Admin $admin
	 *
	 * @return Role
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
	 * @return Role
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
	 * @return Role
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
	 * @return Role
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
	 * @return Role
	 */
	public function removeTeacher(Teacher $teacher)
	{
		$this->teachers->removeElement($teacher);

		return $this;
	}

	/**
	 * Set Teacher Collection
	 *
	 * @param Collection $teachers
	 *
	 * @return Role
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
	 * @return Role
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
	 * @return Role
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
	 * @return Role
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
	 * (non-PHPdoc) @see
	 * \Symfony\Component\Security\Core\Role\RoleInterface::getRole()
	 *
	 * @return string
	 */
	public function getRole()
	{
		return $this->getName();
	}

	/**
	 * string representation of the object
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->getName();
	}
}
