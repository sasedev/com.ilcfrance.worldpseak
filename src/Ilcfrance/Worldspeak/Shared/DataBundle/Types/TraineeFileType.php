<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Types;

use Ilcfrance\Worldspeak\Shared\DataBundle\Document\TraineeFile;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Bridge between MongoDb TraineeFile and Doctrine Entity CoursDocument
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TraineeFileType extends Type
{

	/**
	 *
	 * @var string
	 */
	const NAME = 'TraineeFile';

	/**
	 *
	 * @var DocumentManager
	 */
	private $dm;

	/**
	 * Set DocumentManager
	 *
	 * @param DocumentManager $dm
	 *
	 * @return TraineeFileType
	 */
	public function setDocumentManager(DocumentManager $dm)
	{
		$this->dm = $dm;

		return $this;
	}

	/**
	 * (non-PHPdoc) @see \Doctrine\DBAL\Types\Type::convertToDatabaseValue()
	 *
	 * @param mixed $value
	 *        	The value to convert.
	 * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
	 *        	The currently used database platform.
	 * @return guid
	 * @throws ConversionException
	 */
	public function convertToDatabaseValue($value, AbstractPlatform $platform)
	{
		if (empty($value)) {
			return null;
		}

		if ($value instanceof TraineeFile) {
			return $value->getId();
		}
		throw ConversionException::conversionFailed($value, self::NAME);
	}

	/**
	 * (non-PHPdoc) @see \Doctrine\DBAL\Types\Type::convertToPHPValue()
	 *
	 * @param mixed $value
	 * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
	 *
	 * @return mixed object document reference.
	 */
	public function convertToPHPValue($value, AbstractPlatform $platform)
	{
		if (empty($value)) {
			return null;
		}

		return $this->dm->getReference('Ilcfrance\Worldspeak\Shared\DataBundle\Document\TraineeFile', str_replace("-", "", $value));
	}

	/**
	 * (non-PHPdoc) @see \Doctrine\DBAL\Types\Type::getSQLDeclaration()
	 *
	 * @param array $fieldDeclaration
	 * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
	 *
	 * @return string
	 */
	public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
	{
		return $platform->getClobTypeDeclarationSQL($fieldDeclaration);
	}

	/**
	 * (non-PHPdoc) @see \Doctrine\DBAL\Types\Type::getName()
	 *
	 * @return string
	 */
	public function getName()
	{
		return self::NAME;
	}
}
