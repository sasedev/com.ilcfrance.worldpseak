<?php
namespace Ilcfrance\Worldspeak\Trainee\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Form Class used to initialize password recovery process
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class SecurityLostPasswordTForm extends AbstractType
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
		$builder->add('username', TextType::class, array(
			'label' => '_sec.username.label',
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
		return 'SecurityLostPasswordForm';
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
