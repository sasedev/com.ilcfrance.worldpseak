<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Add new Company Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class CompanyAddTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'CompanyAddForm';
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
        $builder->add('name', TextType::class, array(
            'label' => 'Company.name.label'
        ));

        $builder->add('prefix', TextType::class, array(
            'label' => 'Company.prefix.label'
        ));

        $builder->add('service', TextType::class, array(
            'label' => 'Company.service.label',
            'required' => false
        ));

        $builder->add('address', TextareaType::class, array(
            'label' => 'Company.address.label',
            'required' => false
        ));

        $builder->add('town', TextType::class, array(
            'label' => 'Company.town.label',
            'required' => false
        ));

        $builder->add('postalCode', TextType::class, array(
            'label' => 'Company.postalCode.label',
            'required' => false
        ));

        $builder->add('country', CountryType::class, array(
            'label' => 'Company.country.label',
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
