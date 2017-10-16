<?php
namespace Ilcfrance\Worldspeak\Trainee\SecurityBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Locale;
use Ilcfrance\Worldspeak\Shared\DataBundle\Repository\LocaleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form Class used in user profile to change prefered interface Locale
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class UserEditPreferedLocaleTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'UserEditPreferedLocaleForm';
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
            'label' => '_sec.preferedLocale.label',
            'class' => 'IlcfranceWorldspeakSharedDataBundle:Locale',
            'query_builder' => function (LocaleRepository $er) {
                return $er->createQueryBuilder('l')
                    ->where('l.status = :status')
                    ->setParameter('status', Locale::STATUS_ACTIF)
                    ->orderBy('l.prefix', 'ASC');
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
