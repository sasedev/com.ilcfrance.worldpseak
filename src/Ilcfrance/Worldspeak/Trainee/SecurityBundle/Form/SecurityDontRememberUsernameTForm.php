<?php
namespace Ilcfrance\Worldspeak\Trainee\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Form Class used to recover username from email input
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class SecurityDontRememberUsernameTForm extends AbstractType
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
		$builder->add('email', EmailType::class, array(
			'label' => '_sec.email.label',
			'mapped' => false
		));
		$builder->add('submit', SubmitType::class, array(
			'label' => '_action.btnSend'
		));
	}

	/**
	 * (non-PHPdoc) @see \Symfony\Component\Form\FormTypeInterface::getName()
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'SecurityDontRememberUsernameForm';
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
