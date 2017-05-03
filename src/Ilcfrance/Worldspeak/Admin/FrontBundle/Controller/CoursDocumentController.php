<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
		$this->addTwigVar('menu_active', 'trainee');
	}

	/**
	 * Download TraineeFile From CoursDocument by id
	 *
	 * @param guid $id
	 *
	 * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function downloadAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__trainee_list');
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
}
