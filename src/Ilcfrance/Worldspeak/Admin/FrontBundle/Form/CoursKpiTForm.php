<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Cours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Cours Kpi Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class CoursKpiTForm extends AbstractType
{

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('kpiHomeworkPerformed', ChoiceType::class, array(
			'label' => 'Cours.kpiHomeworkPerformed.label',
			'choices' => Cours::choiceKPI()
		));

		$builder->add('kpiParticipation', ChoiceType::class, array(
			'label' => 'Cours.kpiParticipation.label',
			'choices' => Cours::choiceKPI()
		));

		$builder->add('kpiVocabularyRetention', ChoiceType::class, array(
			'label' => 'Cours.kpiVocabularyRetention.label',
			'choices' => Cours::choiceKPI()
		));

		$builder->add('kpiCorrectionConsideration', ChoiceType::class, array(
			'label' => 'Cours.kpiCorrectionConsideration.label',
			'choices' => Cours::choiceKPI()
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
		return 'CoursKpiForm';
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
