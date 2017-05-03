<?php
namespace Ilcfrance\Worldspeak\Trainee\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class CoursDocumentEditTForm extends AbstractType
{

	/**
	 * (non-PHPdoc) @see \Symfony\Component\Form\AbstractType::buildForm()
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('file', FileType::class, array(
			'label' => 'TraineeFile.file.label',
			'constraints' => array(
				new File(array(
					'maxSize' => "20480k"
				))
			),
			'required' => false
		));

		$builder->add('msg', TextareaType::class, array(
			'label' => 'CoursDocument.msg.label',
			'required' => true,
			'mapped' => false
		));

		$builder->add('submit', SubmitType::class, array(
			'label' => '_action.btnEdit'
		));
	}

	/*
	 * (non-PHPdoc) @see \Symfony\Component\Form\FormTypeInterface::getName()
	 */
	public function getName()
	{
		return 'CoursDocumentEditForm';
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
