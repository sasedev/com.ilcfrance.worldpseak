<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Doctrine\ODM\MongoDB\DocumentManager;
use Ilcfrance\Worldspeak\Shared\DataBundle\Document\TeachingResource;

/**
 * Bridge between MongoDb TeachingResource and Doctrine Entity TimeCreditDocument
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TeachingResourceType extends Type
{

    /**
     *
     * @var string
     */
    const NAME = 'TeachingResource';

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
     * @return TeachingResourceType
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

        if ($value instanceof TeachingResource) {
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

        return $this->dm->getReference('Ilcfrance\Worldspeak\Shared\DataBundle\Document\TeachingResource', str_replace("-", "", $value));
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
