<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Locale;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Teacher;
use Ilcfrance\Worldspeak\Shared\DataBundle\Repository\LocaleRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Add new Teacher Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TeacherAddTForm extends AbstractType
{

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('username', TextType::class, array(
			'label' => 'Teacher.username.label'
		));

		$builder->add('email', EmailType::class, array(
			'label' => 'Teacher.email.label'
		));

		$builder->add('lockout', ChoiceType::class, array(
			'label' => 'Teacher.lockout.label',
			'choices' => Teacher::choiceLockout()
		));

		$builder->add('registerMail', ChoiceType::class, array(
			'label' => 'Teacher.registerMail.label',
			'choices' => Teacher::choiceRegisterMail()
		));

		$builder->add('type', ChoiceType::class, array(
			'label' => 'Teacher.type.label',
			'choices' => Teacher::choiceType()
		));

		$builder->add('ftype', ChoiceType::class, array(
			'label' => 'Teacher.ftype.label',
			'choices' => Teacher::choiceFtype()
		));

		$builder->add('coursPhone', TextType::class, array(
			'label' => 'Teacher.coursPhone.label'
		));

		$builder->add('sexe', ChoiceType::class, array(
			'label' => 'Teacher.sexe.label',
			'choices' => Teacher::choiceSexe(),
			'required' => false
		));

		$builder->add('lastName', TextType::class, array(
			'label' => 'Teacher.lastName.label'
		));

		$builder->add('firstName', TextType::class, array(
			'label' => 'Teacher.firstName.label'
		));

		$builder->add('preferedLocale', EntityType::class, array(
			'label' => 'Teacher.preferedLocale.label',
			'class' => 'IlcfranceWorldspeakSharedDataBundle:Locale',
			'query_builder' => function (LocaleRepository $lr) {
				return $lr->createQueryBuilder('l')->where('l.status= :status')->setParameter('status', Locale::STATUS_ACTIF)->orderBy('l.name', 'ASC');
			},
			'choice_label' => 'name',
			'by_reference' => true,
			'required' => false
		));

		$builder->add('phone', TextType::class, array(
			'label' => 'Teacher.phone.label'
		));

		$builder->add('mobile', TextType::class, array(
			'label' => 'Teacher.mobile.label'
		));

		$builder->add('submit', SubmitType::class, array(
			'label' => '_action.btnAdd'
		));
	}

	/**
	 * (non-PHPdoc) @see \Symfony\Component\Form\FormTypeInterface::getName()
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'TeacherAddForm';
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
	 * get the default options
	 *
	 * @return multitype:string multitype:string
	 */
	public function getDefaultOptions()
	{
		return array(
			'validation_groups' => array(
				'admRegistration',
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
