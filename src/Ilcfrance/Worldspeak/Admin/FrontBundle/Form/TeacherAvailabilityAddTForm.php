<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Teacher;
use Ilcfrance\Worldspeak\Shared\DataBundle\Repository\TeacherRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Add new TeacherAvailability Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TeacherAvailabilityAddTForm extends AbstractType
{

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'TeacherAvailabilityAddForm';
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
     * Build Form
     *
     * {@inheritdoc}
     * @see AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('teacher', EntityType::class, array(
            'label' => 'TeacherAvailability.teacher.label',
            'class' => 'IlcfranceWorldspeakSharedDataBundle:Teacher',
            'query_builder' => function (TeacherRepository $tr) {
                return $tr->createQueryBuilder('t')
                    ->where('t.lockout = :lock')
                    ->andWhere('t.type = :type')
                    ->orderBy('t.username', 'ASC')
                    ->setParameter('lock', Teacher::LOCKOUT_UNLOCKED)
                    ->setParameter('type', Teacher::TYPE_INTERNAL);
            },
            'choice_label' => 'fullname',
            'by_reference' => true
        ));

        $builder->add('dtStart', DateTimeType::class, array(
            'label' => 'TeacherAvailability.dtStart.label',
            'widget' => 'single_text',
            'date_format' => 'yyyy-MM-dd HH:ii'
        ));

        $builder->add('dtEnd', DateTimeType::class, array(
            'label' => 'TeacherAvailability.dtEnd.label',
            'widget' => 'single_text',
            'date_format' => 'yyyy-MM-dd HH:ii'
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
