<?php
namespace Ilcfrance\Worldspeak\Teacher\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Cours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Cours Status Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class CoursStatusTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'CoursStatusForm';
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
                'teacherStatus',
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
        $builder->add('status', ChoiceType::class, array(
            'label' => 'Cours.status.label',
            'choices' => Cours::choiceStatusForTeachers()
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
