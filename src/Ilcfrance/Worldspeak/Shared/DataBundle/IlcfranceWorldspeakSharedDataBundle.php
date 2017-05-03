<?php
namespace Ilcfrance\Worldspeak\Shared\DataBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Ilcfrance\Worldspeak\Shared\DataBundle\Types\AdminAvatarType;
use Ilcfrance\Worldspeak\Shared\DataBundle\Types\TeacherAvatarType;
use Ilcfrance\Worldspeak\Shared\DataBundle\Types\TeachingResourceType;
use Ilcfrance\Worldspeak\Shared\DataBundle\Types\TraineeAvatarType;
use Ilcfrance\Worldspeak\Shared\DataBundle\Types\TraineeFileType;
use Doctrine\DBAL\Types\Type;

class IlcfranceWorldspeakSharedDataBundle extends Bundle
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		if (!Type::hasType(AdminAvatarType::NAME)) {
			Type::addType(AdminAvatarType::NAME, 'Ilcfrance\Worldspeak\Shared\DataBundle\Types\AdminAvatarType');
		}
		if (!Type::hasType(TeacherAvatarType::NAME)) {
			Type::addType(TeacherAvatarType::NAME, 'Ilcfrance\Worldspeak\Shared\DataBundle\Types\TeacherAvatarType');
		}
		if (!Type::hasType(TraineeAvatarType::NAME)) {
			Type::addType(TraineeAvatarType::NAME, 'Ilcfrance\Worldspeak\Shared\DataBundle\Types\TraineeAvatarType');
		}
		if (!Type::hasType(TeachingResourceType::NAME)) {
			Type::addType(TeachingResourceType::NAME, 'Ilcfrance\Worldspeak\Shared\DataBundle\Types\TeachingResourceType');
		}
		if (!Type::hasType(TraineeFileType::NAME)) {
			Type::addType(TraineeFileType::NAME, 'Ilcfrance\Worldspeak\Shared\DataBundle\Types\TraineeFileType');
		}
	}

	/**
	 * (non-PHPdoc) @see \Symfony\Component\HttpKernel\Bundle\Bundle::boot()
	 */
	public function boot()
	{
		$dm = $this->container->get('doctrine_mongodb')->getManager();

		/* @var $type \Ilcfrance\Worldspeak\Shared\DataBundle\Types\AdminAvatarType */
		$adminAvatarType = Type::getType(AdminAvatarType::NAME);
		$adminAvatarType->setDocumentManager($dm);

		/* @var $type \Ilcfrance\Worldspeak\Shared\DataBundle\Types\TeacherAvatarType */
		$teacherAvatarType = Type::getType(TeacherAvatarType::NAME);
		$teacherAvatarType->setDocumentManager($dm);

		/* @var $type \Ilcfrance\Worldspeak\Shared\DataBundle\Types\TraineeAvatarType */
		$traineeAvatarType = Type::getType(TraineeAvatarType::NAME);
		$traineeAvatarType->setDocumentManager($dm);

		/* @var $type \Ilcfrance\Worldspeak\Shared\DataBundle\Types\TeachingResourceType */
		$teachingResourceType = Type::getType(TeachingResourceType::NAME);
		$teachingResourceType->setDocumentManager($dm);

		/* @var $type \Ilcfrance\Worldspeak\Shared\DataBundle\Types\TraineeFileType */
		$traineeFileType = Type::getType(TraineeFileType::NAME);
		$traineeFileType->setDocumentManager($dm);
	}
}
