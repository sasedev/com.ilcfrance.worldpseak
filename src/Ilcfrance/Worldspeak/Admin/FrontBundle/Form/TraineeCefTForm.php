<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Trainee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Edit Trainee Cef Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TraineeCefTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'TraineeCefForm';
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
                'admCef',
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
        $builder->add('cef', ChoiceType::class, array(
            'label' => 'Trainee.cef.label',
            'choices' => Trainee::choiceCef()
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
