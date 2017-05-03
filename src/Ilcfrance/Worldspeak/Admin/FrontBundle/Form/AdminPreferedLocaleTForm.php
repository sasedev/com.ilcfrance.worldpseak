<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Locale;
use Ilcfrance\Worldspeak\Shared\DataBundle\Repository\LocaleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Edit Admin Prefered Locale Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class AdminPreferedLocaleTForm extends AbstractType
{

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('preferedLocale', EntityType::class, array(
			'label' => 'Admin.preferedLocale.label',
			'class' => 'IlcfranceWorldspeakSharedDataBundle:Locale',
			'query_builder' => function (LocaleRepository $lr) {
				return $lr->createQueryBuilder('l')->where('l.status= :status')->setParameter('status', Locale::STATUS_ACTIF)->orderBy('l.name', 'ASC');
			},
			'choice_label' => 'name',
			'by_reference' => true,
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
		return 'AdminPreferedLocaleForm';
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
