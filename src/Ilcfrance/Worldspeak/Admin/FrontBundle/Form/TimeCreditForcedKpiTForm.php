<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCredit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * TimeCredit Forced Kpi Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TimeCreditForcedKpiTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'TimeCreditForcedKpiForm';
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
                'admKPI',
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
        $builder->add('forcedKpiHomeworkPerformed', ChoiceType::class, array(
            'label' => 'TimeCredit.forcedKpiHomeworkPerformed.label',
            'choices' => TimeCredit::choiceKPI(),
            'required' => false
        ));

        $builder->add('forcedKpiParticipation', ChoiceType::class, array(
            'label' => 'TimeCredit.forcedKpiParticipation.label',
            'choices' => TimeCredit::choiceKPI(),
            'required' => false
        ));

        $builder->add('forcedKpiVocabularyRetention', ChoiceType::class, array(
            'label' => 'TimeCredit.forcedKpiVocabularyRetention.label',
            'choices' => TimeCredit::choiceKPI(),
            'required' => false
        ));

        $builder->add('forcedKpiCorrectionConsideration', ChoiceType::class, array(
            'label' => 'TimeCredit.forcedKpiCorrectionConsideration.label',
            'choices' => TimeCredit::choiceKPI(),
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
