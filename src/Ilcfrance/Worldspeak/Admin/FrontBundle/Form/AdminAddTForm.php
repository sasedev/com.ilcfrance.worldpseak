<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Admin;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Locale;
use Ilcfrance\Worldspeak\Shared\DataBundle\Repository\LocaleRepository;
use Ilcfrance\Worldspeak\Shared\DataBundle\Repository\RoleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Add new Admin Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class AdminAddTForm extends AbstractType
{

    /**
     *
     * @var boolean
     */
    private $selrole = false;

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'AdminAddForm';
    }

    /**
     * get the default options
     *
     * @return array
     */
    public function getDefaultOptions()
    {
        return array(
            'validation_groups' => array(
                'admRegistration',
                'Default'
            ),
            'selrole' => false
        );
    }

    /**
     * Form builder
     *
     * {@inheritdoc}
     * @see AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->selrole = $options['selrole'];

        $builder->add('username', TextType::class, array(
            'label' => 'Admin.username.label'
        ));

        $builder->add('email', EmailType::class, array(
            'label' => 'Admin.email.label'
        ));

        if ($this->selrole == true) {
            $builder->add('adminRoles', EntityType::class, array(
                'label' => 'Admin.adminRoles.label',
                'class' => 'IlcfranceWorldspeakSharedDataBundle:Role',
                'query_builder' => function (RoleRepository $rr) {
                    return $rr->createQueryBuilder('r')
                        ->where('r.name = :role_admin')
                        ->orWhere('r.name = :role_super_admin')
                        ->setParameter('role_admin', 'ROLE_ADMIN')
                        ->setParameter('role_super_admin', 'ROLE_SUPER_ADMIN')
                        ->orderBy('r.name', 'ASC');
                },
                'choice_label' => 'name',
                'expanded' => true,
                'mapped' => false
            ));
        }

        $builder->add('sexe', ChoiceType::class, array(
            'label' => 'Admin.sexe.label',
            'choices' => Admin::choiceSexe(),
            'required' => false
        ));

        $builder->add('lastName', TextType::class, array(
            'label' => 'Admin.lastName.label'
        ));

        $builder->add('firstName', TextType::class, array(
            'label' => 'Admin.firstName.label'
        ));

        $builder->add('preferedLocale', EntityType::class, array(
            'label' => 'Admin.preferedLocale.label',
            'class' => 'IlcfranceWorldspeakSharedDataBundle:Locale',
            'query_builder' => function (LocaleRepository $lr) {
                return $lr->createQueryBuilder('l')
                    ->where('l.status= :status')
                    ->setParameter('status', Locale::STATUS_ACTIF)
                    ->orderBy('l.name', 'ASC');
            },
            'choice_label' => 'name',
            'by_reference' => true,
            'required' => false
        ));
    }

    /**
     *
     * {@inheritdoc}
     * @see AbstractType::getBlockPrefix()
     */
    public function getBlockPrefix()
    {
        return $this->getName();
    }

    /**
     *
     * {@inheritdoc}
     * @see AbstractType::configureOptions()
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults($this->getDefaultOptions());
    }
}
