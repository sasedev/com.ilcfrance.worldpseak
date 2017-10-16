<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Locale;
use Ilcfrance\Worldspeak\Shared\DataBundle\Repository\LocaleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Edit Teacher PreferedLocale Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TeacherPreferedLocaleTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'TeacherPreferedLocaleForm';
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
        $builder->add('preferedLocale', EntityType::class, array(
            'label' => 'Teacher.preferedLocale.label',
            'class' => 'IlcfranceWorldspeakSharedDataBundle:Locale',
            'query_builder' => function (LocaleRepository $lr) {
                return $lr->createQueryBuilder('l')
                    ->where('l.status= :status')
                    ->setParameter('status', Locale::STATUS_ACTIF)
                    ->orderBy('l.name', 'ASC');
            },
            'choice_label' => 'name',
            'by_reference' => true,
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
