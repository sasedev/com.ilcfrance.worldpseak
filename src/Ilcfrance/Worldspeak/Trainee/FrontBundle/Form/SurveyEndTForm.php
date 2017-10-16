<?php
namespace Ilcfrance\Worldspeak\Trainee\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCredit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form for TimeCredit SurveyEnd
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class SurveyEndTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'SurveyEndForm';
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
        $builder->add('surveyEndQ01', ChoiceType::class, array(
            'label' => 'TimeCredit.surveyEndQ01',
            'choices' => TimeCredit::choiceSurvey()
        ));

        $builder->add('surveyEndQ02', ChoiceType::class, array(
            'label' => 'TimeCredit.surveyEndQ02',
            'choices' => TimeCredit::choiceSurvey()
        ));

        $builder->add('surveyEndQ03', ChoiceType::class, array(
            'label' => 'TimeCredit.surveyEndQ03',
            'choices' => TimeCredit::choiceSurvey()
        ));

        $builder->add('surveyEndQ04', ChoiceType::class, array(
            'label' => 'TimeCredit.surveyEndQ04',
            'choices' => TimeCredit::choiceSurvey()
        ));

        $builder->add('surveyEndQ05', ChoiceType::class, array(
            'label' => 'TimeCredit.surveyEndQ05',
            'choices' => TimeCredit::choiceSurvey()
        ));

        $builder->add('surveyEndQ06', ChoiceType::class, array(
            'label' => 'TimeCredit.surveyEndQ06',
            'choices' => TimeCredit::choiceSurvey()
        ));

        $builder->add('surveyEndQ07', ChoiceType::class, array(
            'label' => 'TimeCredit.surveyEndQ07',
            'choices' => TimeCredit::choiceSurvey()
        ));

        $builder->add('surveyEndQ08', ChoiceType::class, array(
            'label' => 'TimeCredit.surveyEndQ08',
            'choices' => TimeCredit::choiceSurvey()
        ));

        $builder->add('surveyEndQ09', ChoiceType::class, array(
            'label' => 'TimeCredit.surveyEndQ09',
            'choices' => TimeCredit::choiceSurvey()
        ));

        $builder->add('surveyEndQ10', TextareaType::class, array(
            'label' => 'TimeCredit.surveyEndQ10'
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
