<?php
namespace Ilcfrance\Worldspeak\Teacher\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Cours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Cours Kpi Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class CoursKpiTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'CoursKpiForm';
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
                'teacherKPI',
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
        $builder->add('kpiHomeworkPerformed', ChoiceType::class, array(
            'label' => 'Cours.kpiHomeworkPerformed.label',
            'choices' => Cours::choiceKPI()
        ));

        $builder->add('kpiParticipation', ChoiceType::class, array(
            'label' => 'Cours.kpiParticipation.label',
            'choices' => Cours::choiceKPI()
        ));

        $builder->add('kpiVocabularyRetention', ChoiceType::class, array(
            'label' => 'Cours.kpiVocabularyRetention.label',
            'choices' => Cours::choiceKPI()
        ));

        $builder->add('kpiCorrectionConsideration', ChoiceType::class, array(
            'label' => 'Cours.kpiCorrectionConsideration.label',
            'choices' => Cours::choiceKPI()
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
