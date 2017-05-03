<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Document\TeachingResource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Add new TeachingResource Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TeachingResourceEditTForm extends AbstractType
{

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('level', ChoiceType::class, array(
			'label' => 'TeachingResource.level.label',
			'choices' => TeachingResource::choiceLevel()
		));

		$builder->add('type', ChoiceType::class, array(
			'label' => 'TeachingResource.type.label',
			'choices' => TeachingResource::choiceType()
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
		return 'TeachingResourceEditForm';
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
