<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Edit Admin Email Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class AdminEmailTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'AdminEmailForm';
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
                'admUpdateMail',
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
        $builder->add('email', EmailType::class, array(
            'label' => 'Admin.email.label'
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
