<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Document;

use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\HttpFoundation\File\File;

/**
 * AdminAvatar Document
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *         @MongoDB\Document(collection="admin_avatars",
 *         repositoryClass="Ilcfrance\Worldspeak\Shared\DataBundle\Repository\AdminAvatarRepository")
 */
class AdminAvatar
{

	/**
	 *
	 * @var guid @MongoDB\Id(strategy="UUID")
	 */
	protected $id;

	/**
	 *
	 * @var File @MongoDB\File()
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
	 * @var \DateTime @MongoDB\Field(type="date")
	 */
	protected $dtCrea;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->dtCrea = new \DateTime('now');
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
	 * Set file
	 *
	 * @param File $file
	 *
	 * @return AdminAvatar
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
	 * @return AdminAvatar
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
	 * @return AdminAvatar
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
	 * @return AdminAvatar
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
	 * @return AdminAvatar
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
	 * @return AdminAvatar
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
	 * @return AdminAvatar
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
	 * @param \DateTime $dtCrea
	 *
	 * @return AdminAvatar
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
	 * string representation of the object
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->getFilename();
	}
}
