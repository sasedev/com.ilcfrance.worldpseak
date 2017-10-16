<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Doctrine\ODM\MongoDB\DocumentManager;
use Ilcfrance\Worldspeak\Shared\DataBundle\Document\TraineeFile;

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
     *
     * {@inheritdoc}
     * @see Type::convertToDatabaseValue()
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
     *
     * {@inheritdoc}
     * @see Type::convertToPHPValue()
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        return $this->dm->getReference('Ilcfrance\Worldspeak\Shared\DataBundle\Document\TraineeFile', str_replace("-", "", $value));
    }

    /**
     *
     * {@inheritdoc}
     * @see Type::getSQLDeclaration()
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getClobTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     *
     * {@inheritdoc}
     * @see Type::getName()
     */
    public function getName()
    {
        return self::NAME;
    }
}
