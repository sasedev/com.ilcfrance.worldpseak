<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Excel Import Form
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class ExcelImportTForm extends AbstractType
{

	/**
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('file', FileType::class, array(
			'label' => 'excel.file',
			'required' => true,
			'constraints' => array(
				new File(array(
					'mimeTypes' => array(
						'application/vnd.ms-excel',
						'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
						'application/zip'
					),
					'maxSize' => "4096k",
					'maxSizeMessage' => 'import.excel.maxsize'
				))
			)
		));

		$builder->add('submit', SubmitType::class, array(
			'label' => '_action.btnUpload'
		));
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see \Symfony\Component\Form\FormTypeInterface::getName()
	 */
	public function getName()
	{
		return 'ExcelImportForm';
	}

	/**
	 *
	 * {@inheritdoc} @see AbstractType::getBlockPrefix()
	 */
	public function getBlockPrefix()
	{
		return $this->getName();
	}
}
