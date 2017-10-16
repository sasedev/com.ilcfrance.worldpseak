<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Locale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Add new Locale Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class LocaleAddTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'LocaleAddForm';
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
     * Form builder
     *
     * {@inheritdoc}
     * @see AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('prefix', TextType::class, array(
            'label' => 'Locale.prefix.label'
        ));

        $builder->add('name', TextType::class, array(
            'label' => 'Locale.name.label'
        ));

        $builder->add('direction', ChoiceType::class, array(
            'label' => 'Locale.direction.label',
            'choices' => Locale::choiceDirection(),
            'expanded' => true
        ));

        $builder->add('status', ChoiceType::class, array(
            'label' => 'Locale.status.label',
            'choices' => Locale::choiceStatus(),
            'expanded' => true
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
