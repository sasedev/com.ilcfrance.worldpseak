<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Document\TeachingResource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

/**
 * Add new TeachingResource Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TeachingResourceAddTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'TeachingResourceAddForm';
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
        $builder->add('level', ChoiceType::class, array(
            'label' => 'TeachingResource.level.label',
            'choices' => TeachingResource::choiceLevel()
        ));

        $builder->add('type', ChoiceType::class, array(
            'label' => 'TeachingResource.type.label',
            'choices' => TeachingResource::choiceType()
        ));

        $builder->add('file', FileType::class, array(
            'label' => 'TeachingResource.file.label',
            'required' => true,
            'constraints' => array(
                new File(array(
                    'maxSize' => "4096k"
                ))
            )
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
