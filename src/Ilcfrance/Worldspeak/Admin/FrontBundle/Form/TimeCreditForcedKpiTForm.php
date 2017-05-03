<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCredit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * TimeCredit Forced Kpi Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TimeCreditForcedKpiTForm extends AbstractType
{

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('forcedKpiHomeworkPerformed', ChoiceType::class, array(
			'label' => 'TimeCredit.forcedKpiHomeworkPerformed.label',
			'choices' => TimeCredit::choiceKPI(),
			'required' => false
		));

		$builder->add('forcedKpiParticipation', ChoiceType::class, array(
			'label' => 'TimeCredit.forcedKpiParticipation.label',
			'choices' => TimeCredit::choiceKPI(),
			'required' => false
		));

		$builder->add('forcedKpiVocabularyRetention', ChoiceType::class, array(
			'label' => 'TimeCredit.forcedKpiVocabularyRetention.label',
			'choices' => TimeCredit::choiceKPI(),
			'required' => false
		));

		$builder->add('forcedKpiCorrectionConsideration', ChoiceType::class, array(
			'label' => 'TimeCredit.forcedKpiCorrectionConsideration.label',
			'choices' => TimeCredit::choiceKPI(),
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
		return 'TimeCreditForcedKpiForm';
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
				'admKPI',
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
