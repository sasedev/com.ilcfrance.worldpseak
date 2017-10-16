<?php
namespace Ilcfrance\Worldspeak\Trainee\SecurityBundle\Form;

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
 * Form Class used in user profile to change personnal data
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class UserEditProfileTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'UserEditProfileForm';
    }

    /**
     *
     * @return array
     */
    public function getDefaultOptions()
    {
        return array(
            'validation_groups' => array(
                'profile',
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
            'label' => '_sec.sexe.label',
            'choices' => Trainee::choiceSexe()
        ));

        $builder->add('lastName', TextType::class, array(
            'label' => '_sec.lastName.label'
        ));

        $builder->add('firstName', TextType::class, array(
            'label' => '_sec.firstName.label'
        ));

        $builder->add('birthday', DateType::class, array(
            'label' => '_sec.birthday.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false
        ));

        $builder->add('address', TextareaType::class, array(
            'label' => '_sec.address.label',
            'required' => false
        ));

        $builder->add('country', CountryType::class, array(
            'label' => '_sec.country.label',
            'required' => false
        ));

        $builder->add('phone', TextType::class, array(
            'label' => '_sec.phone.label',
            'required' => false
        ));

        $builder->add('mobile', TextType::class, array(
            'label' => '_sec.mobile.label',
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
