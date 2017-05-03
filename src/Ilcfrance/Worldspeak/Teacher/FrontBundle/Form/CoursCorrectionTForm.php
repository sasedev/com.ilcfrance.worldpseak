<?php
namespace Ilcfrance\Worldspeak\Teacher\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Cours Correction Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class CoursCorrectionTForm extends AbstractType
{

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('progress', TextareaType::class, array(
			'label' => 'Cours.progress.label'
		));

		$builder->add('correctionVocabulairy', TextareaType::class, array(
			'label' => 'Cours.correctionVocabulairy.label'
		));

		$builder->add('correctionStructure', TextareaType::class, array(
			'label' => 'Cours.correctionStructure.label'
		));

		$builder->add('correctionPrononciation', TextareaType::class, array(
			'label' => 'Cours.correctionPrononciation.label'
		));

		$builder->add('comments', TextareaType::class, array(
			'label' => 'Cours.comments.label'
		));

		$builder->add('submit', SubmitType::class, array(
			'label' => '_action.btnEdit'
		));
	}

	/**
	 * (non-PHPdoc) @see \Symfony\Component\Form\FormTypeInterface::getName()
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'CoursCorrectionForm';
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
