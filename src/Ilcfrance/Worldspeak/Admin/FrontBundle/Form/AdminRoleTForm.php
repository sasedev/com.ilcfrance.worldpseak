<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Ilcfrance\Worldspeak\Shared\DataBundle\Repository\RoleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Edit Admin Role Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class AdminRoleTForm extends AbstractType
{

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('adminRoles', EntityType::class, array(
			'label' => 'Admin.adminRoles.label',
			'class' => 'IlcfranceWorldspeakSharedDataBundle:Role',
			'query_builder' => function (RoleRepository $rr) {
				return $rr->createQueryBuilder('r')->where('r.name = :role_admin')->orWhere('r.name = :role_super_admin')->setParameter('role_admin', 'ROLE_ADMIN')->setParameter('role_super_admin', 'ROLE_SUPER_ADMIN')->orderBy('r.name', 'ASC');
			},
			'choice_label' => 'name',
			'expanded' => true,
			'mapped' => false
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
		return 'AdminRoleForm';
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
