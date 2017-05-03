<?php
namespace Ilcfrance\Worldspeak\Trainee\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Form Class used to upload Avatar Image
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class UserAvatarTForm extends AbstractType
{

	/**
	 * BuildForm
	 * (non-PHPdoc) @see \Symfony\Component\Form\AbstractType::buildForm()
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('avatar', FileType::class, array(
			'label' => '_sec.avatar.label',
			'required' => true,
			'constraints' => array(
				new Image(array(
					'mimeTypes' => array(
						'image/png',
						'image/jpg',
						'image/jpeg',
						'image/pjpeg'
					),
					'maxSize' => "4096k"
				))
			)
		));

		$builder->add('submit', SubmitType::class, array(
			'label' => '_action.btnUpload'
		));
	}

	/**
	 * (non-PHPdoc) @see \Symfony\Component\Form\FormTypeInterface::getName()
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'UserAvatarForm';
	}

	/**
	 *
	 * {@inheritdoc} @see AbstractType::getBlockPrefix()
	 */
	public function getBlockPrefix()
	{
		return $this->getName();
	}
}
