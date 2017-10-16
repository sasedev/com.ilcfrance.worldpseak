<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Teacher;
use Ilcfrance\Worldspeak\Shared\DataBundle\Repository\TeacherRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Cours Teacher Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class CoursTeacherTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'CoursTeacherForm';
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
                'teacher',
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
        $builder->add('teacher', EntityType::class, array(
            'label' => 'Cours.teacher.label',
            'class' => 'IlcfranceWorldspeakSharedDataBundle:Teacher',
            'query_builder' => function (TeacherRepository $tr) {
                return $tr->createQueryBuilder('t')
                    ->where('t.lockout = :lock')
                    ->orderBy('t.username', 'ASC')
                    ->setParameter('lock', Teacher::LOCKOUT_UNLOCKED);
            },
            'choice_label' => 'fullname',
            'by_reference' => true
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
