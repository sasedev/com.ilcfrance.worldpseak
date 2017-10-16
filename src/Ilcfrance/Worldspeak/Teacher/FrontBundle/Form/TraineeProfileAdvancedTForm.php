<?php
namespace Ilcfrance\Worldspeak\Teacher\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Edit Trainee Profile Advanced Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TraineeProfileAdvancedTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'TraineeProfileAdvancedForm';
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
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('job', TextType::class, array(
            'label' => 'Trainee.job.label',
            'required' => false
        ));

        $builder->add('responsabilities', TextType::class, array(
            'label' => 'Trainee.responsabilities.label',
            'required' => false
        ));

        $builder->add('needs', TextareaType::class, array(
            'label' => 'Trainee.needs.label',
            'required' => false
        ));

        $builder->add('outsideInterests', TextareaType::class, array(
            'label' => 'Trainee.outsideInterests.label',
            'required' => false
        ));

        $builder->add('comments', TextareaType::class, array(
            'label' => 'Trainee.comments.label',
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
