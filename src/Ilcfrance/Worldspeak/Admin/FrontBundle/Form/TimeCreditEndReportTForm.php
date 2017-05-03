<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCredit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * TimeCredit EndReport Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TimeCreditEndReportTForm extends AbstractType
{

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('progress1', ChoiceType::class, array(
			'label' => 'TimeCredit.progress1.label',
			'choices' => TimeCredit::choiceEndReport()
		));

		$builder->add('progress2', ChoiceType::class, array(
			'label' => 'TimeCredit.progress2.label',
			'choices' => TimeCredit::choiceEndReport()
		));

		$builder->add('progress3', ChoiceType::class, array(
			'label' => 'TimeCredit.progress3.label',
			'choices' => TimeCredit::choiceEndReport()
		));

		$builder->add('progress4', ChoiceType::class, array(
			'label' => 'TimeCredit.progress4.label',
			'choices' => TimeCredit::choiceEndReport()
		));

		$builder->add('progress5', ChoiceType::class, array(
			'label' => 'TimeCredit.progress5.label',
			'choices' => TimeCredit::choiceEndReport()
		));

		$builder->add('progress6', ChoiceType::class, array(
			'label' => 'TimeCredit.progress6.label',
			'choices' => TimeCredit::choiceEndReport()
		));

		$builder->add('progress7', ChoiceType::class, array(
			'label' => 'TimeCredit.progress7.label',
			'choices' => TimeCredit::choiceEndReport()
		));

		$builder->add('levelDesc1', ChoiceType::class, array(
			'label' => 'TimeCredit.levelDesc1.label',
			'choices' => TimeCredit::choiceEndReport()
		));

		$builder->add('levelDesc2', ChoiceType::class, array(
			'label' => 'TimeCredit.levelDesc2.label',
			'choices' => TimeCredit::choiceEndReport()
		));

		$builder->add('levelDesc3', ChoiceType::class, array(
			'label' => 'TimeCredit.levelDesc3.label',
			'choices' => TimeCredit::choiceEndReport()
		));

		$builder->add('levelDesc4', ChoiceType::class, array(
			'label' => 'TimeCredit.levelDesc4.label',
			'choices' => TimeCredit::choiceEndReport()
		));

		$builder->add('levelDesc5', ChoiceType::class, array(
			'label' => 'TimeCredit.levelDesc5.label',
			'choices' => TimeCredit::choiceEndReport()
		));

		$builder->add('levelDesc6', ChoiceType::class, array(
			'label' => 'TimeCredit.levelDesc6.label',
			'choices' => TimeCredit::choiceEndReport()
		));

		$builder->add('levelDesc7', ChoiceType::class, array(
			'label' => 'TimeCredit.levelDesc7.label',
			'choices' => TimeCredit::choiceEndReport()
		));

		$builder->add('improvement1', ChoiceType::class, array(
			'label' => 'TimeCredit.improvement1.label',
			'choices' => TimeCredit::choiceImprovement()
		));

		$builder->add('improvement2', ChoiceType::class, array(
			'label' => 'TimeCredit.improvement2.label',
			'choices' => TimeCredit::choiceImprovement()
		));

		$builder->add('improvement3', ChoiceType::class, array(
			'label' => 'TimeCredit.improvement3.label',
			'choices' => TimeCredit::choiceImprovement()
		));

		$builder->add('improvement4', ChoiceType::class, array(
			'label' => 'TimeCredit.improvement4.label',
			'choices' => TimeCredit::choiceImprovement()
		));

		$builder->add('improvement5', ChoiceType::class, array(
			'label' => 'TimeCredit.improvement5.label',
			'choices' => TimeCredit::choiceImprovement()
		));

		$builder->add('lastTeacherReport', TextareaType::class, array(
			'label' => 'TimeCredit.lastTeacherReport.label',
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
		return 'TimeCreditEndReportForm';
	}

	/**
	 *
	 * {@inheritdoc} @see AbstractType::getBlockPrefix()
	 */
	public function getBlockPrefix()
	{
		return $this->getName();
	}

	/**
	 * get the default options
	 *
	 * @return multitype:string multitype:string
	 */
	public function getDefaultOptions()
	{
		return array(
			'validation_groups' => array(
				'endReport',
				'Default'
			)
		);
	}

	/**
	 *
	 * {@inheritdoc} @see AbstractType::configureOptions()
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults($this->getDefaultOptions());
	}
}
