<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Cours Correction Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class CoursCorrectionTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'CoursCorrectionForm';
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
        $builder->add('progress', TextareaType::class, array(
            'label' => 'Cours.progress.label',
            'required' => false
        ));

        $builder->add('correctionVocabulairy', TextareaType::class, array(
            'label' => 'Cours.correctionVocabulairy.label'
        ));

        $builder->add('correctionStructure', TextareaType::class, array(
            'label' => 'Cours.correctionStructure.label'
        ));

        $builder->add('correctionPrononciation', TextareaType::class, array(
            'label' => 'Cours.correctionPrononciation.label'
        ));

        $builder->add('comments', TextareaType::class, array(
            'label' => 'Cours.comments.label',
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
