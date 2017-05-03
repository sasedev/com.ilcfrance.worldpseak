<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Company Edit Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class CompanyEditTForm extends AbstractType
{

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', TextType::class, array(
			'label' => 'Company.name.label'
		));

		$builder->add('prefix', TextType::class, array(
			'label' => 'Company.prefix.label'
		));

		$builder->add('service', TextType::class, array(
			'label' => 'Company.service.label',
			'required' => false
		));

		$builder->add('address', TextareaType::class, array(
			'label' => 'Company.address.label',
			'required' => false
		));

		$builder->add('town', TextType::class, array(
			'label' => 'Company.town.label',
			'required' => false
		));

		$builder->add('postalCode', TextType::class, array(
			'label' => 'Company.postalCode.label',
			'required' => false
		));

		$builder->add('country', CountryType::class, array(
			'label' => 'Company.country.label',
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
		return 'CompanyEditForm';
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
