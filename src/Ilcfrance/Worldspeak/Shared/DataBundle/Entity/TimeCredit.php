<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Entity;

use DateTime;
use Ilcfrance\Worldspeak\Shared\ResBundle\Validator as ExtraAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TimeCredit Entity
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *         @ORM\Table(name="timecredits")
 *         @ORM\Entity(repositoryClass="Ilcfrance\Worldspeak\Shared\DataBundle\Repository\TimeCreditRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_TimeCredit")
 *         @Assert\Callback(callback="checkValidTotalHours", groups={"totalHours"})
 */
class TimeCredit
{

    /**
     *
     * @var integer
     */
    const FTYPE_UNDEFINED = 0;

    /**
     *
     * @var integer
     */
    const FTYPE_EN = 100;

    /**
     *
     * @var integer
     */
    const LEVEL_UNDEFINED = 0;

    /**
     *
     * @var integer
     */
    const LEVEL_EN_LOW = 11;

    /**
     *
     * @var integer
     */
    const LEVEL_EN_MEDIUM = 12;

    /**
     *
     * @var integer
     */
    const LEVEL_EN_HIGH = 13;

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
    const SHOWREPORT_DONTSHOW = 1;

    /**
     *
     * @var integer
     */
    const SHOWREPORT_SHOW = 2;

    /**
     *
     * @var integer
     */
    const IMPROVEMENT_NOTREQUIRED = 1;

    /**
     *
     * @var integer
     */
    const IMPROVEMENT_REQUIRED = 2;

    /**
     *
     * @var integer
     */
    const NOTIFYBYMAIL_NOTSENT = 1;

    /**
     *
     * @var integer
     */
    const NOTIFYBYMAIL_SENT = 2;

    /**
     *
     * @var integer
     */
    const NOTIFYBYMAIL_DISABLED = 3;

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
    const ENDREPORT_LOW = 1;

    /**
     *
     * @var integer
     */
    const ENDREPORT_MEDIUM = 3;

    /**
     *
     * @var integer
     */
    const ENDREPORT_HIGH = 4;

    /**
     *
     * @var integer
     */
    const SURVEY_1 = 1;

    /**
     *
     * @var integer
     */
    const SURVEY_2 = 2;

    /**
     *
     * @var integer
     */
    const SURVEY_3 = 3;

    /**
     *
     * @var integer
     */
    const SURVEY_4 = 4;

    /**
     *
     * @var integer
     */
    const SURVEY_5 = 5;

    /**
     *
     * @var integer
     */
    const SURVEY_NOTFILLED = 1;

    /**
     *
     * @var integer
     */
    const SURVEY_FILLED = 2;

    /**
     *
     * @var integer
     */
    const STATUS_NEW = 1;

    /**
     *
     * @var integer
     */
    const STATUS_STARTED = 2;

    /**
     *
     * @var integer
     */
    const STATUS_FULL_PLANIFIED = 3;

    /**
     *
     * @var integer
     */
    const STATUS_FINISHED = 4;

    /**
     *
     * @var integer
     */
    const STATUS_FULL_FINISHED = 5;

