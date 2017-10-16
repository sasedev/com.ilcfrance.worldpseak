<?php
namespace Ilcfrance\Worldspeak\Teacher\FrontBundle\Controller;

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
	 * Download TraineeFile From CoursDocument by id
	 *
	 * @param string $id
	 *
	 * @return RedirectResponse|Response
	 */
	public function downloadAction(Request $request, $id)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Teacher__default_homepage');
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
