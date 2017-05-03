<?php
namespace Ilcfrance\Worldspeak\Trainee\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * Form Class used to resize a temporary uploaded Avatar Image
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class UserAvatarResizeTForm extends AbstractType
{

	private $filename;

	/**
	 * BuildForm
	 * (non-PHPdoc) @see \Symfony\Component\Form\AbstractType::buildForm()
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$this->filename = $options['filename'];
		$builder->add('x1', HiddenType::class, array(
			'required' => true
		));

		$builder->add('y1', HiddenType::class, array(
			'required' => true
		));

		$builder->add('w', HiddenType::class, array(
			'required' => true
		));

		$builder->add('h', HiddenType::class, array(
			'required' => true
		));

		$builder->add('avatar_tmp', HiddenType::class, array(
			'data' => $this->filename,
			'required' => true
		));
	}

	/**
	 * (non-PHPdoc) @see \Symfony\Component\Form\FormTypeInterface::getName()
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'UserAvatarResizeForm';
	}

	/**
	 * get the default options
	 *
	 * @return multitype:string multitype:string
	 */
	public function getDefaultOptions()
	{
		return array(
			'level' => null
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

	/**
	 *
	 * {@inheritdoc} @see AbstractType::getBlockPrefix()
	 */
	public function getBlockPrefix()
	{
		return $this->getName();
	}
}
