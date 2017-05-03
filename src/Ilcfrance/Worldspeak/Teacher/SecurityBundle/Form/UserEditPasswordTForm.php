<?php
namespace Ilcfrance\Worldspeak\Teacher\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Form Class used to update user password
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class UserEditPasswordTForm extends AbstractType
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
		$builder->add('oldPassword', PasswordType::class, array(
			'label' => '_sec.oldPassword.label',
			'mapped' => false
		));

		$builder->add('clearPassword', RepeatedType::class, array(
			'type' => PasswordType::class,
			'invalid_message' => '_sec.newPassword.repeat.notequal',
			'first_options' => array(
				'label' => '_sec.newPassword.first',
				'attr' => array(
					'label_col' => 3,
					'widget_col' => 2
				)
			),
			'second_options' => array(
				'label' => '_sec.newPassword.second',
				'attr' => array(
					'label_col' => 3,
					'widget_col' => 2
				)
			)
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
		return 'UserEditPasswordForm';
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
