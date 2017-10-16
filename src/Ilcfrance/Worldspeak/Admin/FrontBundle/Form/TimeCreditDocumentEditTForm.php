<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Ilcfrance\Worldspeak\Shared\DataBundle\Repository\TeachingResourceRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * TimeCreditDocument Edit Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TimeCreditDocumentEditTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'TimeCreditDocumentEditForm';
    }

    /**
     * get the default options
     *
     * @return array
     */
    public function getDefaultOptions()
    {
        return array(
            'level' => -1
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
        $lvl = $options['level'];

        $builder->add('teachingResource', DocumentType::class, array(
            'label' => 'TimeCreditDocument.teachingResource.label',
            'class' => 'IlcfranceWorldspeakSharedDataBundle:TeachingResource',
            'query_builder' => function (TeachingResourceRepository $tr) use ($lvl) {
                if (null != $lvl && $lvl != -1) {
                    return $tr->createQueryBuilder()
                        ->field('level')
                        ->equals($lvl)
                        ->sort('type', 'ASC')
                        ->sort('filename', 'DESC');
                } else {
                    return $tr->createQueryBuilder()
                        ->sort('level', 'ASC')
                        ->sort('type', 'ASC')
                        ->sort('filename', 'DESC');
                }
            },
            'choice_label' => 'filename',
            'by_reference' => true,
            'required' => false
        ));

        $builder->add('msg', TextareaType::class, array(
            'label' => 'TimeCreditDocument.msg.label',
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
