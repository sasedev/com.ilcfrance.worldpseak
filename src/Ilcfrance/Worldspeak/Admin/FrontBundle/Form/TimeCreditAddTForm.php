<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCredit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * TimeCredit Add Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TimeCreditAddTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'TimeCreditAddForm';
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
                'admCreation',
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
        $builder->add('ftype', ChoiceType::class, array(
            'label' => 'TimeCredit.ftype.label',
            'choices' => TimeCredit::choiceFtype()
        ));

        $builder->add('level', ChoiceType::class, array(
            'label' => 'TimeCredit.level.label',
            'choices' => TimeCredit::choiceLevel()
        ));

        $builder->add('cefBegin', ChoiceType::class, array(
            'label' => 'TimeCredit.cefBegin.label',
            'choices' => TimeCredit::choiceCef()
        ));

        $builder->add('totalHours', IntegerType::class, array(
            'label' => 'TimeCredit.totalHours.label'
        ));

        $builder->add('deadLine', DateType::class, array(
            'label' => 'TimeCredit.deadLine.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false
        ));

        $builder->add('notifyByMail', ChoiceType::class, array(
            'label' => 'TimeCredit.notifyByMail.label',
            'choices' => TimeCredit::choiceNotifyByMail()
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
