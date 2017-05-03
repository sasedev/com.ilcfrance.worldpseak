<?php
namespace Ilcfrance\Worldspeak\Teacher\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Ilcfrance\Worldspeak\Shared\DataBundle\Repository\LocaleRepository;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Locale;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Form Class used in user profile to change prefered interface Locale
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class UserEditPreferedLocaleTForm extends AbstractType
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
		$builder->add('preferedLocale', EntityType::class, array(
			'label' => '_sec.preferedLocale.label',
			'class' => 'IlcfranceWorldspeakSharedDataBundle:Locale',
			'query_builder' => function (LocaleRepository $er) {
				return $er->createQueryBuilder('l')->where('l.status = :status')->setParameter('status', Locale::STATUS_ACTIF)->orderBy('l.prefix', 'ASC');
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
		return 'UserEditPreferedLocaleForm';
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
