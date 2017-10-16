<?php
namespace Ilcfrance\Worldspeak\Admin\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class LoginTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return "LoginForm";
    }

    /**
     * get the default options
     *
     * @return array
     */
    public function getDefaultOptions()
    {
        return array(
            'csrf_protection' => false
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
        $builder->add('username', TextType::class, array(
            'label' => '_sec.username.label',
            'constraints' => array(
                new NotBlank()
            )
        ));

        $builder->add('password', PasswordType::class, array(
            'label' => '_sec.password.label',
            'constraints' => array(
                new NotBlank()
            )
        ));

        $builder->add('remember_me', CheckboxType::class, array(
            'label' => '_sec.rememberme',
            'required' => false
        ));

        $builder->add('target_path', HiddenType::class, array(
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
