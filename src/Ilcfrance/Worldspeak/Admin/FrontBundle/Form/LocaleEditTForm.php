<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Locale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Edit Locale Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class LocaleEditTForm extends AbstractType
{

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('prefix', TextType::class, array(
			'label' => 'Locale.prefix.label'
		));

		$builder->add('name', TextType::class, array(
			'label' => 'Locale.name.label'
		));

		$builder->add('direction', ChoiceType::class, array(
			'label' => 'Locale.direction.label',
			'choices' => Locale::choiceDirection(),
			'expanded' => true
		));

		$builder->add('status', ChoiceType::class, array(
			'label' => 'Locale.status.label',
			'choices' => Locale::choiceStatus(),
			'expanded' => true
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
		return 'LocaleEditForm';
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
