<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Locale;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Trainee;
use Ilcfrance\Worldspeak\Shared\DataBundle\Repository\CompanyRepository;
use Ilcfrance\Worldspeak\Shared\DataBundle\Repository\LocaleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Add new Trainee Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TraineeAddTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'TraineeAddForm';
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
            )
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
        $builder->add('company', EntityType::class, array(
            'label' => 'Trainee.company.label',
            'class' => 'IlcfranceWorldspeakSharedDataBundle:Company',
            'query_builder' => function (CompanyRepository $cr) {
                return $cr->createQueryBuilder('c')
                    ->orderBy('c.name', 'ASC');
            },
            'choice_label' => 'name',
            'by_reference' => true
        ));

        $builder->add('project', TextType::class, array(
            'label' => 'Trainee.project.label',
            'required' => false
        ));

        $builder->add('email', EmailType::class, array(
            'label' => 'Trainee.email.label'
        ));

        $builder->add('lockout', ChoiceType::class, array(
            'label' => 'Trainee.lockout.label',
            'choices' => Trainee::choiceLockout()
        ));

        $builder->add('registerMail', ChoiceType::class, array(
            'label' => 'Trainee.registerMail.label',
            'choices' => Trainee::choiceRegisterMail()
        ));

        $builder->add('sexe', ChoiceType::class, array(
            'label' => 'Trainee.sexe.label',
            'choices' => Trainee::choiceSexe(),
            'required' => false
        ));

        $builder->add('lastName', TextType::class, array(
            'label' => 'Trainee.lastName.label'
        ));

        $builder->add('firstName', TextType::class, array(
            'label' => 'Trainee.firstName.label'
        ));

        $builder->add('preferedLocale', EntityType::class, array(
            'label' => 'Trainee.preferedLocale.label',
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

        $builder->add('phone', TextType::class, array(
            'label' => 'Trainee.phone.label',
            'required' => false
        ));

        $builder->add('mobile', TextType::class, array(
            'label' => 'Trainee.mobile.label',
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
