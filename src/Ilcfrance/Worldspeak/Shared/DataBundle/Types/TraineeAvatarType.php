<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Doctrine\ODM\MongoDB\DocumentManager;
use Ilcfrance\Worldspeak\Shared\DataBundle\Document\TraineeAvatar;

/**
 * Bridge between MongoDb TraineeAvatar and Doctrine Entity Trainee
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TraineeAvatarType extends Type
{

    /**
     *
     * @var string
     */
    const NAME = 'TraineeAvatar';

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
     * @return TraineeAvatarType
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

        if ($value instanceof TraineeAvatar) {
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

        return $this->dm->getReference('Ilcfrance\Worldspeak\Shared\DataBundle\Document\TraineeAvatar', str_replace("-", "", $value));
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
