<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Document;

use DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TeachingResource Document
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *         @MongoDB\Document(collection="teaching_resources",
 *         repositoryClass="Ilcfrance\Worldspeak\Shared\DataBundle\Repository\TeachingResourceRepository")
 */
class TeachingResource
{

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
    const TYPE_EN_OTHER = 104;

    /**
     *
     * @var string @MongoDB\Id(strategy="UUID")
     */
    protected $id;

    /**
     *
     * @var File @MongoDB\File
     */
    protected $file;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $filename;

    /**
     *
     * @var string @MongoDB\Field(type="string")
     */
    protected $mimeType;

    /**
     *
     * @var integer @MongoDB\Field(type="int")
     */
    protected $length;

    /**
     * @MongoDB\Field
     */
    protected $chunkSize;

    /**
     * @MongoDB\Field
     */
    protected $md5;

    /**
     *
     * @var DateTime @MongoDB\Field(type="date")
     */
    protected $dtCrea;

    /**
     *
     * @var integer @MongoDB\Field(type="int")
     *      @Assert\Choice(callback="choiceLevelCallBack")
     */
    protected $level;

    /**
     *
     * @var integer @MongoDB\Field(type="int")
     *      @Assert\Choice(callback="choiceTypeCallBack")
     */
    protected $type;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dtCrea = new DateTime('now');
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
     * Set file
     *
     * @param File $file
     *
     * @return TeachingResource
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return TeachingResource
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     *
     * @return TeachingResource
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set length
     *
     * @param integer $length
     *
     * @return TeachingResource
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get length
     *
     * @return integer
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set chunkSize
     *
     * @param integer $chunkSize
     *
     * @return TeachingResource
     */
    public function setChunkSize($chunkSize)
    {
        $this->chunkSize = $chunkSize;

        return $this;
    }

    /**
     * Get chunkSize
     *
     * @return integer
     * @return TeachingResource
     */
    public function getChunkSize()
    {
        return $this->chunkSize;
    }

    /**
     * Set md5
     *
     * @param string $md5
     *
     * @return TeachingResource
     */
    public function setMd5($md5)
    {
        $this->md5 = $md5;

        return $this;
    }

    /**
     * Get md5
     *
     * @return string
     */
    public function getMd5()
    {
        return $this->md5;
    }

    /**
     * Set dtCrea
     *
     * @param DateTime $dtCrea
     *
     * @return TeachingResource
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
     * Set level
     *
     * @param integer $level
     *
     * @return TeachingResource
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
     * Set type
     *
     * @param integer $type
     *
     * @return TeachingResource
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
     * Choice Form level
     *
     * @return array
     */
    public static function choiceLevel()
    {
        return array(
            'TeachingResource.level.choice.' . self::LEVEL_EN_LOW => self::LEVEL_EN_LOW,
            'TeachingResource.level.choice.' . self::LEVEL_EN_MEDIUM => self::LEVEL_EN_MEDIUM,
            'TeachingResource.level.choice.' . self::LEVEL_EN_HIGH => self::LEVEL_EN_HIGH
        );
    }

    /**
     * Choice Validator level
     *
     * @return array
     */
    public static function choiceLevelCallBack()
    {
        return array(
            self::LEVEL_EN_LOW,
            self::LEVEL_EN_MEDIUM,
            self::LEVEL_EN_HIGH
        );
    }

    /**
     * Choice Form type
     *
     * @return array
     */
    public static function choiceType()
    {
        return array(
            'TeachingResource.type.choice.' . self::TYPE_EN_VOCAB => self::TYPE_EN_VOCAB,
            'TeachingResource.type.choice.' . self::TYPE_EN_GRAMAR => self::TYPE_EN_GRAMAR,
            'TeachingResource.type.choice.' . self::TYPE_EN_VIDEO => self::TYPE_EN_VIDEO,
            'TeachingResource.type.choice.' . self::TYPE_EN_OTHER => self::TYPE_EN_OTHER
        );
    }

    /**
     * Choice Validator type
     *
     * @return array
     */
    public static function choiceTypeCallBack()
    {
        return array(
            self::TYPE_EN_VOCAB,
            self::TYPE_EN_GRAMAR,
            self::TYPE_EN_VIDEO,
            self::TYPE_EN_OTHER
        );
    }

    /**
     * string representation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getFilename();
    }
}
