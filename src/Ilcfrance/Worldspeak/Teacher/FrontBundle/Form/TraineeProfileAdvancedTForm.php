<?php
namespace Ilcfrance\Worldspeak\Teacher\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Edit Trainee Profile Advanced Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TraineeProfileAdvancedTForm extends AbstractType
{

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('job', TextType::class, array(
			'label' => 'Trainee.job.label',
			'required' => false
		));

		$builder->add('responsabilities', TextType::class, array(
			'label' => 'Trainee.responsabilities.label',
			'required' => false
		));

		$builder->add('needs', TextareaType::class, array(
			'label' => 'Trainee.needs.label',
			'required' => false
		));

		$builder->add('outsideInterests', TextareaType::class, array(
			'label' => 'Trainee.outsideInterests.label',
			'required' => false
		));

		$builder->add('comments', TextareaType::class, array(
			'label' => 'Trainee.comments.label',
			'required' => false
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
		return 'TraineeProfileAdvancedForm';
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
