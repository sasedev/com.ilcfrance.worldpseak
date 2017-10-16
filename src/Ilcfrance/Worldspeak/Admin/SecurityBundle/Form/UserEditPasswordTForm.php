<?php
namespace Ilcfrance\Worldspeak\Admin\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form Class used to update user password
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class UserEditPasswordTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'UserEditPasswordForm';
    }

    /**
     * get the default options
     *
     * @return array
     */
    public function getDefaultOptions()
    {
        return array();
    }

    /**
     *
     * {@inheritdoc}
     * @see AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('oldPassword', PasswordType::class, array(
            'label' => '_sec.oldPassword.label',
            'mapped' => false
        ));

        $builder->add('clearPassword', RepeatedType::class, array(
            'type' => PasswordType::class,
            'invalid_message' => '_sec.newPassword.repeat.notequal',
            'first_options' => array(
                'label' => '_sec.newPassword.first',
                'attr' => array(
                    'label_col' => 3,
                    'widget_col' => 2
                )
            ),
            'second_options' => array(
                'label' => '_sec.newPassword.second',
                'attr' => array(
                    'label_col' => 3,
                    'widget_col' => 2
                )
            )
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
