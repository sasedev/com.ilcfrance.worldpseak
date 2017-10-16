<?php
namespace Ilcfrance\Worldspeak\Trainee\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCredit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form for TimeCredit SurveyBegin
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class SurveyBeginTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'SurveyBeginForm';
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
        $builder->add('surveyBeginQ01', ChoiceType::class, array(
            'label' => 'TimeCredit.surveyBeginQ01',
            'choices' => TimeCredit::choiceSurvey()
        ));

        $builder->add('surveyBeginQ02', ChoiceType::class, array(
            'label' => 'TimeCredit.surveyBeginQ02',
            'choices' => TimeCredit::choiceSurvey()
        ));

        $builder->add('surveyBeginQ03', ChoiceType::class, array(
            'label' => 'TimeCredit.surveyBeginQ03',
            'choices' => TimeCredit::choiceSurvey()
        ));

        $builder->add('surveyBeginQ04', ChoiceType::class, array(
            'label' => 'TimeCredit.surveyBeginQ04',
            'choices' => TimeCredit::choiceSurvey()
        ));

        $builder->add('surveyBeginQ05', ChoiceType::class, array(
            'label' => 'TimeCredit.surveyBeginQ05',
            'choices' => TimeCredit::choiceSurvey()
        ));

        $builder->add('surveyBeginQ06', ChoiceType::class, array(
            'label' => 'TimeCredit.surveyBeginQ06',
            'choices' => TimeCredit::choiceSurvey()
        ));

        $builder->add('surveyBeginQ07', TextareaType::class, array(
            'label' => 'TimeCredit.surveyBeginQ07'
        ));

        $builder->add('surveyBeginQ08', TextareaType::class, array(
            'label' => 'TimeCredit.surveyBeginQ08'
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
