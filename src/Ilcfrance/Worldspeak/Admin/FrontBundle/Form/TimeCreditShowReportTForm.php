<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCredit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * TimeCredit ShowReport Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TimeCreditShowReportTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'TimeCreditShowReportForm';
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
                'showReport',
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
        $builder->add('showReport', ChoiceType::class, array(
            'label' => 'TimeCredit.showReport.label',
            'choices' => TimeCredit::choiceShowReport()
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
