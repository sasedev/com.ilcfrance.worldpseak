<?php
namespace Ilcfrance\Worldspeak\Teacher\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCredit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * TimeCredit cefEnd Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TimeCreditCefEndTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'TimeCreditCefEndForm';
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
                'cefEnd',
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
        $builder->add('cefEnd', ChoiceType::class, array(
            'label' => 'TimeCredit.cefEnd.label',
            'choices' => TimeCredit::choiceCef()
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
