<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Trainee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Edit Trainee Profile Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TraineeProfileTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'TraineeProfileForm';
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
                'admProfile',
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

        $builder->add('birthday', DateType::class, array(
            'label' => 'Trainee.birthday.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false
        ));

        $builder->add('address', TextareaType::class, array(
            'label' => 'Trainee.address.label',
            'required' => false
        ));

        $builder->add('country', CountryType::class, array(
            'label' => 'Trainee.country.label',
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
