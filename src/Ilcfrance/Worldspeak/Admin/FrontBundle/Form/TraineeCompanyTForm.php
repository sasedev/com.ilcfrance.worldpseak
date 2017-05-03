<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Ilcfrance\Worldspeak\Shared\DataBundle\Repository\CompanyRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TraineeCompanyTForm extends AbstractType
{

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('company', EntityType::class, array(
			'label' => 'Trainee.company.label',
			'class' => 'IlcfranceWorldspeakSharedDataBundle:Company',
			'query_builder' => function (CompanyRepository $cr) {
				return $cr->createQueryBuilder('c')->orderBy('c.name', 'ASC');
			},
			'choice_label' => 'name',
			'by_reference' => true
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
		return 'TraineeCompanyForm';
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
