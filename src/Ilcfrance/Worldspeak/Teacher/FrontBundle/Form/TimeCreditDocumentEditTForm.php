<?php
namespace Ilcfrance\Worldspeak\Teacher\FrontBundle\Form;

use Ilcfrance\Worldspeak\Shared\DataBundle\Document\TeachingResource;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCreditDocument;
use Ilcfrance\Worldspeak\Shared\DataBundle\Repository\TeachingResourceRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * TimeCreditDocument Edit Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TimeCreditDocumentEditTForm extends AbstractType
{

	/**
	 *
	 * @var integer
	 */
	private $level;

	/**
	 * Constructor
	 *
	 * @param integer $level
	 */
	public function __construct($level = null)
	{
		$this->level = $level;
	}

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$this->level = $options['level'];
		$lvl = $this->level;

		$builder->add('teachingResource', DocumentType::class, array(
			'label' => 'TimeCreditDocument.teachingResource.label',
			'class' => 'IlcfranceWorldspeakSharedDataBundle:TeachingResource',
			'query_builder' => function (TeachingResourceRepository $tr) use ($lvl) {
				if (null != $lvl) {
					return $tr->createQueryBuilder()->field('level')->equals($lvl)->sort('type', 'ASC')->sort('filename', 'DESC');
				} else {
					return $tr->createQueryBuilder()->sort('level', 'ASC')->sort('type', 'ASC')->sort('filename', 'DESC');
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

		$builder->add('submit', SubmitType::class, array(
			'label' => '_action.btnEdit'
		));
	}

	/**
	 * (non-PHPdoc) @see \Symfony\Component\Form\FormTypeInterface::getName()
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'TimeCreditDocumentEditForm';
	}

	/**
	 *
	 * {@inheritdoc} @see AbstractType::getBlockPrefix()
	 */
	public function getBlockPrefix()
	{
		return $this->getName();
	}

	/**
	 * get the default options
	 *
	 * @return multitype:string multitype:string
	 */
	public function getDefaultOptions()
	{
		return array(
			'level' => null
		);
	}

	/**
	 *
	 * {@inheritdoc} @see AbstractType::configureOptions()
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults($this->getDefaultOptions());
	}
}
