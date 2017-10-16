<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ilcfrance\Worldspeak\Shared\DataBundle\Model\FCEventClass;
use Ilcfrance\Worldspeak\Shared\ResBundle\Validator as ExtraAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Cours Entity
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *         @ORM\Table(name="courses")
 *         @ORM\Entity(repositoryClass="Ilcfrance\Worldspeak\Shared\DataBundle\Repository\CoursRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_Cours")
 *         @UniqueEntity(fields="codeMA", message="Cours.codeMA.unique")
 *         @Assert\Callback(callback="checkValidTeacher", groups={"teacher"})
 */
class Cours extends FCEventClass
{

    /**
     *
     * @var integer
     */
    const TYPE_UNDEFINED = 0;

    /**
     *
     * @var integer
     */
    const TYPE_EN_VOCAB = 101;

    /**
     *
     * @var integer
     */
    const TYPE_EN_GRAMAR = 102;

    /**
     *
     * @var integer
     */
    const TYPE_EN_VIDEO = 103;

    /**
     *
     * @var integer
     */
    const TYPE_EN_CORRECTION = 104;

    /**
     *
     * @var integer
     */
    const TYPE_EN_OTHER = 105;

    /**
     *
     * @var integer
     */
    const KPI_LOW = 1;

    /**
     *
     * @var integer
     */
    const KPI_MEDIUM = 3;

    /**
     *
     * @var integer
     */
    const KPI_HIGH = 4;

    /**
     *
     * @var integer
     */
    const STATUS_PLANNED = 1;

    /**
     *
     * @var integer
     */
    const STATUS_PLANNED_PENDING = 2;

    /**
     *
     * @var integer
     */
    const STATUS_DONE = 3;

    /**
     *
     * @var integer
     */
    const STATUS_ABSENT = 4;

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
     * @var string @ORM\Column(name="id", type="guid", nullable=false)
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
     * @var TimeCredit @ORM\ManyToOne(targetEntity="TimeCredit", inversedBy="courses")
     *      @ORM\JoinColumns({@ORM\JoinColumn(name="timecredit", referencedColumnName="id")})
     */
    protected $timeCredit;

    /**
     *
     * @var Teacher @ORM\ManyToOne(targetEntity="Teacher", inversedBy="courses")
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="teacher", referencedColumnName="id")
     *      })
     *      @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_Cours_teacher")
     */
    protected $teacher;

    /**
     *
     * @var DateTime @ORM\Column(name="dtcrea", type="datetimetz",
     *      nullable=true)
     */
    protected $dtCrea;

    /**
     *
     * @var integer @ORM\Column(name="ctype", type="integer", nullable=false)
     *      @Assert\Choice(callback="choiceTypeCallback", groups={"admType", "teacherType"})
     */
    protected $type;

    /**
     *
     * @var integer @ORM\Column(name="status", type="integer", nullable=false)
     *      @Assert\Choice(callback="choiceStatusCallback", groups={"admStatus", "teacherStatus"})
     */
    protected $status;

    /**
     *
     * @var DateTime @ORM\Column(name="start", type="datetimetz", nullable=false)
     */
    protected $dtStart;

    /**
     *
     * @var integer @ORM\Column(name="duration", type="bigint", nullable=false)
     */
    protected $duration;

    /**
     *
     * @var string @ORM\Column(name="coursphone", type="text", nullable=true)
     *      @ExtraAssert\Phone(message = "_phone.invalid", groups={"admUpdatePhone", "teacherUpdatePhone"})
     */
    protected $phone;

    /**
     *
     * @var integer @ORM\Column(name="kpihomeworkperformed", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceKPICallback", groups={"admKPI", "teacherKPI"})
     */
    protected $kpiHomeworkPerformed;

    /**
     *
     * @var integer @ORM\Column(name="kpiparticipation", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceKPICallback", groups={"admKPI", "teacherKPI"})
     */
    protected $kpiParticipation;

