<?php
namespace Ilcfrance\Worldspeak\Trainee\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TimeCreditDocumentController extends BaseController
{

    /**
     * Class Constructor
     */
    public function __construct()
    {
        $this->addTwigVar('menu_active', '');
    }

    /**
     *
     * @param Request $request
     * @param string $id
     *
     * @return Response|RedirectResponse
     */
    public function downloadAction(Request $request, $id)
    {
        $urlFrom = $this->getReferer($request);
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('Trainee__default_homepage');
        }
        $em = $this->getEntityManager();

        try {
            $timeCreditDocument = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCreditDocument')->findOneBy(array(
                'id' => $id
            ));

            if (null != $timeCreditDocument) {
                if (null != $timeCreditDocument->getTeachingResource()) {

                    $response = new Response();
                    $response->headers->set('Cache-Control', 'private');
                    $response->headers->set('Content-type', $timeCreditDocument->getTeachingResource()
                        ->getMimeType());
                    $response->headers->set('Content-Disposition', 'attachment; filename="' . $timeCreditDocument->getTeachingResource()
                        ->getFilename() . '"');
                    $response->headers->set('Content-length', $timeCreditDocument->getTeachingResource()
                        ->getLength());
                    // Send headers before outputting anything
                    $response->sendHeaders();
                    $response->setContent($timeCreditDocument->getTeachingResource()
                        ->getFile()
                        ->getBytes());
                    return $response;
                } else {
                    $this->addFlash('warning', 'TimeCreditDocument.downloadNotFile');
                }
            } else {
                $this->addFlash('warning', 'TimeCreditDocument.downloadNotfound');
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addError($e->getLine() . ' ' . $e->getMessage() . ' ' . $id);
            $this->addFlash('error', 'TimeCreditDocument.downloadError');
        }

        return $this->redirect($urlFrom);
    }
}