    /**
     *
     * @var integer
     */
    const STATUS_DEADLINE_EXCEEDED = 6;

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
     * @var Trainee @ORM\ManyToOne(targetEntity="Trainee", inversedBy="credits")
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="trainee", referencedColumnName="id")
     *      })
     *      @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_TimeCredit_trainee")
     */
    protected $trainee;

    /**
     *
     * @var DateTime @ORM\Column(name="dtcrea", type="datetimetz",
     *      nullable=true)
     */
    protected $dtCrea;

    /**
     *
     * @var integer @ORM\Column(name="ftype", type="integer", nullable=false)
     *      @Assert\Choice(callback="choiceFtypeCallback", groups={"admCreation", "ftype"})
     */
    protected $ftype;

    /**
     *
     * @var integer @ORM\Column(name="totalhours", type="bigint", nullable=false)
     *      @Assert\GreaterThan(value=0, groups={"admCreation", "totalHours"})
     */
    protected $totalHours;

    /**
     *
     * @var integer @ORM\Column(name="reservedhours", type="bigint", nullable=false)
     */
    protected $reservedHours;

    /**
     *
     * @var integer @ORM\Column(name="donehours", type="bigint", nullable=false)
     */
    protected $doneHours;

    /**
     *
     * @var integer @ORM\Column(name="losthours", type="bigint", nullable=false)
     */
    protected $lostHours;

    /**
     *
     * @var DateTime @ORM\Column(name="deadline", type="date", nullable=true)
     *      @Assert\Date(groups={"admCreation", "deadLine"})
     *      @ExtraAssert\DateTimeGreaterThan(value = "now", message="TimeCredit.deadLine.graterThanNow",
     *      groups={"admCreation", "deadLine"})
     */
    protected $deadLine;

    /**
     *
     * @var integer @ORM\Column(name="level", type="integer", nullable=false)
     *      @Assert\Choice(callback="choiceLevelCallback", groups={"admCreation", "level"})
     */
    protected $level;

    /**
     *
     * @var string @ORM\Column(name="cefbegin", type="text", nullable=true)
     *      @Assert\Choice(callback="choiceCefCallback", groups={"admCreation", "cefBegin"})
     */
    protected $cefBegin;

    /**
     *
     * @var string @ORM\Column(name="cefend", type="text", nullable=true)
     *      @Assert\Choice(callback="choiceCefCallback", groups={"cefEnd"})
     */
    protected $cefEnd;

    /**
     *
     * @var string @ORM\Column(name="objectives", type="text", nullable=true)
     */
    protected $objectives;

    /**
     *
     * @var string @ORM\Column(name="comments", type="text", nullable=true)
     */
    protected $comments;

    /**
     *
     * @var integer @ORM\Column(name="creationmail", type="integer", nullable=false)
     *      @Assert\Choice(callback="choiceNotifyByMailCallback", groups={"admCreation"})
     */
    protected $notifyByMail;

    /**
     *
     * @var integer @ORM\Column(name="forcedkpihomeworkperformed", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceKPICallback", groups={"admKPI"})
     */
    protected $forcedKpiHomeworkPerformed;

    /**
     *
     * @var integer @ORM\Column(name="forcedkpiparticipation", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceKPICallback", groups={"admKPI"})
     */
    protected $forcedKpiParticipation;

    /**
     *
     * @var integer @ORM\Column(name="forcedkpivocabularyretention", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceKPICallback", groups={"admKPI"})
     */
    protected $forcedKpiVocabularyRetention;

    /**
     *
     * @var integer @ORM\Column(name="forcedkpicorrectionconsideration", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceKPICallback", groups={"admKPI"})
     */
    protected $forcedKpiCorrectionConsideration;

    /**
     *
     * @var integer @ORM\Column(name="progress1", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceEndReportCallback", groups={"endReport"})
     */
    protected $progress1;

    /**
     *
     * @var integer @ORM\Column(name="progress2", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceEndReportCallback", groups={"endReport"})
     */
    protected $progress2;

    /**
     *
     * @var integer @ORM\Column(name="progress3", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceEndReportCallback", groups={"endReport"})
     */
    protected $progress3;

    /**
     *
     * @var integer @ORM\Column(name="progress4", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceEndReportCallback", groups={"endReport"})
     */
    protected $progress4;

    /**
     *
     * @var integer @ORM\Column(name="progress5", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceEndReportCallback", groups={"endReport"})
     */
    protected $progress5;

    /**
     *
     * @var integer @ORM\Column(name="progress6", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceEndReportCallback", groups={"endReport"})
     */
    protected $progress6;

    /**
     *
     * @var integer @ORM\Column(name="progress7", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceEndReportCallback", groups={"endReport"})
     */
    protected $progress7;

    /**
     *
     * @var integer @ORM\Column(name="leveldesc1", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceEndReportCallback", groups={"endReport"})
     */
    protected $levelDesc1;

    /**
     *
     * @var integer @ORM\Column(name="leveldesc2", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceEndReportCallback", groups={"endReport"})
     */
    protected $levelDesc2;

    /**
     *
     * @var integer @ORM\Column(name="leveldesc3", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceEndReportCallback", groups={"endReport"})
     */
    protected $levelDesc3;

    /**
     *
     * @var integer @ORM\Column(name="leveldesc4", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceEndReportCallback", groups={"endReport"})
     */
    protected $levelDesc4;

    /**
     *
     * @var integer @ORM\Column(name="leveldesc5", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceEndReportCallback", groups={"endReport"})
     */
    protected $levelDesc5;

    /**
     *
     * @var integer @ORM\Column(name="leveldesc6", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceEndReportCallback", groups={"endReport"})
     */
    protected $levelDesc6;

    /**
     *
     * @var integer @ORM\Column(name="leveldesc7", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceEndReportCallback", groups={"endReport"})
     */
    protected $levelDesc7;

    /**
     *
     * @var integer @ORM\Column(name="improvement1", type="smallint", nullable=false)
     *      @Assert\Choice(callback="choiceImprovementCallback", groups={"endReport"})
     */
    protected $improvement1;

    /**
     *
     * @var integer @ORM\Column(name="improvement2", type="smallint", nullable=false)
     *      @Assert\Choice(callback="choiceImprovementCallback", groups={"endReport"})
     */
    protected $improvement2;

    /**
     *
     * @var integer @ORM\Column(name="improvement3", type="smallint", nullable=false)
     *      @Assert\Choice(callback="choiceImprovementCallback", groups={"endReport"})
     */
    protected $improvement3;

    /**
     *
     * @var integer @ORM\Column(name="improvement4", type="smallint", nullable=false)
     *      @Assert\Choice(callback="choiceImprovementCallback", groups={"endReport"})
     */
    protected $improvement4;

    /**
     *
     * @var integer @ORM\Column(name="improvement5", type="smallint", nullable=false)
     *      @Assert\Choice(callback="choiceImprovementCallback", groups={"endReport"})
     */
    protected $improvement5;

    /**
     *
     * @var string @ORM\Column(name="lastteacherreport", type="text", nullable=true)
     */
    protected $lastTeacherReport;

    /**
     *
     * @var integer @ORM\Column(name="locked", type="smallint", nullable=false)
     *      @Assert\Choice(callback="choiceLockoutCallback", groups={"lockout"})
     */
    protected $lockout;

    /**
     *
     * @var integer @ORM\Column(name="showreport", type="smallint", nullable=false)
     *      @Assert\Choice(callback="choiceShowReportCallback", groups={"showReport"})
     */
    protected $showReport;

    /**
     *
     * @var integer @ORM\Column(name="surveybeginq01", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceSurveyCallback", groups={"surveyBegin"})
     */
    protected $surveyBeginQ01;

    /**
     *
     * @var integer @ORM\Column(name="surveybeginq02", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceSurveyCallback", groups={"surveyBegin"})
     */
    protected $surveyBeginQ02;

    /**
     *
     * @var integer @ORM\Column(name="surveybeginq03", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceSurveyCallback", groups={"surveyBegin"})
     */
    protected $surveyBeginQ03;

    /**
     *
     * @var integer @ORM\Column(name="surveybeginq04", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceSurveyCallback", groups={"surveyBegin"})
     */
    protected $surveyBeginQ04;

    /**
     *
     * @var integer @ORM\Column(name="surveybeginq05", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceSurveyCallback", groups={"surveyBegin"})
     */
    protected $surveyBeginQ05;

    /**
     *
     * @var integer @ORM\Column(name="surveybeginq06", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceSurveyCallback", groups={"surveyBegin"})
     */
    protected $surveyBeginQ06;

    /**
     *
     * @var string @ORM\Column(name="surveybeginq07", type="text", nullable=true)
     */
    protected $surveyBeginQ07;

    /**
     *
     * @var string @ORM\Column(name="surveybeginq08", type="text", nullable=true)
     */
    protected $surveyBeginQ08;

    /**
     *
     * @var integer @ORM\Column(name="surveybegin", type="smallint", nullable=false)
     *      @Assert\Choice(callback="choiceSurveyFillCallback", groups={"surveyBeginFill"})
     */
    protected $surveyBegin;

    /**
     *
     * @var integer @ORM\Column(name="surveyendq01", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceSurveyCallback", groups={"surveyEnd"})
     */
    protected $surveyEndQ01;

    /**
     *
     * @var integer @ORM\Column(name="surveyendq02", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceSurveyCallback", groups={"surveyEnd"})
     */
    protected $surveyEndQ02;

    /**
     *
     * @var integer @ORM\Column(name="surveyendq03", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceSurveyCallback", groups={"surveyEnd"})
     */
    protected $surveyEndQ03;

    /**
     *
     * @var integer @ORM\Column(name="surveyendq04", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceSurveyCallback", groups={"surveyEnd"})
     */
    protected $surveyEndQ04;

    /**
     *
     * @var integer @ORM\Column(name="surveyendq05", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceSurveyCallback", groups={"surveyEnd"})
     */
    protected $surveyEndQ05;

    /**
     *
     * @var integer @ORM\Column(name="surveyendq06", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceSurveyCallback", groups={"surveyEnd"})
     */
    protected $surveyEndQ06;

    /**
     *
     * @var integer @ORM\Column(name="surveyendq07", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceSurveyCallback", groups={"surveyEnd"})
     */
    protected $surveyEndQ07;

    /**
     *
     * @var integer @ORM\Column(name="surveyendq08", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceSurveyCallback", groups={"surveyEnd"})
     */
    protected $surveyEndQ08;

    /**
     *
     * @var integer @ORM\Column(name="surveyendq09", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceSurveyCallback", groups={"surveyEnd"})
     */
    protected $surveyEndQ09;

    /**
     *
     * @var string @ORM\Column(name="surveyendq10", type="text", nullable=true)
     */
    protected $surveyEndQ10;

    /**
     *
     * @var integer @ORM\Column(name="surveyend", type="smallint", nullable=false)
     *      @Assert\Choice(callback="choiceSurveyFillCallback", groups={"surveyEndFill"})
     */
    protected $surveyEnd;

    /**
     *
     * @var integer @ORM\Column(name="buggy", type="integer", nullable=false)
     */
    protected $buggy;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="Cours", mappedBy="timeCredit")
     *      @ORM\OrderBy({"dtStart"="DESC"})
     */
    protected $courses;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="TimeCreditDocument", mappedBy="timeCredit")
     *      @ORM\OrderBy({"dtCrea"="DESC"})
     *      @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_TimeCredit_documents")
     */
    protected $documents;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="AdminNotif", mappedBy="timeCredit",
     *      cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     *      @ORM\OrderBy({"dtStart"="DESC"})
     *      @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_TimeCredit_adminNotifs")
     */
    protected $adminNotifs;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="TeacherNotif", mappedBy="timeCredit",
     *      cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     *      @ORM\OrderBy({"dtStart"="DESC"})
     *      @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_TimeCredit_teacherNotifs")
     */
    protected $teacherNotifs;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="TraineeNotif", mappedBy="timeCredit",
     *      cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     *      @ORM\OrderBy({"dtStart"="DESC"})
     *      @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_TimeCredit_traineeNotifs")
     */
    protected $traineeNotifs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dtCrea = new DateTime('now');
        $this->ftype = self::FTYPE_UNDEFINED;
        $this->reservedHours = 0;
        $this->doneHours = 0;
        $this->lostHours = 0;
        $this->level = self::LEVEL_UNDEFINED;
        $this->lockout = self::LOCKOUT_UNLOCKED;
        $this->notifyByMail = self::NOTIFYBYMAIL_NOTSENT;

        $this->improvement1 = self::IMPROVEMENT_NOTREQUIRED;
        $this->improvement2 = self::IMPROVEMENT_NOTREQUIRED;
        $this->improvement3 = self::IMPROVEMENT_NOTREQUIRED;
        $this->improvement4 = self::IMPROVEMENT_NOTREQUIRED;
        $this->improvement5 = self::IMPROVEMENT_NOTREQUIRED;

        $this->showReport = self::SHOWREPORT_DONTSHOW;
        $this->surveyBegin = self::SURVEY_NOTFILLED;
        $this->surveyEnd = self::SURVEY_NOTFILLED;

        $this->buggy = self::HEALTH_OK;

        $this->courses = new ArrayCollection();
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
     * Set trainee
     *
     * @param Trainee $trainee
     *
     * @return TimeCredit
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
     * Set dtcrea
     *
     * @param DateTime $dtcrea
     *
     * @return TimeCredit
     */
    public function setDtCrea(\DateTime $dtcrea)
    {
        $this->dtCrea = $dtcrea;

        return $this;
    }

    /**
     * Get dtcrea
     *
     * @return DateTime
     */
    public function getDtCrea()
    {
        return $this->dtCrea;
    }

    /**
     * Set ftype
     *
     * @param integer $type
     *
     * @return TimeCredit
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
     * Set totalHours
     *
     * @param integer $totalHours
     *
     * @return TimeCredit
     */
    public function setTotalHours($totalHours)
    {
        $this->totalHours = $totalHours;

        return $this;
    }

    /**
     * Get totalHours
     *
     * @return integer
     */
    public function getTotalHours()
    {
        return $this->totalHours;
    }

    /**
     * Set reservedHours
     *
     * @param integer $reservedHours
     *
     * @return TimeCredit
     */
    public function setReservedHours($reservedHours)
    {
        $this->reservedHours = $reservedHours;

        return $this;
    }

    /**
     * Get reservedHours
     *
     * @return integer
     */
    public function getReservedHours()
    {
        return $this->reservedHours;
    }

    /**
     * Set doneHours
     *
     * @param integer $doneHours
     *
     * @return TimeCredit
     */
    public function setDoneHours($doneHours)
    {
        $this->doneHours = $doneHours;

        return $this;
    }

    /**
     * Get doneHours
     *
     * @return integer
     */
    public function getDoneHours()
    {
        return $this->doneHours;
    }

    /**
     * Set lostHours
     *
     * @param integer $lostHours
     *
     * @return TimeCredit
     */
    public function setLostHours($lostHours)
    {
        $this->lostHours = $lostHours;

        return $this;
    }

    /**
     * Get lostHours
     *
     * @return integer
     */
    public function getLostHours()
    {
        return $this->lostHours;
    }

    /**
     * Set deadLine
     *
     * @param DateTime $deadLine
     *
     * @return TimeCredit
     */
    public function setDeadLine(\DateTime $deadLine = null)
    {
        $this->deadLine = $deadLine;

        return $this;
    }

    /**
     * Get deadLine
     *
     * @return DateTime
     */
    public function getDeadLine()
    {
        return $this->deadLine;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return TimeCredit
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set lockout
     *
     * @param integer $lockout
     *
     * @return TimeCredit
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
     * Set cefBegin
     *
     * @param string $cefBegin
     *
     * @return TimeCredit
     */
    public function setCefBegin($cefBegin)
    {
        $this->cefBegin = $cefBegin;

        return $this;
    }

    /**
     * Get cefBegin
     *
     * @return string
     */
    public function getCefBegin()
    {
        return $this->cefBegin;
    }

    /**
     * Set cefEnd
     *
     * @param string $cefEnd
     *
     * @return TimeCredit
     */
    public function setCefEnd($cefEnd)
    {
        $this->cefEnd = $cefEnd;

        return $this;
    }

    /**
     * Get cefEnd
     *
     * @return string
     */
    public function getCefEnd()
    {
        return $this->cefEnd;
    }

    /**
     * Set objectives
     *
     * @param string $objectives
     *
     * @return TimeCredit
     */
    public function setObjectives($objectives)
    {
        $this->objectives = $objectives;

        return $this;
    }

    /**
     * Get objectives
     *
     * @return string
     */
    public function getObjectives()
    {
        return $this->objectives;
    }

    /**
     * Set comments
     *
     * @param string $comments
     *
     * @return TimeCredit
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
     * Set notifyByMail
     *
     * @param integer $notifyByMail
     *
     * @return TimeCredit
     */
    public function setNotifyByMail($notifyByMail)
    {
        $this->notifyByMail = $notifyByMail;

        return $this;
    }

    /**
     * Get notifyByMail
     *
     * @return integer
     */
    public function getNotifyByMail()
    {
        return $this->notifyByMail;
    }

    /**
     * Set forcedKpiCorrectionConsideration
     *
     * @param integer $kpi
     *
     * @return TimeCredit
     */
    public function setForcedKpiCorrectionConsideration($kpi)
    {
        $this->forcedKpiCorrectionConsideration = $kpi;

        return $this;
    }

    /**
     * Get forcedKpiCorrectionConsideration
     *
     * @return integer
     */
    public function getForcedKpiCorrectionConsideration()
    {
        return $this->forcedKpiCorrectionConsideration;
    }

    /**
     * Set forcedKpiHomeworkPerformed
     *
     * @param integer $kpi
     *
     * @return TimeCredit
     */
    public function setForcedKpiHomeworkPerformed($kpi)
    {
        $this->forcedKpiHomeworkPerformed = $kpi;

        return $this;
    }

    /**
     * Get forcedKpiHomeworkPerformed
     *
     * @return integer
     */
    public function getForcedKpiHomeworkPerformed()
    {
        return $this->forcedKpiHomeworkPerformed;
    }

    /**
     * Set forcedKpiParticipation
     *
     * @param integer $kpi
     *
     * @return TimeCredit
     */
    public function setForcedKpiParticipation($kpi)
    {
        $this->forcedKpiParticipation = $kpi;

        return $this;
    }

    /**
     * Get forcedKpiParticipation
     *
     * @return integer
     */
    public function getForcedKpiParticipation()
    {
        return $this->forcedKpiParticipation;
    }

    /**
     * Set forcedKpiVocabularyRetention
     *
     * @param integer $kpi
     *
     * @return TimeCredit
     */
    public function setForcedKpiVocabularyRetention($kpi)
    {
        $this->forcedKpiVocabularyRetention = $kpi;

        return $this;
    }

    /**
     * Get forcedKpiVocabularyRetention
     *
     * @return integer
     */
    public function getForcedKpiVocabularyRetention()
    {
        return $this->forcedKpiVocabularyRetention;
    }

    /**
     * Set progress1
     *
     * @param integer $progress1
     *
     * @return TimeCredit
     */
    public function setProgress1($progress1)
    {
        $this->progress1 = $progress1;

        return $this;
    }

    /**
     * Get progress1
     *
     * @return integer
     */
    public function getProgress1()
    {
        return $this->progress1;
    }

    /**
     * Set progress2
     *
     * @param integer $progress2
     *
     * @return TimeCredit
     */
    public function setProgress2($progress2)
    {
        $this->progress2 = $progress2;

        return $this;
    }

    /**
     * Get progress2
     *
     * @return integer
     */
    public function getProgress2()
    {
        return $this->progress2;
    }

    /**
     * Set progress3
     *
     * @param integer $progress3
     *
     * @return TimeCredit
     */
    public function setProgress3($progress3)
    {
        $this->progress3 = $progress3;

        return $this;
    }

    /**
     * Get progress3
     *
     * @return integer
     */
    public function getProgress3()
    {
        return $this->progress3;
    }

    /**
     * Set progress4
     *
     * @param integer $progress4
     *
     * @return TimeCredit
     */
    public function setProgress4($progress4)
    {
        $this->progress4 = $progress4;

        return $this;
    }

    /**
     * Get progress4
     *
     * @return integer
     */
    public function getProgress4()
    {
        return $this->progress4;
    }

    /**
     * Set progress5
     *
     * @param integer $progress5
     *
     * @return TimeCredit
     */
    public function setProgress5($progress5)
    {
        $this->progress5 = $progress5;

        return $this;
    }

    /**
     * Get progress5
     *
     * @return integer
     */
    public function getProgress5()
    {
        return $this->progress5;
    }

    /**
     * Set progress6
     *
     * @param integer $progress6
     *
     * @return TimeCredit
     */
    public function setProgress6($progress6)
    {
        $this->progress6 = $progress6;

        return $this;
    }

    /**
     * Get progress6
     *
     * @return integer
     */
    public function getProgress6()
    {
        return $this->progress6;
    }

    /**
     * Set progress7
     *
     * @param integer $progress7
     *
     * @return TimeCredit
     */
    public function setProgress7($progress7)
    {
        $this->progress7 = $progress7;

        return $this;
    }

    /**
     * Get progress7
     *
     * @return integer
     */
    public function getProgress7()
    {
        return $this->progress7;
    }

    /**
     * Set levelDesc1
     *
     * @param integer $levelDesc1
     *
     * @return TimeCredit
     */
    public function setLevelDesc1($levelDesc1)
    {
        $this->levelDesc1 = $levelDesc1;

        return $this;
    }

    /**
     * Get levelDesc1
     *
     * @return integer
     */
    public function getLevelDesc1()
    {
        return $this->levelDesc1;
    }

    /**
     * Set levelDesc2
     *
     * @param integer $levelDesc2
     *
     * @return TimeCredit
     */
    public function setLevelDesc2($levelDesc2)
    {
        $this->levelDesc2 = $levelDesc2;

        return $this;
    }

    /**
     * Get levelDesc2
     *
     * @return integer
     */
    public function getLevelDesc2()
    {
        return $this->levelDesc2;
    }

    /**
     * Set levelDesc3
     *
     * @param integer $levelDesc3
     *
     * @return TimeCredit
     */
    public function setLevelDesc3($levelDesc3)
    {
        $this->levelDesc3 = $levelDesc3;

        return $this;
    }

    /**
     * Get levelDesc3
     *
     * @return integer
     */
    public function getLevelDesc3()
    {
        return $this->levelDesc3;
    }

    /**
     * Set levelDesc4
     *
     * @param integer $levelDesc4
     *
     * @return TimeCredit
     */
    public function setLevelDesc4($levelDesc4)
    {
        $this->levelDesc4 = $levelDesc4;

        return $this;
    }

    /**
     * Get levelDesc4
     *
     * @return integer
     */
    public function getLevelDesc4()
    {
        return $this->levelDesc4;
    }

    /**
     * Set levelDesc5
     *
     * @param integer $levelDesc5
     *
     * @return TimeCredit
     */
    public function setLevelDesc5($levelDesc5)
    {
        $this->levelDesc5 = $levelDesc5;

        return $this;
    }

    /**
     * Get levelDesc5
     *
     * @return integer
     */
    public function getLevelDesc5()
    {
        return $this->levelDesc5;
    }

    /**
     * Set levelDesc6
     *
     * @param integer $levelDesc6
     *
     * @return TimeCredit
     */
    public function setLevelDesc6($levelDesc6)
    {
        $this->levelDesc6 = $levelDesc6;

        return $this;
    }

    /**
     * Get levelDesc6
     *
     * @return integer
     */
    public function getLevelDesc6()
    {
        return $this->levelDesc6;
    }

    /**
     * Set levelDesc7
     *
     * @param integer $levelDesc7
     *
     * @return TimeCredit
     */
    public function setLevelDesc7($levelDesc7)
    {
        $this->levelDesc7 = $levelDesc7;

        return $this;
    }

    /**
     * Get levelDesc7
     *
     * @return integer
     */
    public function getLevelDesc7()
    {
        return $this->levelDesc7;
    }

    /**
     * Set improvement1
     *
     * @param integer $improvement1
     *
     * @return TimeCredit
     */
    public function setImprovement1($improvement1)
    {
        $this->improvement1 = $improvement1;

        return $this;
    }

    /**
     * Get improvement1
     *
     * @return integer
     */
    public function getImprovement1()
    {
        return $this->improvement1;
    }

    /**
     * Set improvement2
     *
     * @param integer $improvement2
     *
     * @return TimeCredit
     */
    public function setImprovement2($improvement2)
    {
        $this->improvement2 = $improvement2;

        return $this;
    }

    /**
     * Get improvement2
     *
     * @return integer
     */
    public function getImprovement2()
    {
        return $this->improvement2;
    }

    /**
     * Set improvement3
     *
     * @param integer $improvement3
     *
     * @return TimeCredit
     */
    public function setImprovement3($improvement3)
    {
        $this->improvement3 = $improvement3;

        return $this;
    }

    /**
     * Get improvement3
     *
     * @return integer
     */
    public function getImprovement3()
    {
        return $this->improvement3;
    }

    /**
     * Set improvement4
     *
     * @param integer $improvement4
     *
     * @return TimeCredit
     */
    public function setImprovement4($improvement4)
    {
        $this->improvement4 = $improvement4;

        return $this;
    }

    /**
     * Get improvement4
     *
     * @return integer
     */
    public function getImprovement4()
    {
        return $this->improvement4;
    }

    /**
     * Set improvement5
     *
     * @param integer $improvement5
     *
     * @return TimeCredit
     */
    public function setImprovement5($improvement5)
    {
        $this->improvement5 = $improvement5;

        return $this;
    }

    /**
     * Get improvement5
     *
     * @return integer
     */
    public function getImprovement5()
    {
        return $this->improvement5;
    }

    /**
     * Set lastTeacherReport
     *
     * @param string $lastTeacherReport
     *
     * @return TimeCredit
     */
    public function setLastTeacherReport($lastTeacherReport)
    {
        $this->lastTeacherReport = $lastTeacherReport;

        return $this;
    }

    /**
     * Get lastTeacherReport
     *
     * @return string
     */
    public function getLastTeacherReport()
    {
        return $this->lastTeacherReport;
    }

    /**
     * Set showReport
     *
     * @param integer $showReport
     *
     * @return TimeCredit
     */
    public function setShowReport($showReport)
    {
        $this->showReport = $showReport;

        return $this;
    }

    /**
     * Get showReport
     *
     * @return integer
     */
    public function getShowReport()
    {
        return $this->showReport;
    }

    /**
     * Set surveyBeginQ01
     *
     * @param integer $surveyBeginQ01
     *
     * @return TimeCredit
     */
    public function setSurveyBeginQ01($surveyBeginQ01)
    {
        $this->surveyBeginQ01 = $surveyBeginQ01;

        return $this;
    }

    /**
     * Get surveyBeginQ01
     *
     * @return integer
     */
    public function getSurveyBeginQ01()
    {
        return $this->surveyBeginQ01;
    }

    /**
     * Set surveyBeginQ02
     *
     * @param integer $surveyBeginQ02
     *
     * @return TimeCredit
     */
    public function setSurveyBeginQ02($surveyBeginQ02)
    {
        $this->surveyBeginQ02 = $surveyBeginQ02;

        return $this;
    }

    /**
     * Get surveyBeginQ02
     *
     * @return integer
     */
    public function getSurveyBeginQ02()
    {
        return $this->surveyBeginQ02;
    }

    /**
     * Set surveyBeginQ03
     *
     * @param integer $surveyBeginQ03
     *
     * @return TimeCredit
     */
    public function setSurveyBeginQ03($surveyBeginQ03)
    {
        $this->surveyBeginQ03 = $surveyBeginQ03;

        return $this;
    }

    /**
     * Get surveyBeginQ03
     *
     * @return integer
     */
    public function getSurveyBeginQ03()
    {
        return $this->surveyBeginQ03;
    }

    /**
     * Set surveyBeginQ04
     *
     * @param integer $surveyBeginQ04
     *
     * @return TimeCredit
     */
    public function setSurveyBeginQ04($surveyBeginQ04)
    {
        $this->surveyBeginQ04 = $surveyBeginQ04;

        return $this;
    }

    /**
     * Get surveyBeginQ04
     *
     * @return integer
     */
    public function getSurveyBeginQ04()
    {
        return $this->surveyBeginQ04;
    }

    /**
     * Set surveyBeginQ05
     *
     * @param integer $surveyBeginQ05
     *
     * @return TimeCredit
     */
    public function setSurveyBeginQ05($surveyBeginQ05)
    {
        $this->surveyBeginQ05 = $surveyBeginQ05;

        return $this;
    }

    /**
     * Get surveyBeginQ05
     *
     * @return integer
     */
    public function getSurveyBeginQ05()
    {
        return $this->surveyBeginQ05;
    }

    /**
     * Set surveyBeginQ06
     *
     * @param integer $surveyBeginQ06
     *
     * @return TimeCredit
     */
    public function setSurveyBeginQ06($surveyBeginQ06)
    {
        $this->surveyBeginQ06 = $surveyBeginQ06;

        return $this;
    }

    /**
     * Get surveyBeginQ06
     *
     * @return integer
     */
    public function getSurveyBeginQ06()
    {
        return $this->surveyBeginQ06;
    }

    /**
     * Set surveyBeginQ07
     *
     * @param string $surveyBeginQ07
     *
     * @return TimeCredit
     */
    public function setSurveyBeginQ07($surveyBeginQ07)
    {
        $this->surveyBeginQ07 = $surveyBeginQ07;

        return $this;
    }

    /**
     * Get surveyBeginQ07
     *
     * @return string
     */
    public function getSurveyBeginQ07()
    {
        return $this->surveyBeginQ07;
    }

    /**
     * Set surveyBeginQ08
     *
     * @param string $surveyBeginQ08
     *
     * @return TimeCredit
     */
    public function setSurveyBeginQ08($surveyBeginQ08)
    {
        $this->surveyBeginQ08 = $surveyBeginQ08;

        return $this;
    }

    /**
     * Get surveyBeginQ08
     *
     * @return string
     */
    public function getSurveyBeginQ08()
    {
        return $this->surveyBeginQ08;
    }

    /**
     * Set surveyBegin
     *
     * @param integer $surveyBegin
     *
     * @return TimeCredit
     */
    public function setSurveyBegin($surveyBegin)
    {
        $this->surveyBegin = $surveyBegin;

        return $this;
    }

    /**
     * Get surveyBegin
     *
     * @return integer
     */
    public function getSurveyBegin()
    {
        return $this->surveyBegin;
    }

    /**
     * Set surveyEndQ01
     *
     * @param integer $surveyEndQ01
     *
     * @return TimeCredit
     */
    public function setSurveyEndQ01($surveyEndQ01)
    {
        $this->surveyEndQ01 = $surveyEndQ01;

        return $this;
    }

    /**
     * Get surveyEndQ01
     *
     * @return integer
     */
    public function getSurveyEndQ01()
    {
        return $this->surveyEndQ01;
    }

    /**
     * Set surveyEndQ02
     *
     * @param integer $surveyEndQ02
     *
     * @return TimeCredit
     */
    public function setSurveyEndQ02($surveyEndQ02)
    {
        $this->surveyEndQ02 = $surveyEndQ02;

        return $this;
    }

    /**
     * Get surveyEndQ02
     *
     * @return integer
     */
    public function getSurveyEndQ02()
    {
        return $this->surveyEndQ02;
    }

    /**
     * Set surveyEndQ03
     *
     * @param integer $surveyEndQ03
     *
     * @return TimeCredit
     */
    public function setSurveyEndQ03($surveyEndQ03)
    {
        $this->surveyEndQ03 = $surveyEndQ03;

        return $this;
    }

    /**
     * Get surveyEndQ03
     *
     * @return integer
     */
    public function getSurveyEndQ03()
    {
        return $this->surveyEndQ03;
    }

    /**
     * Set surveyEndQ04
     *
     * @param integer $surveyEndQ04
     *
     * @return TimeCredit
     */
    public function setSurveyEndQ04($surveyEndQ04)
    {
        $this->surveyEndQ04 = $surveyEndQ04;

        return $this;
    }

    /**
     * Get surveyEndQ04
     *
     * @return integer
     */
    public function getSurveyEndQ04()
    {
        return $this->surveyEndQ04;
    }

    /**
     * Set surveyEndQ05
     *
     * @param integer $surveyEndQ05
     *
     * @return TimeCredit
     */
    public function setSurveyEndQ05($surveyEndQ05)
    {
        $this->surveyEndQ05 = $surveyEndQ05;

        return $this;
    }

    /**
     * Get surveyEndQ05
     *
     * @return integer
     */
    public function getSurveyEndQ05()
    {
        return $this->surveyEndQ05;
    }

    /**
     * Set surveyEndQ06
     *
     * @param integer $surveyEndQ06
     *
     * @return TimeCredit
     */
    public function setSurveyEndQ06($surveyEndQ06)
    {
        $this->surveyEndQ06 = $surveyEndQ06;

        return $this;
    }

    /**
     * Get surveyEndQ06
     *
     * @return integer
     */
    public function getSurveyEndQ06()
    {
        return $this->surveyEndQ06;
    }

    /**
     * Set surveyEndQ07
     *
     * @param integer $surveyEndQ07
     *
     * @return TimeCredit
     */
    public function setSurveyEndQ07($surveyEndQ07)
    {
        $this->surveyEndQ07 = $surveyEndQ07;

        return $this;
    }

    /**
     * Get surveyEndQ07
     *
     * @return integer
     */
    public function getSurveyEndQ07()
    {
        return $this->surveyEndQ07;
    }

    /**
     * Set surveyEndQ08
     *
     * @param integer $surveyEndQ08
     *
     * @return TimeCredit
     */
    public function setSurveyEndQ08($surveyEndQ08)
    {
        $this->surveyEndQ08 = $surveyEndQ08;

        return $this;
    }

    /**
     * Get surveyEndQ08
     *
     * @return integer
     */
    public function getSurveyEndQ08()
    {
        return $this->surveyEndQ08;
    }

    /**
     * Set surveyEndQ09
     *
     * @param integer $surveyEndQ09
     *
     * @return TimeCredit
     */
    public function setSurveyEndQ09($surveyEndQ09)
    {
        $this->surveyEndQ09 = $surveyEndQ09;

        return $this;
    }

    /**
     * Get surveyEndQ09
     *
     * @return integer
     */
    public function getSurveyEndQ09()
    {
        return $this->surveyEndQ09;
    }

    /**
     * Set surveyEndQ10
     *
     * @param string $surveyEndQ10
     *
     * @return TimeCredit
     */
    public function setSurveyEndQ10($surveyEndQ10)
    {
        $this->surveyEndQ10 = $surveyEndQ10;

        return $this;
    }

    /**
     * Get surveyEndQ10
     *
     * @return string
     */
    public function getSurveyEndQ10()
    {
        return $this->surveyEndQ10;
    }

    /**
     * Set surveyEnd
     *
     * @param integer $surveyEnd
     *
     * @return TimeCredit
     */
    public function setSurveyEnd($surveyEnd)
    {
        $this->surveyEnd = $surveyEnd;

        return $this;
    }

    /**
     * Get surveyEnd
     *
     * @return integer
     */
    public function getSurveyEnd()
    {
        return $this->surveyEnd;
    }

    /**
     * Set buggy
     *
     * @param integer $buggy
     *
     * @return TimeCredit
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
     * Add cours
     *
     * @param Cours $cours
     *
     * @return TimeCredit
     */
    public function addCours(Cours $cours)
    {
        $this->courses[] = $cours;

        return $this;
    }

    /**
     * Remove cours
     *
     * @param Cours $cours
     *
     * @return TimeCredit
     */
    public function removeCours(Cours $cours)
    {
        $this->courses->removeElement($cours);

        return $this;
    }

    /**
     * Set cours
     *
     * @param Collection $courses
     *
     * @return TimeCredit
     */
    public function setCourses(Collection $courses)
    {
        $this->courses = $courses;

        return $this;
    }

    /**
     * Get cours ArrayCollection
     *
     * @return ArrayCollection
     */
    public function getCourses()
    {
        return $this->courses;
    }

    /**
     * Add document
     *
     * @param TimeCreditDocument $document
     *
     * @return TimeCredit
     */
    public function addDocument(TimeCreditDocument $document)
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * Remove document
     *
     * @param TimeCreditDocument $document
     *
     * @return TimeCredit
     */
    public function removeDocument(TimeCreditDocument $document)
    {
        $this->documents->removeElement($document);

        return $this;
    }

    /**
     * Set document Collection
     *
     * @param Collection $documents
     *
     * @return TimeCredit
     */
    public function setDocuments(Collection $documents)
    {
        $this->documents = $documents;

        return $this;
    }

    /**
     * Get document ArrayCollection
     *
     * @return ArrayCollection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Add adminNotif
     *
     * @param AdminNotif $adminNotif
     *
     * @return TimeCredit
     */
    public function addAdminNotif(AdminNotif $adminNotif)
    {
        $this->adminNotifs[] = $adminNotif;

        return $this;
    }

    /**
     * Remove adminNotif
     *
     * @param AdminNotif $adminNotif
     *
     * @return TimeCredit
     */
    public function removeAdminNotif(AdminNotif $adminNotif)
    {
        $this->adminNotifs->removeElement($adminNotif);

        return $this;
    }

    /**
     * Set adminNotif Collection
     *
     * @param Collection $adminNotifs
     *
     * @return TimeCredit
     */
    public function setAdminNotifs(Collection $adminNotifs)
    {
        $this->adminNotifs = $adminNotifs;

        return $this;
    }

    /**
     * Get adminNotif ArrayCollection
     *
     * @return ArrayCollection
     */
    public function getAdminNotifs()
    {
        return $this->adminNotifs;
    }

    /**
     * Add teacherNotif
     *
     * @param TeacherNotif $teacherNotif
     *
     * @return TimeCredit
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
     * @return TimeCredit
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
     * @return TimeCredit
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
     * @return TimeCredit
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
     * @return TimeCredit
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
     * @return TimeCredit
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
     * Get unplanifiedHours
     *
     * @return float
     */
    public function getNotPlanifiedHours()
    {
        return floatval($this->totalHours - ($this->doneHours + $this->lostHours + $this->reservedHours));
    }

    /**
     * Get FullPlanified
     *
     * @return boolean
     */
    public function isFullPlanified()
    {
        if ($this->totalHours == ($this->doneHours + $this->lostHours + $this->reservedHours)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get Finished
     *
     * @return boolean
     */
    public function isFinished()
    {
        if (!$this->isFullPlanified()) {
            return false;
        } else {
            $now = new DateTime('now');
            $courses = $this->getCourses();
            if (count($courses) != 0) {
                $lastCoursDtEnd = $courses[0]->getDtEnd();
                if ($now > $lastCoursDtEnd) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Get Finished
     *
     * @return boolean
     */
    public function isFullFinished()
    {
        if (!$this->isFinished()) {
            return false;
        } else {
            $now = new DateTime('now');

            $allCoursesFinishedAndCommented = true;

            foreach ($this->getCourses() as $cours) {
                if ($cours->getDtEnd() > $now) {
                    $allCoursesFinishedAndCommented = false;
                } elseif ($cours->getStatus() == Cours::STATUS_PLANNED) {
                    $allCoursesFinishedAndCommented = false;
                }
            }

            if ($allCoursesFinishedAndCommented) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get DeadLineExceeded
     *
     * @return boolean
     */
    public function isDeadLineExceeded()
    {
        $now = new DateTime('now');

        if (null != $this->getDeadLine() && $now > $this->getDeadLine()) {
            return true;
        }

        return false;
    }

    /**
     * Get Started
     *
     * @return boolean
     */
    public function isStarted()
    {
        if (count($this->getCourses()) != 0) {
            return true;
        }

        return false;
    }

    public function getStatus()
    {
        if ($this->isFullFinished()) {
            return self::STATUS_FULL_FINISHED;
        } elseif ($this->isFinished()) {
            return self::STATUS_FINISHED;
        } elseif ($this->isDeadLineExceeded()) {
            return self::STATUS_DEADLINE_EXCEEDED;
        } elseif ($this->isFullPlanified()) {
            return self::STATUS_FULL_PLANIFIED;
        } elseif ($this->isStarted()) {
            return self::STATUS_STARTED;
        } else {
            return self::STATUS_NEW;
        }
    }

    /**
     * Get globalKpiHomeworkPerformed
     *
     * @return NULL | integer | number
     */
    public function getGlobalKpiHomeworkPerformed()
    {
        $forcedKpiHomeworkPerformed = $this->getForcedKpiHomeworkPerformed();
        if (null != $forcedKpiHomeworkPerformed) {
            return $forcedKpiHomeworkPerformed;
        }

        return $this->getCalculatedKpiHomeworkPerformed();
    }

    /**
     * Get globalKpiParticipation
     *
     * @return NULL | integer | number
     */
    public function getGlobalKpiParticipation()
    {
        $forcedKpiParticipation = $this->getForcedKpiParticipation();
        if (null != $forcedKpiParticipation) {
            return $forcedKpiParticipation;
        }

        return $this->getCalculatedKpiParticipation();
    }

    /**
     * Get globalKpiVocabularyRetention
     *
     * @return NULL | integer | number
     */
    public function getGlobalKpiVocabularyRetention()
    {
        $forcedKpiVocabularyRetention = $this->getForcedKpiVocabularyRetention();
        if (null != $forcedKpiVocabularyRetention) {
            return $forcedKpiVocabularyRetention;
        }

        return $this->getCalculatedKpiVocabularyRetention();
    }

    /**
     * Get globalKpiCorrectionConsideration
     *
     * @return NULL | integer | number
     */
    public function getGlobalKpiCorrectionConsideration()
    {
        $forcedKpiCorrectionConsideration = $this->getForcedKpiCorrectionConsideration();
        if (null != $forcedKpiCorrectionConsideration) {
            return $forcedKpiCorrectionConsideration;
        }

        return $this->getCalculatedKpiCorrectionConsideration();
    }

    /**
     * Get calculatedKpiHomeworkPerformed
     *
     * @return NULL | number
     */
    public function getCalculatedKpiHomeworkPerformed()
    {
        $kpi1 = 0;
        $kpi3 = 0;
        $kpi4 = 0;
        foreach ($this->getCourses() as $cours) {
            if ($cours->getKpiHomeworkPerformed() == 1) {
                $kpi1++;
            }
            if ($cours->getKpiHomeworkPerformed() == 3) {
                $kpi3++;
            }
            if ($cours->getKpiHomeworkPerformed() == 4) {
                $kpi4++;
            }
        }
        if ($kpi1 == 0 && $kpi3 == 0 && $kpi4 == 0) {
            return null;
        } else {
            $kpi = 0;
            if ($kpi1 > $kpi3) {
                if ($kpi1 > $kpi4) {
                    $kpi = 1;
                } else {
                    $kpi = 4;
                }
            } else {
                if ($kpi3 > $kpi4) {
                    $kpi = 3;
                } else {
                    $kpi = 4;
                }
            }

            return $kpi;
        }
    }

    /**
     * Get calculatedKpiParticipation
     *
     * @return NULL | number
     */
    public function getCalculatedKpiParticipation()
    {
        $kpi1 = 0;
        $kpi3 = 0;
        $kpi4 = 0;
        foreach ($this->getCourses() as $cours) {
            if ($cours->getKpiParticipation() == 1) {
                $kpi1++;
            }
            if ($cours->getKpiParticipation() == 3) {
                $kpi3++;
            }
            if ($cours->getKpiParticipation() == 4) {
                $kpi4++;
            }
        }
        if ($kpi1 == 0 && $kpi3 == 0 && $kpi4 == 0) {
            return null;
        } else {
            $kpi = 0;
            if ($kpi1 > $kpi3) {
                if ($kpi1 > $kpi4) {
                    $kpi = 1;
                } else {
                    $kpi = 4;
                }
            } else {
                if ($kpi3 > $kpi4) {
                    $kpi = 3;
                } else {
                    $kpi = 4;
                }
            }

            return $kpi;
        }
    }

    /**
     * Get calculatedKpiVocabularyRetention
     *
     * @return NULL | number
     */
    public function getCalculatedKpiVocabularyRetention()
    {
        $kpi1 = 0;
        $kpi3 = 0;
        $kpi4 = 0;
        foreach ($this->getCourses() as $cours) {
            if ($cours->getKpiVocabularyRetention() == 1) {
                $kpi1++;
            }
            if ($cours->getKpiVocabularyRetention() == 3) {
                $kpi3++;
            }
            if ($cours->getKpiVocabularyRetention() == 4) {
                $kpi4++;
            }
        }
        if ($kpi1 == 0 && $kpi3 == 0 && $kpi4 == 0) {
            return null;
        } else {
            $kpi = 0;
            if ($kpi1 > $kpi3) {
                if ($kpi1 > $kpi4) {
                    $kpi = 1;
                } else {
                    $kpi = 4;
                }
            } else {
                if ($kpi3 > $kpi4) {
                    $kpi = 3;
                } else {
                    $kpi = 4;
                }
            }

            return $kpi;
        }
    }

    /**
     * Get calculatedKpiCorrectionConsideration
     *
     * @return NULL | number
     */
    public function getCalculatedKpiCorrectionConsideration()
    {
        $kpi1 = 0;
        $kpi3 = 0;
        $kpi4 = 0;
        foreach ($this->getCourses() as $cours) {
            if ($cours->getKpiCorrectionConsideration() == 1) {
                $kpi1++;
            }
            if ($cours->getKpiCorrectionConsideration() == 3) {
                $kpi3++;
            }
            if ($cours->getKpiCorrectionConsideration() == 4) {
                $kpi4++;
            }
        }
        if ($kpi1 == 0 && $kpi3 == 0 && $kpi4 == 0) {
            return null;
        } else {
            $kpi = 0;
            if ($kpi1 > $kpi3) {
                if ($kpi1 > $kpi4) {
                    $kpi = 1;
                } else {
                    $kpi = 4;
                }
            } else {
                if ($kpi3 > $kpi4) {
                    $kpi = 3;
                } else {
                    $kpi = 4;
                }
            }

            return $kpi;
        }
    }

    /**
     * Choice Form ftype
     *
     * @return array
     */
    public static function choiceFtype()
    {
        return array(
            'TimeCredit.ftype.choice.' . self::FTYPE_EN => self::FTYPE_EN
        );
    }

    /**
     * Choice Validator ftype
     *
     * @return array
     */
    public static function choiceFtypeCallback()
    {
        return array(
            self::FTYPE_EN
        );
    }

    /**
     * Choice Form level
     *
     * @return array
     */
    public static function choiceLevel()
    {
        return array(
            'TimeCredit.level.choice.' . self::LEVEL_EN_LOW => self::LEVEL_EN_LOW,
            'TimeCredit.level.choice.' . self::LEVEL_EN_MEDIUM => self::LEVEL_EN_MEDIUM,
            'TimeCredit.level.choice.' . self::LEVEL_EN_HIGH => self::LEVEL_EN_HIGH
        );
    }

    /**
     * Choice Validator level
     *
     * @return array
     */
    public static function choiceLevelCallback()
    {
        return array(
            self::LEVEL_EN_LOW,
            self::LEVEL_EN_MEDIUM,
            self::LEVEL_EN_HIGH
        );
    }

    /**
     * Choice Form cef
     *
     * @return array
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
     * @return array
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
     * Choice Form notifyByMail
     *
     * @return array
     */
    public static function choiceNotifyByMail()
    {
        return array(
            'TimeCredit.notifyByMail.choice.' . self::NOTIFYBYMAIL_NOTSENT => self::NOTIFYBYMAIL_NOTSENT,
            'TimeCredit.notifyByMail.choice.' . self::NOTIFYBYMAIL_SENT => self::NOTIFYBYMAIL_SENT,
            'TimeCredit.notifyByMail.choice.' . self::NOTIFYBYMAIL_DISABLED => self::NOTIFYBYMAIL_DISABLED
        );
    }

    /**
     * Choice Validator notifyByMail
     *
     * @return array
     */
    public static function choiceNotifyByMailCallback()
    {
        return array(
            self::NOTIFYBYMAIL_NOTSENT,
            self::NOTIFYBYMAIL_SENT,
            self::NOTIFYBYMAIL_DISABLED
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
            'TimeCredit.kpi.choice.' . self::KPI_LOW => self::KPI_LOW,
            'TimeCredit.kpi.choice.' . self::KPI_MEDIUM => self::KPI_MEDIUM,
            'TimeCredit.kpi.choice.' . self::KPI_HIGH => self::KPI_HIGH
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
     * Choice Form endReport
     *
     * @return array
     */
    public static function choiceEndReport()
    {
        return array(
            'TimeCredit.endReport.choice.' . self::ENDREPORT_LOW => self::ENDREPORT_LOW,
            'TimeCredit.endReport.choice.' . self::ENDREPORT_MEDIUM => self::ENDREPORT_MEDIUM,
            'TimeCredit.endReport.choice.' . self::ENDREPORT_HIGH => self::ENDREPORT_HIGH
        );
    }

    /**
     * Choice Validator endReport
     *
     * @return array
     */
    public static function choiceEndReportCallback()
    {
        return array(
            self::ENDREPORT_LOW,
            self::ENDREPORT_MEDIUM,
            self::ENDREPORT_HIGH
        );
    }

    /**
     * Choice Form improvement
     *
     * @return array
     */
    public static function choiceImprovement()
    {
        return array(
            'TimeCredit.improvement.choice.' . self::IMPROVEMENT_NOTREQUIRED => self::IMPROVEMENT_NOTREQUIRED,
            'TimeCredit.improvement.choice.' . self::IMPROVEMENT_REQUIRED => self::IMPROVEMENT_REQUIRED
        );
    }

    /**
     * Choice Validator improvement
     *
     * @return array
     */
    public static function choiceImprovementCallback()
    {
        return array(
            self::IMPROVEMENT_NOTREQUIRED,
            self::IMPROVEMENT_REQUIRED
        );
    }

    /**
     * Choice Form lockout
     *
     * @return array
     */
    public static function choiceLockout()
    {
        return array(
            'TimeCredit.lockout.choice.' . self::LOCKOUT_UNLOCKED => self::LOCKOUT_UNLOCKED,
            'TimeCredit.lockout.choice.' . self::LOCKOUT_LOCKED => self::LOCKOUT_LOCKED
        );
    }

    /**
     * Choice Validator lockout
     *
     * @return array
     */
    public static function choiceLockoutCallback()
    {
        return array(
            self::LOCKOUT_UNLOCKED,
            self::LOCKOUT_LOCKED
        );
    }

    /**
     * Choice Form showReport
     *
     * @return array
     */
    public static function choiceShowReport()
    {
        return array(
            'TimeCredit.showReport.choice.' . self::SHOWREPORT_DONTSHOW => self::SHOWREPORT_DONTSHOW,
            'TimeCredit.showReport.choice.' . self::SHOWREPORT_SHOW => self::SHOWREPORT_SHOW
        );
    }

    /**
     * Choice Validator showReport
     *
     * @return array
     */
    public static function choiceShowReportCallback()
    {
        return array(
            self::SHOWREPORT_DONTSHOW,
            self::SHOWREPORT_SHOW
        );
    }

    /**
     * Choice Form survey
     *
     * @return array
     */
    public static function choiceSurvey()
    {
        return array(
            'TimeCredit.survey.choice.' . self::SURVEY_1 => self::SURVEY_1,
            'TimeCredit.survey.choice.' . self::SURVEY_2 => self::SURVEY_2,
            'TimeCredit.survey.choice.' . self::SURVEY_3 => self::SURVEY_3,
            'TimeCredit.survey.choice.' . self::SURVEY_4 => self::SURVEY_4,
            'TimeCredit.survey.choice.' . self::SURVEY_5 => self::SURVEY_5
        );
    }

    /**
     * Choice Validator survey
     *
     * @return array
     */
    public static function choiceSurveyCallback()
    {
        return array(
            self::SURVEY_1,
            self::SURVEY_2,
            self::SURVEY_3,
            self::SURVEY_4,
            self::SURVEY_5
        );
    }

    /**
     * Choice Form surveyFill
     *
     * @return array
     */
    public static function choiceSurveyFill()
    {
        return array(
            'TimeCredit.surveyFill.choice.' . self::SURVEY_NOTFILLED => self::SURVEY_NOTFILLED,
            'TimeCredit.surveyFill.choice.' . self::SURVEY_FILLED => self::SURVEY_FILLED
        );
    }

    /**
     * Choice Validator surveyFill
     *
     * @return array
     */
    public static function choiceSurveyFillCallback()
    {
        return array(
            self::SURVEY_NOTFILLED,
            self::SURVEY_FILLED
        );
    }

    /**
     * Validator totalHours
     *
     * @param ExecutionContextInterface $context
     */
    public function checkValidTotalHours($context)
    {
        $countOtherHours = $this->getDoneHours() + $this->getLostHours() + $this->getReservedHours();

        if ($this->getTotalHours() < $countOtherHours) {
            $context->addViolationAt('totalHours', 'TimeCredit.totalHours.invalid', array(), null);
        }
    }

    /**
     * Update the buggy status and notifs
     * @ORM\PreFlush()
     */
    public function updateBuggy()
    {
        $this->setBuggy(self::HEALTH_OK);

        if (null == $this->getCefBegin() || trim($this->getCefBegin()) == '') {
            $this->setBuggy(self::HEALTH_BUGGY);
        }

        if ($this->isDeadLineExceeded()) {
            $this->setBuggy(self::HEALTH_BUGGY);
        }

        if ($this->isFullFinished()) {
            if (null == $this->getCefEnd() || trim($this->getCefEnd()) == '') {
                $this->setBuggy(self::HEALTH_BUGGY);
            }

            if (null == $this->getGlobalKpiCorrectionConsideration()) {
                $this->setBuggy(self::HEALTH_BUGGY);
            }

            if (null == $this->getGlobalKpiHomeworkPerformed()) {
                $this->setBuggy(self::HEALTH_BUGGY);
            }

            if (null == $this->getGlobalKpiParticipation()) {
                $this->setBuggy(self::HEALTH_BUGGY);
            }

            if (null == $this->getGlobalKpiVocabularyRetention()) {
                $this->setBuggy(self::HEALTH_BUGGY);
            }
        }

        $courses = $this->getCourses();
        if (null == $courses) {
            $this->courses = new ArrayCollection();
            $courses = new ArrayCollection();
        }

        $adminNotifs = $this->getAdminNotifs();
        if (null == $adminNotifs) {
            $adminNotifs = new ArrayCollection();
            $this->adminNotifs = new ArrayCollection();
        }

        // admin notif txt 40j
        if (count($courses) < $this->getTotalHours() && count($courses) > 0) {

            $adminNotif40d = null;
            foreach ($adminNotifs as $notif) {
                if ($notif->getType() == AdminNotif::TYPE_TXT_40D_AFTER_COURS) {
                    $adminNotif40d = $notif;
                }
            }
            $after40d = new DateTime('now');
            $after40d->modify('+1 hour');

            $i = 0;
            $lastCoursFound = false;
            do {
                $lastCours = $courses[$i];
                if ($lastCours instanceof Cours) {
                    $lastCoursFound = true;
                    $after40d->setTimestamp($lastCours->getDtEnd()
                        ->getTimestamp());
                }
                $i++;
            } while ($i < count($courses) && $lastCoursFound == true);
            $after40d->modify('+40 days');

            if (null == $adminNotif40d) {
                $adminNotif40d = new AdminNotif();
                $adminNotif40d->setType(AdminNotif::TYPE_TXT_40D_AFTER_COURS);
            }
            $adminNotif40d->setStatus(AdminNotif::PENDING);
            $adminNotif40d->setDtStart($after40d);
            $adminNotif40d->setTimeCredit($this);
            $this->addAdminNotif($adminNotif40d);
        } else {
            foreach ($adminNotifs as $notif) {
                if ($notif->getType() == AdminNotif::TYPE_TXT_40D_AFTER_COURS) {
                    if ($notif->getStatus() == AdminNotif::PENDING) {
                        $this->removeAdminNotif($notif);
                    }
                }
            }
        }

        $adminNotifs = $this->getAdminNotifs();
        if (null == $adminNotifs) {
            $adminNotifs = new ArrayCollection();
        }
        // admin notif txt tc edit
        if (count($courses) == $this->getTotalHours()) {
            if (null == $this->getImprovement1() || null == $this->getImprovement2() || null == $this->getImprovement3() || null == $this->getImprovement4() || null == $this->getImprovement5() || null == $this->getLevelDesc1() || null == $this->getLevelDesc2() || null == $this->getLevelDesc3() || null == $this->getLevelDesc4() || null == $this->getLevelDesc5() || null == $this->getLevelDesc6() || null == $this->getLevelDesc7() || null == $this->getProgress1() || null == $this->getProgress2() || null == $this->getProgress3() || null == $this->getProgress4() || null == $this->getProgress5() || null == $this->getProgress6() || null == $this->getProgress7()) {
                $after1h = new DateTime('now');
                $after1h->modify('+1 hour');
                $after1h->setTimestamp($courses[0]->getDtEnd()
                    ->getTimestamp());

                $adminNotiftxtTcEdit = null;
                foreach ($adminNotifs as $notif) {
                    if ($notif->getType() == AdminNotif::TYPE_TXT_TIMECREDIT_EDIT) {
                        $adminNotiftxtTcEdit = $notif;
                    }
                }

                if (null == $adminNotiftxtTcEdit) {
                    $adminNotiftxtTcEdit = new AdminNotif();
                    $adminNotiftxtTcEdit->setType(AdminNotif::TYPE_TXT_TIMECREDIT_EDIT);
                }
                $adminNotiftxtTcEdit->setStatus(AdminNotif::PENDING);
                $adminNotiftxtTcEdit->setDtStart($after1h);
                $adminNotiftxtTcEdit->setTimeCredit($this);
                $this->addAdminNotif($adminNotiftxtTcEdit);
            } else {
                foreach ($adminNotifs as $notif) {
                    if ($notif->getType() == AdminNotif::TYPE_TXT_TIMECREDIT_EDIT) {
                        $this->removeAdminNotif($notif);
                    }
                }
            }
        } else {
            foreach ($adminNotifs as $notif) {
                if ($notif->getType() == AdminNotif::TYPE_TXT_TIMECREDIT_EDIT) {
                    $this->removeAdminNotif($notif);
                }
            }
        }

        $teacherNotifs = $this->getTeacherNotifs();
        if (null == $teacherNotifs) {
            $teacherNotifs = new ArrayCollection();
            $this->teacherNotifs = new ArrayCollection();
        }
        // teacher notif txt tc
        if (count($courses) == $this->getTotalHours()) {
            if (null == $this->getImprovement1() || null == $this->getImprovement2() || null == $this->getImprovement3() || null == $this->getImprovement4() || null == $this->getImprovement5() || null == $this->getLevelDesc1() || null == $this->getLevelDesc2() || null == $this->getLevelDesc3() || null == $this->getLevelDesc4() || null == $this->getLevelDesc5() || null == $this->getLevelDesc6() || null == $this->getLevelDesc7() || null == $this->getProgress1() || null == $this->getProgress2() || null == $this->getProgress3() || null == $this->getProgress4() || null == $this->getProgress5() || null == $this->getProgress6() || null == $this->getProgress7()) {
                $after1h = new DateTime('now');
                $after1h->modify('+1 hour');
                $after1h->setTimestamp($courses[0]->getDtEnd()
                    ->getTimestamp());
                $teacherNotifTxtTcEdit = null;
                $lastTeacher = $courses[0]->getTeacher();
                if (null != $lastTeacher) {
                    foreach ($teacherNotifs as $notif) {
                        if ($notif->getType() == TeacherNotif::TYPE_TXT_TIMECREDIT_EDIT) {
                            $teacherNotifTxtTcEdit = $notif;
                        }
                    }
                    if (null == $teacherNotifTxtTcEdit) {
                        $teacherNotifTxtTcEdit = new TeacherNotif();
                        $teacherNotifTxtTcEdit->setType(TeacherNotif::TYPE_TXT_TIMECREDIT_EDIT);
                    }
                    $teacherNotifTxtTcEdit->setTeacher($lastTeacher);
                    $teacherNotifTxtTcEdit->setStatus(TeacherNotif::PENDING);
                    $teacherNotifTxtTcEdit->setDtStart($after1h);
                    $teacherNotifTxtTcEdit->setTimeCredit($this);
                    $this->addTeacherNotif($teacherNotifTxtTcEdit);
                } else {
                    foreach ($teacherNotifs as $notif) {
                        if ($notif->getType() == TeacherNotif::TYPE_TXT_TIMECREDIT_EDIT) {
                            $this->removeTeacherNotif($notif);
                        }
                    }
                }
            } else {
                foreach ($teacherNotifs as $notif) {
                    if ($notif->getType() == TeacherNotif::TYPE_TXT_TIMECREDIT_EDIT) {
                        $this->removeTeacherNotif($notif);
                    }
                }
            }
        } else {
            foreach ($teacherNotifs as $notif) {
                if ($notif->getType() == TeacherNotif::TYPE_TXT_TIMECREDIT_EDIT) {
                    $this->removeTeacherNotif($notif);
                }
            }
        }

        $teacherNotifs = $this->getTeacherNotifs();
        if (null == $teacherNotifs) {
            $teacherNotifs = new ArrayCollection();
        }
        // teacher notif email tc
        if (count($courses) == $this->getTotalHours()) {
            if (null == $this->getImprovement1() || null == $this->getImprovement2() || null == $this->getImprovement3() || null == $this->getImprovement4() || null == $this->getImprovement5() || null == $this->getLevelDesc1() || null == $this->getLevelDesc2() || null == $this->getLevelDesc3() || null == $this->getLevelDesc4() || null == $this->getLevelDesc5() || null == $this->getLevelDesc6() || null == $this->getLevelDesc7() || null == $this->getProgress1() || null == $this->getProgress2() || null == $this->getProgress3() || null == $this->getProgress4() || null == $this->getProgress5() || null == $this->getProgress6() || null == $this->getProgress7()) {
                $after1h = new DateTime('now');
                $after1h->modify('+1 hour');
                $after1h->setTimestamp($courses[0]->getDtEnd()
                    ->getTimestamp());
                $teacherNotifEmailTcEdit = null;
                $lastTeacher = $courses[0]->getTeacher();
                if (null != $lastTeacher) {
                    foreach ($teacherNotifs as $notif) {
                        if ($notif->getType() == TeacherNotif::TYPE_EMAIL_TIMECREDIT_EDIT) {
                            $teacherNotifEmailTcEdit = $notif;
                        }
                    }
                    if (null == $teacherNotifEmailTcEdit) {
                        $teacherNotifEmailTcEdit = new TeacherNotif();
                        $teacherNotifEmailTcEdit->setType(TeacherNotif::TYPE_EMAIL_TIMECREDIT_EDIT);
                        $teacherNotifEmailTcEdit->setTeacher($lastTeacher);
                        $teacherNotifEmailTcEdit->setStatus(TeacherNotif::PENDING);
                        $teacherNotifEmailTcEdit->setDtStart($after1h);
                        $teacherNotifEmailTcEdit->setTimeCredit($this);
                        $this->addTeacherNotif($teacherNotifEmailTcEdit);
                    } elseif ($teacherNotifEmailTcEdit->getStatus() == TeacherNotif::ERROR) {
                        $teacherNotifEmailTcEdit = new TeacherNotif();
                        $teacherNotifEmailTcEdit->setType(TeacherNotif::TYPE_EMAIL_TIMECREDIT_EDIT);
                        $teacherNotifEmailTcEdit->setTeacher($lastTeacher);
                        $teacherNotifEmailTcEdit->setStatus(TeacherNotif::PENDING);
                        $teacherNotifEmailTcEdit->setDtStart($after1h);
                        $teacherNotifEmailTcEdit->setTimeCredit($this);
                        $this->addTeacherNotif($teacherNotifEmailTcEdit);
                    } elseif ($teacherNotifEmailTcEdit->getStatus() == TeacherNotif::PENDING) {
                        $teacherNotifEmailTcEdit->setTeacher($lastTeacher);
                        $teacherNotifEmailTcEdit->setStatus(TeacherNotif::PENDING);
                        $teacherNotifEmailTcEdit->setDtStart($after1h);
                        $teacherNotifEmailTcEdit->setTimeCredit($this);
                        $this->addTeacherNotif($teacherNotifEmailTcEdit);
                    }
                } else {
                    foreach ($teacherNotifs as $notif) {
                        if ($notif->getType() == TeacherNotif::TYPE_EMAIL_TIMECREDIT_EDIT && $notif->getStatus() == TeacherNotif::PENDING) {
                            $this->removeTeacherNotif($notif);
                        }
                    }
                }
            } else {
                foreach ($teacherNotifs as $notif) {
                    if ($notif->getType() == TeacherNotif::TYPE_EMAIL_TIMECREDIT_EDIT && $notif->getStatus() == TeacherNotif::PENDING) {
                        $this->removeTeacherNotif($notif);
                    }
                }
            }
        } else {
            foreach ($teacherNotifs as $notif) {
                if ($notif->getType() == TeacherNotif::TYPE_EMAIL_TIMECREDIT_EDIT && $notif->getStatus() == TeacherNotif::PENDING) {
                    $this->removeTeacherNotif($notif);
                }
            }
        }

        $traineeNotifs = $this->getTraineeNotifs();
        if (null == $traineeNotifs) {
            $traineeNotifs = new ArrayCollection();
            $this->traineeNotifs = new ArrayCollection();
        }
        // trainee notif txt survey begin
        if (count($courses) >= 2) {
            if ($this->getSurveyBegin() == self::SURVEY_NOTFILLED) {
                $after1hAfter2c = new DateTime('now');
                $after1hAfter2c->modify('+1 hour');
                $i = 2;
                $secondCoursFound = false;
                do {
                    $secondCours = $courses[count($courses) - $i];
                    if ($secondCours instanceof Cours) {
                        $secondCoursFound = true;
                        $after1hAfter2c->setTimestamp($secondCours->getDtEnd()
                            ->getTimestamp());
                    }
                    $i++;
                } while ($i <= count($courses) && $secondCoursFound != true);

                $traineeNotifTxtSB = null;
                foreach ($traineeNotifs as $notif) {
                    if ($notif->getType() == TraineeNotif::TYPE_TXT_SURVEYBEGIN) {
                        $traineeNotifTxtSB = $notif;
                    }
                }

                if (null == $traineeNotifTxtSB) {
                    $traineeNotifTxtSB = new TraineeNotif();
                    $traineeNotifTxtSB->setType(TraineeNotif::TYPE_TXT_SURVEYBEGIN);
                }
                $traineeNotifTxtSB->setTrainee($this->getTrainee());
                $traineeNotifTxtSB->setStatus(TraineeNotif::PENDING);
                $traineeNotifTxtSB->setDtStart($after1hAfter2c);
                $traineeNotifTxtSB->setTimeCredit($this);
                $this->addTraineeNotif($traineeNotifTxtSB);
            } else {
                foreach ($traineeNotifs as $notif) {
                    if ($notif->getType() == TraineeNotif::TYPE_TXT_SURVEYBEGIN) {
                        if ($notif->getStatus() == TraineeNotif::PENDING) {
                            $this->removeTraineeNotif($notif);
                        }
                    }
                }
            }
        } else {
            foreach ($traineeNotifs as $notif) {
                if ($notif->getType() == TraineeNotif::TYPE_TXT_SURVEYBEGIN) {
                    if ($notif->getStatus() == TraineeNotif::PENDING) {
                        $this->removeTraineeNotif($notif);
                    }
                }
            }
        }

        $traineeNotifs = $this->getTraineeNotifs();
        if (null == $traineeNotifs) {
            $traineeNotifs = new ArrayCollection();
        }
        // trainee notif email survey begin
        if (count($courses) > 2) {
            if ($this->getSurveyBegin() == self::SURVEY_NOTFILLED) {
                $after1hAfter2c = new DateTime('now');
                $after1hAfter2c->modify('+1 hour');
                $i = 2;
                $secondCoursFound = false;
                do {
                    $secondCours = $courses[count($courses) - $i];
                    if ($secondCours instanceof Cours) {
                        $secondCoursFound = true;
                        $after1hAfter2c->setTimestamp($secondCours->getDtEnd()
                            ->getTimestamp());
                    }
                    $i++;
                } while ($i <= count($courses) && $secondCoursFound != true);
                $traineeNotifEmailSB = null;
                foreach ($traineeNotifs as $notif) {
                    if ($notif->getType() == TraineeNotif::TYPE_EMAIL_SURVEYBEGIN) {
                        $traineeNotifEmailSB = $notif;
                    }
                }

                if (null == $traineeNotifEmailSB) {
                    $traineeNotifEmailSB = new TraineeNotif();
                    $traineeNotifEmailSB->setType(TraineeNotif::TYPE_EMAIL_SURVEYBEGIN);
                    $traineeNotifEmailSB->setTrainee($this->getTrainee());
                    $traineeNotifEmailSB->setStatus(TraineeNotif::PENDING);
                    $traineeNotifEmailSB->setDtStart($after1hAfter2c);
                    $traineeNotifEmailSB->setTimeCredit($this);
                    $this->addTraineeNotif($traineeNotifEmailSB);
                } elseif ($traineeNotifEmailSB->getStatus() == TraineeNotif::ERROR) {
                    $traineeNotifEmailSB = new TraineeNotif();
                    $traineeNotifEmailSB->setType(TraineeNotif::TYPE_EMAIL_SURVEYBEGIN);
                    $traineeNotifEmailSB->setTrainee($this->getTrainee());
                    $traineeNotifEmailSB->setStatus(TraineeNotif::PENDING);
                    $traineeNotifEmailSB->setDtStart($after1hAfter2c);
                    $traineeNotifEmailSB->setTimeCredit($this);
                    $this->addTraineeNotif($traineeNotifEmailSB);
                } elseif ($traineeNotifEmailSB->getStatus() == TraineeNotif::PENDING) {
                    $traineeNotifEmailSB->setTrainee($this->getTrainee());
                    $traineeNotifEmailSB->setStatus(TraineeNotif::PENDING);
                    $traineeNotifEmailSB->setDtStart($after1hAfter2c);
                    $traineeNotifEmailSB->setTimeCredit($this);
                    $this->addTraineeNotif($traineeNotifEmailSB);
                }
            } else {
                foreach ($traineeNotifs as $notif) {
                    if ($notif->getType() == TraineeNotif::TYPE_EMAIL_SURVEYBEGIN) {
                        if ($notif->getStatus() == TraineeNotif::PENDING) {
                            $this->removeTraineeNotif($notif);
                        }
                    }
                }
            }
        } else {
            foreach ($traineeNotifs as $notif) {
                if ($notif->getType() == TraineeNotif::TYPE_EMAIL_SURVEYBEGIN) {
                    if ($notif->getStatus() == TraineeNotif::PENDING) {
                        $this->removeTraineeNotif($notif);
                    }
                }
            }
        }

        $traineeNotifs = $this->getTraineeNotifs();
        if (null == $traineeNotifs) {
            $traineeNotifs = new ArrayCollection();
        }
        // trainee notif txt survey end
        if (count($courses) == $this->getTotalHours()) {
            if ($this->getSurveyEnd() == self::SURVEY_NOTFILLED) {
                $after1h = new DateTime('now');
                $after1h->setTimestamp($courses[0]->getDtEnd()
                    ->getTimestamp());
                $traineeNotifTxtSE = null;
                foreach ($traineeNotifs as $notif) {
                    if ($notif->getType() == TraineeNotif::TYPE_TXT_SURVEYEND) {
                        $traineeNotifTxtSE = $notif;
                    }
                }

                if (null == $traineeNotifTxtSE) {
                    $traineeNotifTxtSE = new TraineeNotif();
                    $traineeNotifTxtSE->setType(TraineeNotif::TYPE_TXT_SURVEYEND);
                }
                $traineeNotifTxtSE->setTrainee($this->getTrainee());
                $traineeNotifTxtSE->setStatus(TraineeNotif::PENDING);
                $traineeNotifTxtSE->setDtStart($after1h);
                $traineeNotifTxtSE->setTimeCredit($this);
                $this->addTraineeNotif($traineeNotifTxtSE);
            } else {
                foreach ($traineeNotifs as $notif) {
                    if ($notif->getType() == TraineeNotif::TYPE_TXT_SURVEYEND) {
                        if ($notif->getStatus() == TraineeNotif::PENDING) {
                            $this->removeTraineeNotif($notif);
                        }
                    }
                }
            }
        } else {
            foreach ($traineeNotifs as $notif) {
                if ($notif->getType() == TraineeNotif::TYPE_TXT_SURVEYEND) {
                    if ($notif->getStatus() == TraineeNotif::PENDING) {
                        $this->removeTraineeNotif($notif);
                    }
                }
            }
        }

        $traineeNotifs = $this->getTraineeNotifs();
        if (null == $traineeNotifs) {
            $traineeNotifs = new ArrayCollection();
        }
        // trainee notif email survey end
        if (count($courses) == $this->getTotalHours()) {
            if ($this->getSurveyEnd() == self::SURVEY_NOTFILLED) {
                $after1h = new DateTime('now');
                $after1h->modify('+1 hour');
                $after1h->setTimestamp($courses[0]->getDtEnd()
                    ->getTimestamp());
                $traineeNotifEmailSE = null;
                foreach ($traineeNotifs as $notif) {
                    if ($notif->getType() == TraineeNotif::TYPE_EMAIL_SURVEYEND) {
                        $traineeNotifEmailSE = $notif;
                    }
                }

                if (null == $traineeNotifEmailSE) {
                    $traineeNotifEmailSE = new TraineeNotif();
                    $traineeNotifEmailSE->setType(TraineeNotif::TYPE_EMAIL_SURVEYEND);
                    $traineeNotifEmailSE->setTrainee($this->getTrainee());
                    $traineeNotifEmailSE->setStatus(TraineeNotif::PENDING);
                    $traineeNotifEmailSE->setDtStart($after1h);
                    $traineeNotifEmailSE->setTimeCredit($this);
                    $this->addTraineeNotif($traineeNotifEmailSE);
                } elseif ($traineeNotifEmailSE->getStatus() == TraineeNotif::ERROR) {
                    $traineeNotifEmailSE = new TraineeNotif();
                    $traineeNotifEmailSE->setType(TraineeNotif::TYPE_EMAIL_SURVEYEND);
                    $traineeNotifEmailSE->setTrainee($this->getTrainee());
                    $traineeNotifEmailSE->setStatus(TraineeNotif::PENDING);
                    $traineeNotifEmailSE->setDtStart($after1h);
                    $traineeNotifEmailSE->setTimeCredit($this);
                    $this->addTraineeNotif($traineeNotifEmailSE);
                } elseif ($traineeNotifEmailSE->getStatus() == TraineeNotif::PENDING) {
                    $traineeNotifEmailSE->setTrainee($this->getTrainee());
                    $traineeNotifEmailSE->setStatus(TraineeNotif::PENDING);
                    $traineeNotifEmailSE->setDtStart($after1h);
                    $traineeNotifEmailSE->setTimeCredit($this);
                    $this->addTraineeNotif($traineeNotifEmailSE);
                }
            } else {
                foreach ($traineeNotifs as $notif) {
                    if ($notif->getType() == TraineeNotif::TYPE_EMAIL_SURVEYEND) {
                        if ($notif->getStatus() == TraineeNotif::PENDING) {
                            $this->removeTraineeNotif($notif);
                        }
                    }
                }
            }
        } else {
            foreach ($traineeNotifs as $notif) {
                if ($notif->getType() == TraineeNotif::TYPE_EMAIL_SURVEYEND) {
                    if ($notif->getStatus() == TraineeNotif::PENDING) {
                        $this->removeTraineeNotif($notif);
                    }
                }
            }
        }

        $traineeNotifs = $this->getTraineeNotifs();
        if (null == $traineeNotifs) {
            $traineeNotifs = new ArrayCollection();
        }
        // trainee notif email +15d
        if (count($courses) < $this->getTotalHours() && count($courses) > 0) {

            $traineeNotif15d = null;
            foreach ($traineeNotifs as $notif) {
                if ($notif->getType() == TraineeNotif::TYPE_EMAIL_15D_AFTER_COURS) {
                    $traineeNotif15d = $notif;
                }
            }
            $after15d = new DateTime('now');
            $after15d->modify('+1 hour');
            $i = 0;
            $lastCoursFound = false;
            do {
                $lastCours = $courses[$i];
                if ($lastCours instanceof Cours) {
                    $lastCoursFound = true;
                    $after15d->setTimestamp($lastCours->getDtEnd()
                        ->getTimestamp());
                }
                $i++;
            } while ($i < count($courses) && $lastCoursFound == true);
            $after15d->modify('+15 days');

            if (null == $traineeNotif15d) {
                $traineeNotif15d = new TraineeNotif();
                $traineeNotif15d->setType(TraineeNotif::TYPE_EMAIL_15D_AFTER_COURS);
                $traineeNotif15d->setTrainee($this->getTrainee());
                $traineeNotif15d->setStatus(TraineeNotif::PENDING);
                $traineeNotif15d->setDtStart($after15d);
                $traineeNotif15d->setTimeCredit($this);
                $this->addTraineeNotif($traineeNotif15d);
            } elseif ($traineeNotif15d->getStatus() == TraineeNotif::ERROR) {
                $traineeNotif15d = new TraineeNotif();
                $traineeNotif15d->setType(TraineeNotif::TYPE_EMAIL_15D_AFTER_COURS);
                $traineeNotif15d->setTrainee($this->getTrainee());
                $traineeNotif15d->setStatus(TraineeNotif::PENDING);
                $traineeNotif15d->setDtStart($after15d);
                $traineeNotif15d->setTimeCredit($this);
                $this->addTraineeNotif($traineeNotif15d);
            } elseif ($traineeNotif15d->getStatus() == TraineeNotif::PENDING) {
                $traineeNotif15d->setTrainee($this->getTrainee());
                $traineeNotif15d->setStatus(TraineeNotif::PENDING);
                $traineeNotif15d->setDtStart($after15d);
                $traineeNotif15d->setTimeCredit($this);
                $this->addTraineeNotif($traineeNotif15d);
            }
        } else {
            foreach ($adminNotifs as $notif) {
                if ($notif->getType() == TraineeNotif::TYPE_EMAIL_15D_AFTER_COURS) {
                    if ($notif->getStatus() == TraineeNotif::PENDING) {
                        $this->removeTraineeNotif($notif);
                    }
                }
            }
        }

        $traineeNotifs = $this->getTraineeNotifs();
        if (null == $traineeNotifs) {
            $traineeNotifs = new ArrayCollection();
        }
        // trainee notif email +30d
        if (count($courses) < $this->getTotalHours() && count($courses) > 0) {

            $traineeNotif30d = null;
            foreach ($traineeNotifs as $notif) {
                if ($notif->getType() == TraineeNotif::TYPE_EMAIL_30D_AFTER_COURS) {
                    $traineeNotif30d = $notif;
                }
            }
            $after30d = new DateTime('now');
            $after30d->modify('+1 hour');
            $i = 0;
            $lastCoursFound = false;
            do {
                $lastCours = $courses[$i];
                if ($lastCours instanceof Cours) {
                    $lastCoursFound = true;
                    $after30d->setTimestamp($lastCours->getDtEnd()
                        ->getTimestamp());
                }
                $i++;
            } while ($i < count($courses) && $lastCoursFound == true);
            $after30d->modify('+30 days');

            if (null == $traineeNotif30d) {
                $traineeNotif30d = new TraineeNotif();
                $traineeNotif30d->setType(TraineeNotif::TYPE_EMAIL_30D_AFTER_COURS);
                $traineeNotif30d->setTrainee($this->getTrainee());
                $traineeNotif30d->setStatus(TraineeNotif::PENDING);
                $traineeNotif30d->setDtStart($after30d);
                $traineeNotif30d->setTimeCredit($this);
                $this->addTraineeNotif($traineeNotif30d);
            } elseif ($traineeNotif30d->getStatus() == TraineeNotif::ERROR) {
                $traineeNotif30d->setTrainee($this->getTrainee());
                $traineeNotif30d->setStatus(TraineeNotif::PENDING);
                $traineeNotif30d->setDtStart($after30d);
                $traineeNotif30d->setTimeCredit($this);
                $this->addTraineeNotif($traineeNotif30d);
            } elseif ($traineeNotif30d->getStatus() == TraineeNotif::PENDING) {
                $traineeNotif30d->setTrainee($this->getTrainee());
                $traineeNotif30d->setStatus(TraineeNotif::PENDING);
                $traineeNotif30d->setDtStart($after30d);
                $traineeNotif30d->setTimeCredit($this);
                $this->addTraineeNotif($traineeNotif30d);
            }
        } else {
            foreach ($adminNotifs as $notif) {
                if ($notif->getType() == TraineeNotif::TYPE_EMAIL_30D_AFTER_COURS) {
                    if ($notif->getStatus() == TraineeNotif::PENDING) {
                        $this->removeTraineeNotif($notif);
                    }
                }
            }
        }
    }
}
