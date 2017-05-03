<?php
namespace Ilcfrance\Worldspeak\Teacher\SecurityBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Teacher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Form Class used in user profile to change personnal data
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class UserEditProfileTForm extends AbstractType
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
		$builder->add('sexe', ChoiceType::class, array(
			'label' => '_sec.sexe.label',
			'choices' => Teacher::choiceSexe()
		));

		$builder->add('lastName', TextType::class, array(
			'label' => '_sec.lastName.label'
		));

		$builder->add('firstName', TextType::class, array(
			'label' => '_sec.firstName.label'
		));

		$builder->add('birthday', DateType::class, array(
			'label' => '_sec.birthday.label',
			'widget' => 'single_text',
			'format' => 'yyyy-MM-dd',
			'required' => false
		));

		$builder->add('address', TextareaType::class, array(
			'label' => '_sec.address.label',
			'required' => false
		));

		$builder->add('country', CountryType::class, array(
			'label' => '_sec.country.label',
			'required' => false
		));

		$builder->add('phone', TextType::class, array(
			'label' => '_sec.phone.label',
			'required' => false
		));

		$builder->add('mobile', TextType::class, array(
			'label' => '_sec.mobile.label',
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
		return 'UserEditProfileForm';
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
	 * (non-PHPdoc) @see \Symfony\Component\Form\AbstractType::getDefaultOptions()
	 *
	 * @return multitype:string multitype:string
	 */
	public function getDefaultOptions()
	{
		return array(
			'validation_groups' => array(
				'profile',
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