    /**
     *
     * @var integer @ORM\Column(name="kpivocabularyretention", type="integer",
     *      nullable=true)
     *      @Assert\Choice(callback="choiceKPICallback", groups={"admKPI",
     *      "teacherKPI"})
     */
    protected $kpiVocabularyRetention;

    /**
     *
     * @var integer @ORM\Column(name="kpicorrectionconsideration", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceKPICallback", groups={"admKPI", "teacherKPI"})
     */
    protected $kpiCorrectionConsideration;

    /**
     *
     * @var string @ORM\Column(name="correctionvocabulairy", type="text", nullable=true)
     */
    protected $correctionVocabulairy;

    /**
     *
     * @var string @ORM\Column(name="correctionstructure", type="text", nullable=true)
     */
    protected $correctionStructure;

    /**
     *
     * @var string @ORM\Column(name="correctionprononciation", type="text", nullable=true)
     */
    protected $correctionPrononciation;

    /**
     *
     * @var string @ORM\Column(name="progress", type="text", nullable=true)
     */
    protected $progress;

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
     * @var Collection @ORM\OneToMany(targetEntity="CoursDocument", mappedBy="cours")
     *      @ORM\OrderBy({"dtCrea" = "DESC"})
     *      @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_Cours_documents")
     */
    protected $documents;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="TeacherNotif", mappedBy="cours",
     *      cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     *      @ORM\OrderBy({"dtStart"="DESC"})
     *      @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_Cours_teacherNotifs")
     */
    protected $teacherNotifs;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="TraineeNotif", mappedBy="cours",
     *      cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     *      @ORM\OrderBy({"dtStart"="DESC"})
     *      @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_Cours_traineeNotifs")
     */
    protected $traineeNotifs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dtCrea = new DateTime('now');
        $this->ctype = self::TYPE_UNDEFINED;
        $this->status = self::STATUS_PLANNED;
        $this->duration = 60;

        $this->buggy = self::HEALTH_OK;

        $this->documents = new ArrayCollection();
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
     * Set codeMA
     *
     * @param string $codeMA
     *
     * @return Cours
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
     * Set timeCredit
     *
     * @param TimeCredit $timeCredit
     *
     * @return Cours
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
     * Set teacher
     *
     * @param Teacher $teacher
     *
     * @return Cours
     */
    public function setTeacher(Teacher $teacher = null)
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * Get teacher
     *
     * @return Teacher
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * Set dtCrea
     *
     * @param DateTime $dtCrea
     *
     * @return Cours
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
     * Set type
     *
     * @param integer $type
     *
     * @return Cours
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get ctype
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
     * @return Cours
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
        $now = new DateTime('now');
        if ($now > $this->getDtStart() && $this->status == self::STATUS_PLANNED) {
            return self::STATUS_PLANNED_PENDING;
        }

