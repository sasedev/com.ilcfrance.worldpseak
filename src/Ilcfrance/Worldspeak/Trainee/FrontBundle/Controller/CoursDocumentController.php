<?php
namespace Ilcfrance\Worldspeak\Trainee\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Shared\DataBundle\Document\TraineeFile;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Cours;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\CoursDocument;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TraineeLog;
use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Ilcfrance\Worldspeak\Trainee\FrontBundle\Form\CoursDocumentAddTForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CoursDocument Controller
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class CoursDocumentController extends BaseController
{

	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		$this->addTwigVar('menu_active', '');
	}

	public function addPostAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Trainee__default_homepage');
		}
		if ($this->endswith($urlFrom, $this->generateUrl('Trainee__coursDocument_add_post', array(
			'id' => $id
		)))) {
			$urlFrom = $this->generateUrl('Trainee__cours_edit_get', array(
				'id' => $id
			));
		}

		$em = $this->getEntityManager();
		$currentUser = $this->getSecurityTokenStorage()->getToken()->getUser();

		try {
			$cours = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->findOneBy(array(
				'id' => $id
			));

			if (null == $cours) {
				return $this->redirect($urlFrom);
			}

			$traineeFile = new TraineeFile();
			$coursDocumentAddForm = $this->createForm(CoursDocumentAddTForm::class, $traineeFile);

			;
			$data = $request->request->all();
			if (isset($data['CoursDocumentAddForm'])) {
				$coursDocumentAddForm->handleRequest($request);
				if ($coursDocumentAddForm->isValid()) {
					$coursDocument = new CoursDocument();
					$coursDocument->setCours($cours);

					$upload = $coursDocumentAddForm['file']->getData();
					if (null != $upload) {
						$dm = $this->getMongoManager();

						$traineeFile->setFile($upload->getPathname());
						$traineeFile->setFilename($upload->getClientOriginalName());
						$traineeFile->setMimeType($upload->getClientMimeType());

						$dm->persist($traineeFile);
						$dm->flush();

						$coursDocument->setTraineeFile($traineeFile);
					}
					$coursDocument->setMsg($coursDocumentAddForm['msg']->getData());
					$em->persist($coursDocument);
					$em->flush();

					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($currentUser);
					$traineeLog->setMsg("Succes ajout de CoursDocument");
					$em->persist($traineeLog);
					$em->flush();

					$this->addFlash('success', 'CoursDocument.addSuccess');

					return $this->redirect($urlFrom);
				} else {
					$this->addFlash('error', 'CoursDocument.addError');

					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($currentUser);
					$traineeLog->setMsg("Echec ajout de CoursDocument Formulaire invalide");
					$em->persist($traineeLog);
					$em->flush();
				}
			}

			$this->addTwigVar('tabActive', 2);
			$this->addTwigVar('coursDocumentAddForm', $coursDocumentAddForm->createView());

			$dateFormatter = $this->get('ilc_france_worldspeak_shared_res.date_formatter');

			$this->addTwigVar('cours', $cours);

			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleTrainee__cours_edit_txt', array(
				'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
				'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
				'%duration%' => $cours->getDuration()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleTrainee__cours_edit', array(
				'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
				'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
				'%duration%' => $cours->getDuration()
			)));

			return $this->render('IlcfranceWorldspeakTraineeFrontBundle:Cours:edit.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());

			$traineeLog = new TraineeLog();
			$traineeLog->setTrainee($currentUser);
			$traineeLog->setMsg("Echec ajout de CoursDocument " . $e->getMessage());
			$em->persist($traineeLog);
			$em->flush();

			return $this->redirect($urlFrom);
		}
	}

	public function downloadAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Trainee__default_homepage');
		}
		$em = $this->getEntityManager();

		try {
			$coursDocument = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:CoursDocument')->findOneBy(array(
				'id' => $id
			));

			if (null != $coursDocument) {
				if (null != $coursDocument->getTraineeFile()) {
					$response = new Response();
					$response->headers->set('Cache-Control', 'private');
					$response->headers->set('Content-type', $coursDocument->getTraineeFile()->getMimeType());
					$response->headers->set('Content-Disposition', 'attachment; filename="' . $coursDocument->getTraineeFile()->getFilename() . '"');

					$response->headers->set('Content-length', $coursDocument->getTraineeFile()->getLength());
					// Send headers before outputting anything
					$response->sendHeaders();

					$response->setContent($coursDocument->getTraineeFile()->getFile()->getBytes());

					return $response;
				} else {
					$this->addFlash('warning', 'CoursDocument.downloadNotFile');
				}
			} else {
				$this->addFlash('warning', 'CoursDocument.downloadNotfound');
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
			$this->addFlash('error', 'CoursDocument.downloadError');
		}

		return $this->redirect($urlFrom);
	}

	public function deleteAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Trainee__default_homepage');
		}
		$em = $this->getEntityManager();
		$currentUser = $this->getSecurityTokenStorage()->getToken()->getUser();

		try {
			$coursDocument = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:CoursDocument')->findOneBy(array(
				'id' => $id
			));

			if (null != $coursDocument) {
				// $cours = $coursDocument->getCours();
				$traineeFile = $coursDocument->getTraineeFile();

				if (null != $traineeFile) {
					$dm = $this->getMongoManager();

					$dm->remove($traineeFile);
					$dm->flush();
				}

				$em->remove($coursDocument);
				$em->flush();

				$traineeLog = new TraineeLog();
				$traineeLog->setTrainee($currentUser);
				$traineeLog->setMsg("Success suppression de CoursDocument");
				$em->persist($traineeLog);
				$em->flush();

				$this->addFlash('success', $this->translate('CoursDocument.deleteSuccess'));
			} else {
				$traineeLog = new TraineeLog();
				$traineeLog->setTrainee($currentUser);
				$traineeLog->setMsg("Echec suppression de CoursDocument " . $this->translate('CoursDocument.deleteNotfound'));
				$em->persist($traineeLog);
				$em->flush();

				$this->addFlash('warning', 'CoursDocument.deleteNotfound');
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
			$this->addFlash('error', 'CoursDocument.deleteError');

			$traineeLog = new TraineeLog();
			$traineeLog->setTrainee($currentUser);
			$traineeLog->setMsg("Echec suppression de CoursDocument " . $e->getMessage());
			$em->persist($traineeLog);
			$em->flush();
		}

		return $this->redirect($urlFrom);
	}
}
