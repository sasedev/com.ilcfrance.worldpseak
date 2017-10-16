<?php
namespace Ilcfrance\Worldspeak\Teacher\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Cours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Cours Type Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class CoursTypeTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'CoursTypeForm';
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
                'teacherType',
                'Default'
            )
        );
    }

    /**
     * Form builder
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', ChoiceType::class, array(
            'label' => 'Cours.type.label',
            'choices' => Cours::choiceType()
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