        return $this->status;
    }

    /**
     * Set dtStart
     *
     * @param DateTime $dtStart
     *
     * @return Cours
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
     * Get end
     *
     * @return DateTime
     */
    public function getDtEnd()
    {
        $end = new DateTime();
        $end->setTimestamp($this->getDtStart()
            ->getTimestamp());
        $modify = '+' . $this->getDuration() . ' minutes';
        $end->modify($modify);

        return $end;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Cours
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Cours
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
     * Set kpiCorrectionConsideration
     *
     * @param integer $kpi
     *
     * @return Cours
     */
    public function setKpiCorrectionConsideration($kpi)
    {
        $this->kpiCorrectionConsideration = $kpi;

        return $this;
    }

    /**
     * Get kpiCorrectionConsideration
     *
     * @return integer
     */
    public function getKpiCorrectionConsideration()
    {
        return $this->kpiCorrectionConsideration;
    }

    /**
     * Set kpiHomeworkPerformed
     *
     * @param integer $kpi
     *
     * @return Cours
     */
    public function setKpiHomeworkPerformed($kpi)
    {
        $this->kpiHomeworkPerformed = $kpi;

        return $this;
    }

    /**
     * Get kpiHomeworkPerformed
     *
     * @return integer
     */
    public function getKpiHomeworkPerformed()
    {
        return $this->kpiHomeworkPerformed;
    }

    /**
     * Set kpiParticipation
     *
     * @param integer $kpi
     *
     * @return Cours
     */
    public function setKpiParticipation($kpi)
    {
        $this->kpiParticipation = $kpi;

        return $this;
    }

    /**
     * Get kpiParticipation
     *
     * @return integer
     */
    public function getKpiParticipation()
    {
        return $this->kpiParticipation;
    }

    /**
     * Set kpiVocabularyRetention
     *
     * @param integer $kpi
     *
     * @return Cours
     */
    public function setKpiVocabularyRetention($kpi)
    {
        $this->kpiVocabularyRetention = $kpi;

        return $this;
    }

    /**
     * Get kpiVocabularyRetention
     *
     * @return integer
     */
    public function getKpiVocabularyRetention()
    {
        return $this->kpiVocabularyRetention;
    }

    /**
     * Set correctionVocabulairy
     *
     * @param string $correctionVocabulairy
     *
     * @return Cours
     */
    public function setCorrectionVocabulairy($correctionVocabulairy)
    {
        $this->correctionVocabulairy = $correctionVocabulairy;

        return $this;
    }

    /**
     * Get correctionVocabulairy
     *
     * @return string
     */
    public function getCorrectionVocabulairy()
    {
        return $this->correctionVocabulairy;
    }

    /**
     * Set correctionStructure
     *
     * @param string $correctionStructure
     *
     * @return Cours
     */
    public function setCorrectionStructure($correctionStructure)
    {
        $this->correctionStructure = $correctionStructure;

        return $this;
    }

    /**
     * Get correctionStructure
     *
     * @return string
     */
    public function getCorrectionStructure()
    {
        return $this->correctionStructure;
    }

    /**
     * Set correctionPrononciation
     *
     * @param string $correctionPrononciation
     *
     * @return Cours
     */
    public function setCorrectionPrononciation($correctionPrononciation)
    {
        $this->correctionPrononciation = $correctionPrononciation;

        return $this;
    }

    /**
     * Get correctionPrononciation
     *
     * @return string
     */
    public function getCorrectionPrononciation()
    {
        return $this->correctionPrononciation;
    }

    /**
     * Set progress
     *
     * @param string $progress
     *
     * @return Cours
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;

        return $this;
    }

    /**
     * Get progress
     *
     * @return string
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * Set comments
     *
     * @param string $comments
     *
     * @return Cours
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
     * Add document
     *
     * @param CoursDocument $document
     *
     * @return Cours
     */
    public function addDocument(CoursDocument $document)
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * Remove document
     *
     * @param CoursDocument $document
     *
     * @return Cours
     */
    public function removeDocument(CoursDocument $document)
    {
        $this->documents->removeElement($document);

        return $this;
    }

    /**
     * Set document Collection
     *
     * @param Collection $documents
     *
     * @return Cours
     */
    public function setDocuments(Collection $documents)
    {
        $this->documents = $documents;

        return $this;
    }

    /**
     * Get documents
     *
     * @return ArrayCollection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Add teacherNotif
     *
     * @param TeacherNotif $teacherNotif
     *
     * @return Cours
     */
    public function addTeacherNotif(TeacherNotif $teacherNotif)
    {
        $this->teacherNotifs[] = $teacherNotif;

        return $this;
    }

    /**
     * Remove teacherNotif
     *
     * @param TeacherNotif $teacherNotif
     *
     * @return Cours
     */
    public function removeTeacherNotif(TeacherNotif $teacherNotif)
    {
        $this->teacherNotifs->removeElement($teacherNotif);

        return $this;
    }

    /**
     * Set teacherNotif Collection
     *
     * @param Collection $teacherNotifs
     *
     * @return Cours
     */
    public function setTeacherNotifs(Collection $teacherNotifs)
    {
        $this->teacherNotifs = $teacherNotifs;

        return $this;
    }

    /**
     * Get teacherNotif ArrayCollection
     *
     * @return ArrayCollection
     */
    public function getTeacherNotifs()
    {
        return $this->teacherNotifs;
    }

    /**
     * Add traineeNotif
     *
     * @param TraineeNotif $traineeNotif
     *
     * @return Cours
     */
    public function addTraineeNotif(TraineeNotif $traineeNotif)
    {
        $this->traineeNotifs[] = $traineeNotif;

        return $this;
    }

    /**
     * Remove traineeNotif
     *
     * @param TraineeNotif $traineeNotif
     *
     * @return Cours
     */
    public function removeTraineeNotif(TraineeNotif $traineeNotif)
    {
        $this->traineeNotifs->removeElement($traineeNotif);

        return $this;
    }

    /**
     * Set traineeNotif Collection
     *
     * @param Collection $traineeNotifs
     *
     * @return Cours
     */
    public function setTraineeNotifs(Collection $traineeNotifs)
    {
        $this->traineeNotifs = $traineeNotifs;

        return $this;
    }

    /**
     * Get traineeNotif ArrayCollection
     *
     * @return ArrayCollection
     */
    public function getTraineeNotifs()
    {
        return $this->traineeNotifs;
    }

    /**
     * Get in Progress
     *
     * @return boolean
     */
    public function getInProgress()
    {
        $now = new DateTime('now');
        if ($now >= $this->getDtStart() && $now <= $this->getDtEnd()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get finished
     *
     * @return boolean
     */
    public function getFinished()
    {
        $now = new DateTime('now');
        if ($now > $this->getDtEnd()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get editable
     *
     * @return boolean
     */
    public function getEditable()
    {
        $inOneDay = new DateTime('now');
        $inOneDay->modify('+1 day');
        if ($inOneDay < $this->getDtStart()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get deletable
     *
     * @return boolean
     */
    public function getDeletable()
    {
        $inOneDay = new DateTime('now');
        $inOneDay->modify('+1 day');
        if ($inOneDay < $this->dtStart) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see FCEventClass::getBackgroundColor()
     */
    public function getBackgroundColor()
    {
        if ($this->getFinished()) {
            if ($this->getStatus() == self::STATUS_PLANNED) {
                return 'grey';
            }
            if ($this->getStatus() == self::STATUS_PLANNED_PENDING) {
                return 'darkred';
            }
            if ($this->getStatus() == self::STATUS_DONE) {
                return 'lightblue';
            }
            if ($this->getStatus() == self::STATUS_ABSENT) {
                return 'red';
            }
        } elseif ($this->getInProgress()) {
            return 'orange';
        } elseif ($this->getEditable() == false) {
            return 'darkblue';
        } else {
            return 'lightgrey';
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see FCEventClass::getBorderColor()
     */
    public function getBorderColor()
    {
        if ($this->getFinished()) {
            if ($this->getStatus() == self::STATUS_PLANNED) {
                return 'grey';
            }
            if ($this->getStatus() == self::STATUS_PLANNED_PENDING) {
                return 'darkred';
            }
            if ($this->getStatus() == self::STATUS_DONE) {
                return 'lightblue';
            }
            if ($this->getStatus() == self::STATUS_ABSENT) {
                return 'red';
            }
        } elseif ($this->getInProgress()) {
            return 'orange';
        } elseif ($this->getEditable() == false) {
            return 'darkblue';
        } else {
            return 'lightgrey';
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see FCEventClass::getTextColor()
     */
    public function getTextColor()
    {
        if ($this->getFinished()) {
            if ($this->getStatus() == self::STATUS_PLANNED) {
                return 'black';
            }
            if ($this->getStatus() == self::STATUS_PLANNED_PENDING) {
                return 'white';
            }
            if ($this->getStatus() == self::STATUS_DONE) {
                return 'black';
            }
            if ($this->getStatus() == self::STATUS_ABSENT) {
                return 'black';
            }
        } elseif ($this->getInProgress()) {
            return 'black';
        } elseif ($this->getEditable() == false) {
            return 'white';
        } else {
            return 'black';
        }
    }

    /**
     * Choice Form type
     *
     * @return array
     */
    public static function choiceType()
    {
        return array(
            'Cours.type.choice.' . self::TYPE_EN_VOCAB => self::TYPE_EN_VOCAB,
            'Cours.type.choice.' . self::TYPE_EN_GRAMAR => self::TYPE_EN_GRAMAR,
            'Cours.type.choice.' . self::TYPE_EN_VIDEO => self::TYPE_EN_VIDEO,
            'Cours.type.choice.' . self::TYPE_EN_CORRECTION => self::TYPE_EN_CORRECTION,
            'Cours.type.choice.' . self::TYPE_EN_OTHER => self::TYPE_EN_OTHER
        );
    }

    /**
     * Choice Validator type
     *
     * @return array
     */
    public static function choiceTypeCallback()
    {
        return array(
            self::TYPE_EN_VOCAB,
            self::TYPE_EN_GRAMAR,
            self::TYPE_EN_VIDEO,
            self::TYPE_EN_CORRECTION,
            self::TYPE_EN_OTHER
        );
    }

    /**
     * Choice Form kpi
     *
     * @return array
     */
    public static function choiceKPI()
    {
        return array(
            'Cours.kpi.choice.' . self::KPI_LOW => self::KPI_LOW,
            'Cours.kpi.choice.' . self::KPI_MEDIUM => self::KPI_MEDIUM,
            'Cours.kpi.choice.' . self::KPI_HIGH => self::KPI_HIGH
        );
    }

    /**
     * Choice Validator kpi
     *
     * @return array
     */
    public static function choiceKPICallback()
    {
        return array(
            self::KPI_LOW,
            self::KPI_MEDIUM,
            self::KPI_HIGH
        );
    }

    /**
     * Choice Form kpi
     *
     * @return array
     */
    public static function choiceStatus()
    {
        return array(
            'Cours.status.choice.' . self::STATUS_PLANNED => self::STATUS_PLANNED,
            'Cours.status.choice.' . self::STATUS_DONE => self::STATUS_DONE,
            'Cours.status.choice.' . self::STATUS_ABSENT => self::STATUS_ABSENT
        );
    }

    /**
     * Choice Form status (for Teachers)
     *
     * @return array
     */
    public static function choiceStatusForTeachers()
    {
        return array(
            'Cours.status.choice.' . self::STATUS_DONE => self::STATUS_DONE,
            'Cours.status.choice.' . self::STATUS_ABSENT => self::STATUS_ABSENT
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
            self::STATUS_PLANNED,
            self::STATUS_DONE,
            self::STATUS_ABSENT
        );
    }

    /**
     * Validator techer
     *
     * @param ExecutionContextInterface $context
     */
    public function checkValidTeacher($context)
    {
        if ($this->getTeacher() != null) {
            // a.definir
            $teacher = $this->getTeacher();
            if ($teacher->getUsername() != 'a.definir' && $teacher->getUsername() != 'variables.formateurs') {
                $canChangeTeacher = true;
                $courses = $teacher->getCourses();
                if (null != $courses) {
                    $dtStart = $this->getDtStart();
                    foreach ($courses as $cours) {
                        $dtStart1 = new DateTime('now');
                        $dtStart1->setTimestamp($cours->getDtStart()
                            ->getTimestamp());

                        $dtStart2 = new DateTime('now');
                        $dtStart2->setTimestamp($cours->getDtStart()
                            ->getTimestamp());

                        $dtStart3 = new DateTime('now');
                        $dtStart3->setTimestamp($cours->getDtStart()
                            ->getTimestamp());

                        $dtStart2 = $dtStart2->modify('+30 minutes');
                        $dtStart3 = $dtStart3->modify('-30 minutes');

                        if (($dtStart == $dtStart1 || $dtStart == $dtStart2 || $dtStart == $dtStart3) && ($cours->getId() != $this->getId())) {
                            $canChangeTeacher = false;
                        }
                    }
                }
                if (!$canChangeTeacher) {
                    $context->addViolationAt('teacher', 'Cours.teacher.invalid', array(), null);
                }
            }
        }
    }

    /**
     * Update the buggy status and notifs
     * @ORM\PreFlush()
     */
    public function updateBuggy()
    {
        $this->setBuggy(self::HEALTH_OK);

        if (null == $this->getPhone() || trim($this->getPhone()) == '') {
            if (null != $this->getTeacher() && null != $this->getTeacher()->getCoursPhone() && trim($this->getTeacher()->getCoursPhone()) != '') {
                $this->setPhone($this->getTeacher()
                    ->getCoursPhone());
            } else {
                $this->setBuggy(self::HEALTH_BUGGY);
            }
        }

        if ($this->getStatus() == self::STATUS_PLANNED_PENDING) {
            $this->setBuggy(self::HEALTH_BUGGY);
        }

        if ($this->getStatus() == self::STATUS_DONE) {
            if (null == $this->getKpiCorrectionConsideration()) {
                $this->setBuggy(self::HEALTH_BUGGY);
            }

            if (null == $this->getKpiHomeworkPerformed()) {
                $this->setBuggy(self::HEALTH_BUGGY);
            }

            if (null == $this->getKpiParticipation()) {
                $this->setBuggy(self::HEALTH_BUGGY);
            }

            if (null == $this->getKpiVocabularyRetention()) {
                $this->setBuggy(self::HEALTH_BUGGY);
            }

            if (null == $this->getCorrectionPrononciation() || trim($this->getCorrectionPrononciation()) == '') {
                $this->setBuggy(self::HEALTH_BUGGY);
            }

            if (null == $this->getCorrectionStructure() || trim($this->getCorrectionStructure()) == '') {
                $this->setBuggy(self::HEALTH_BUGGY);
            }

            if (null == $this->getCorrectionVocabulairy() || trim($this->getCorrectionVocabulairy()) == '') {
                $this->setBuggy(self::HEALTH_BUGGY);
            }
        }

        $now = new DateTime('now');

        $traineeNotifs = $this->getTraineeNotifs();
        if (null == $traineeNotifs) {
            $traineeNotifs = new ArrayCollection();
            $this->traineeNotifs = new ArrayCollection();
        }
        if ($this->getDtStart() > $now) {
            $traineeNotifB24h = null;
            $before24h = new DateTime('now');
            $before24h->setTimestamp($this->getDtStart()
                ->getTimestamp());
            $before24h->modify('-24 hours');

            foreach ($traineeNotifs as $notif) {
                if ($notif->getType() == TraineeNotif::TYPE_EMAIL_24H_BEFORE_COURS) {
                    $traineeNotifB24h = $notif;
                }
            }
            if (null == $traineeNotifB24h) {
                $traineeNotifB24h = new TraineeNotif();
                $traineeNotifB24h->setTrainee($this->getTimeCredit()
                    ->getTrainee());
                $traineeNotifB24h->setTimeCredit($this->getTimeCredit());
                $traineeNotifB24h->setCours($this);
                $traineeNotifB24h->setStatus(TraineeNotif::PENDING);
                $traineeNotifB24h->setType(TraineeNotif::TYPE_EMAIL_24H_BEFORE_COURS);
                $traineeNotifB24h->setDtStart($before24h);
                $this->addTraineeNotif($traineeNotifB24h);
            } elseif ($traineeNotifB24h->getStatus() != TraineeNotif::PENDING && $traineeNotifB24h->getDtStart() != $before24h) {
                $traineeNotifB24h = new TraineeNotif();
                $traineeNotifB24h->setTrainee($this->getTimeCredit()
                    ->getTrainee());
                $traineeNotifB24h->setTimeCredit($this->getTimeCredit());
                $traineeNotifB24h->setCours($this);
                $traineeNotifB24h->setStatus(TraineeNotif::PENDING);
                $traineeNotifB24h->setType(TraineeNotif::TYPE_EMAIL_24H_BEFORE_COURS);
                $traineeNotifB24h->setDtStart($before24h);
                $this->addTraineeNotif($traineeNotifB24h);
            } elseif ($traineeNotifB24h->getStatus() == TraineeNotif::PENDING) {
                $traineeNotifB24h->setTrainee($this->getTimeCredit()
                    ->getTrainee());
                $traineeNotifB24h->setTimeCredit($this->getTimeCredit());
                $traineeNotifB24h->setCours($this);
                $traineeNotifB24h->setStatus(TraineeNotif::PENDING);
                $traineeNotifB24h->setType(TraineeNotif::TYPE_EMAIL_24H_BEFORE_COURS);
                $traineeNotifB24h->setDtStart($before24h);
                $this->addTraineeNotif($traineeNotifB24h);
            }
        } else {
            foreach ($traineeNotifs as $notif) {
                if ($notif->getType() == TraineeNotif::TYPE_EMAIL_24H_BEFORE_COURS && $notif->getStatus() == TraineeNotif::PENDING) {
                    $this->removeTraineeNotif($notif);
                }
            }
        }

        $after1h = new DateTime('now');
        $after1h->setTimestamp($this->getDtEnd()
            ->getTimestamp());

        $teacherNotifs = $this->getTeacherNotifs();
        if (null == $teacherNotifs) {
            $teacherNotifs = new ArrayCollection();
            $this->teacherNotifs = new ArrayCollection();
        }
        if (null != $this->getTeacher()) {
            if ($this->getStatus() == self::STATUS_PLANNED || $this->getStatus() == self::STATUS_PLANNED_PENDING || ($this->getStatus() == self::STATUS_DONE && (null == $this->getCorrectionPrononciation() || null == $this->getCorrectionStructure() || null == $this->getCorrectionVocabulairy() || null == $this->getKpiCorrectionConsideration() || null == $this->getKpiHomeworkPerformed() || null == $this->getKpiParticipation() || null == $this->getKpiVocabularyRetention()))) {

                $teacherNotifTxt1h = null;
                foreach ($teacherNotifs as $notif) {
                    if ($notif->getType() == TeacherNotif::TYPE_TXT_COURS_EDIT) {
                        $teacherNotifTxt1h = $notif;
                    }
                }
                if (null == $teacherNotifTxt1h) {
                    $teacherNotifTxt1h = new TeacherNotif();
                    $teacherNotifTxt1h->setTeacher($this->getTeacher());
                    $teacherNotifTxt1h->setTimeCredit($this->getTimeCredit());
                    $teacherNotifTxt1h->setCours($this);
                    $teacherNotifTxt1h->setStatus(TeacherNotif::PENDING);
                    $teacherNotifTxt1h->setType(TeacherNotif::TYPE_TXT_COURS_EDIT);
                    $teacherNotifTxt1h->setDtStart($after1h);
                    $this->addTeacherNotif($teacherNotifTxt1h);
                } else {
                    $teacherNotifTxt1h->setTeacher($this->getTeacher());
                    $teacherNotifTxt1h->setTimeCredit($this->getTimeCredit());
                    $teacherNotifTxt1h->setCours($this);
                    $teacherNotifTxt1h->setStatus(TeacherNotif::PENDING);
                    $teacherNotifTxt1h->setType(TeacherNotif::TYPE_TXT_COURS_EDIT);
                    $teacherNotifTxt1h->setDtStart($after1h);
                    $this->addTeacherNotif($teacherNotifTxt1h);
                }
            } else {
                foreach ($teacherNotifs as $notif) {
                    if ($notif->getType() == TeacherNotif::TYPE_TXT_COURS_EDIT && $notif->getStatus() == TeacherNotif::PENDING) {
                        $this->removeTeacherNotif($notif);
                    }
                }
            }
        } else {
            foreach ($teacherNotifs as $notif) {
                if ($notif->getType() == TeacherNotif::TYPE_TXT_COURS_EDIT && $notif->getStatus() == TeacherNotif::PENDING) {
                    $this->removeTeacherNotif($notif);
                }
            }
        }

        $after2h = new DateTime('now');
        $after2h->setTimestamp($this->getDtEnd()
            ->getTimestamp());
        $after2h->modify('+1 hour');

        $teacherNotifs = $this->getTeacherNotifs();
        if (null == $teacherNotifs) {
            $teacherNotifs = new ArrayCollection();
        }
        if (null != $this->getTeacher()) {
            if ($this->getStatus() == self::STATUS_PLANNED || $this->getStatus() == self::STATUS_PLANNED_PENDING) {
                $teacherNotifEmail1h = null;
                foreach ($teacherNotifs as $notif) {
                    if ($notif->getType() == TeacherNotif::TYPE_EMAIL_COURS_EDIT) {
                        $teacherNotifEmail1h = $notif;
                    }
                }
                if (null == $teacherNotifEmail1h) {
                    $teacherNotifEmail1h = new TeacherNotif();
                    $teacherNotifEmail1h->setTeacher($this->getTeacher());
                    $teacherNotifEmail1h->setTimeCredit($this->getTimeCredit());
                    $teacherNotifEmail1h->setCours($this);
                    $teacherNotifEmail1h->setStatus(TeacherNotif::PENDING);
                    $teacherNotifEmail1h->setType(TeacherNotif::TYPE_EMAIL_COURS_EDIT);
                    $teacherNotifEmail1h->setDtStart($after2h);
                    $this->addTeacherNotif($teacherNotifEmail1h);
                } elseif ($teacherNotifEmail1h->getStatus() == TeacherNotif::ERROR) {
                    $teacherNotifEmail1h = new TeacherNotif();
                    $teacherNotifEmail1h->setTeacher($this->getTeacher());
                    $teacherNotifEmail1h->setTimeCredit($this->getTimeCredit());
                    $teacherNotifEmail1h->setCours($this);
                    $teacherNotifEmail1h->setStatus(TeacherNotif::PENDING);
                    $teacherNotifEmail1h->setType(TeacherNotif::TYPE_EMAIL_COURS_EDIT);
                    $teacherNotifEmail1h->setDtStart($after2h);
                    $this->addTeacherNotif($teacherNotifEmail1h);
                } elseif ($teacherNotifEmail1h->getStatus() == TeacherNotif::PENDING) {
                    $teacherNotifEmail1h->setTeacher($this->getTeacher());
                    $teacherNotifEmail1h->setTimeCredit($this->getTimeCredit());
                    $teacherNotifEmail1h->setCours($this);
                    $teacherNotifEmail1h->setStatus(TeacherNotif::PENDING);
                    $teacherNotifEmail1h->setType(TeacherNotif::TYPE_EMAIL_COURS_EDIT);
                    $teacherNotifEmail1h->setDtStart($after2h);
                    $this->addTeacherNotif($teacherNotifEmail1h);
                }
            } else {
                foreach ($teacherNotifs as $notif) {
                    if ($notif->getType() == TeacherNotif::TYPE_EMAIL_COURS_EDIT && $notif->getStatus() == TeacherNotif::PENDING) {
                        $this->removeTeacherNotif($notif);
                    }
                }
            }
        } else {
            foreach ($teacherNotifs as $notif) {
                if ($notif->getType() == TeacherNotif::TYPE_EMAIL_COURS_EDIT && $notif->getStatus() == TeacherNotif::PENDING) {
                    $this->removeTeacherNotif($notif);
                }
            }
        }
    }
}
