<?php
namespace Ilcfrance\Worldspeak\Admin\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form Class used to resize a temporary uploaded Avatar Image
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class UserAvatarResizeTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'UserAvatarResizeForm';
    }

    /**
     * get the default options
     *
     * @return array
     */
    public function getDefaultOptions()
    {
        return array(
            'filename' => null
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
        $builder->add('x1', HiddenType::class, array(
            'required' => true
        ));

        $builder->add('y1', HiddenType::class, array(
            'required' => true
        ));

        $builder->add('w', HiddenType::class, array(
            'required' => true
        ));

        $builder->add('h', HiddenType::class, array(
            'required' => true
        ));

        $builder->add('avatar_tmp', HiddenType::class, array(
            'data' => $options['filename'],
            'required' => true
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
