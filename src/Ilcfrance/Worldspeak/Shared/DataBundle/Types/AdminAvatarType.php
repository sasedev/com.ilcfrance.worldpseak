<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Doctrine\ODM\MongoDB\DocumentManager;
use Ilcfrance\Worldspeak\Shared\DataBundle\Document\AdminAvatar;

/**
 * Bridge between MongoDb AdminAvatar and Doctrine Entity Admin
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class AdminAvatarType extends Type
{

    /**
     *
     * @var string
     */
    const NAME = 'AdminAvatar';

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
     * @return AdminAvatarType
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

        if ($value instanceof AdminAvatar) {
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

        return $this->dm->getReference('Ilcfrance\Worldspeak\Shared\DataBundle\Document\AdminAvatar', str_replace("-", "", $value));
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
