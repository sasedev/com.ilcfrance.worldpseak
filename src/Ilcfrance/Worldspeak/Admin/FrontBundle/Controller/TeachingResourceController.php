<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TeachingResourceAddTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TeachingResourceEditTForm;
use Ilcfrance\Worldspeak\Shared\DataBundle\Document\TeachingResource;
use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * TeachingResource Controller
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TeachingResourceController extends BaseController
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->addTwigVar('menu_active', 'teachingResource');
    }

    /**
     * Get TeachingResource with pagination 10/page
     *
     * @param Request $request
     * @param integer $page
     *
     * @return Response
     */
    public function listAction(Request $request, $page = 1)
    {
        $dm = $this->getMongoManager();
        $query = $dm->getRepository('IlcfranceWorldspeakSharedDataBundle:TeachingResource')->getAllQuery();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 10);
        $pagination->setPageRange(10);

        $teachingResource = new TeachingResource();
        $teachingResourceAddForm = $this->createForm(TeachingResourceAddTForm::class, $teachingResource);
        $this->addTwigVar('teachingResourceAddForm', $teachingResourceAddForm->createView());

        $this->addTwigVar('teachingResources', $pagination);
        $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__teachingResource_list'));
        $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__teachingResource_list'));
        $this->addTwigVar('smenu_active', 'teachingResource.list');

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:TeachingResource:list.html.twig', $this->getTwigVars());
    }

    /*
     * public function repaireAction(Request $request)
     * {
     * ini_set('memory_limit', '-1');
     * ini_set('max_execution_time', '0');
     * $kernel_root = $this->get('kernel')->getRootDir();
     * $dm = $this->getMongoManager();
     * $file_array = array();
     * $file_names = $kernel_root . '/../nosql/worldspeak/teaching_resources.files.json';
     * $file_names_handle = \fopen($file_names, "r");
     * if ($file_names_handle) {
     * while (($line = \fgets($file_names_handle)) !== false) {
     * $json_data = \json_decode($line, true);
     * // \print_r($json_data);
     * $file_array[$json_data['_id']] = array(
     * 'filename' => $json_data['filename'],
     * 'content' => '',
     * 'dtCrea' => new DateTime($json_data['dtCrea']['$date'])
     * );
     * }
     * \fclose($file_names_handle);
     * } else {
     * // error opening the file.
     * $this->addFlash('error', "error opening the file " . $file_names);
     * }
     * // \print_r($file_array);
     * $file_contents = $kernel_root . '/../nosql/worldspeak/teaching_resources.chunks.json';
     * $file_contents_handle = \fopen($file_contents, "r");
     * if ($file_contents_handle) {
     * while (($line = \fgets($file_contents_handle)) !== false) {
     * $json_data = \json_decode($line, true);
     * // \print_r($json_data);
     * $file_array[$json_data['files_id']]['content'] .= \base64_decode($json_data['data']['$binary']);
     * }
     * \fclose($file_contents_handle);
     * } else {
     * // error opening the file.
     * $this->addFlash('error', "error opening the file " . $file_contents);
     * }
     * // \print_r($file_array);
     * foreach ($file_array as $key => $file) {
     * if (! \file_exists($kernel_root . "/../nosql/worldspeak/output/" . $key)) {
     * \mkdir($kernel_root . "/../nosql/worldspeak/output/" . $key);
     * }
     * $filename = $kernel_root . "/../nosql/worldspeak/output/" . $key . "/" . $file['filename'];
     * $dtCrea = $file['dtCrea'];
     * $handle = \fopen($filename, "w+");
     * echo $filename . "\n";
     * if ($handle) {
     * \fwrite($handle, $file['content']);
     * \fclose($handle);
     * \touch($filename, $dtCrea->getTimestamp());
     * $teachingResource = $dm->getRepository('IlcfranceWorldspeakSharedDataBundle:TeachingResource')->find($key);
     * if (null == $teachingResource) {
     * $this->addFlash('error', "unknown teaching resource " . $key);
     * } else {
     * $teachingResource->setFile($filename);
     * $teachingResource->setFilename($file['filename']);
     * $dm->persist($teachingResource);
     * $dm->flush();
     * $this->addFlash('success', $this->translate('TeachingResource.addSuccess', array(
     * '%teachingResource%' => $teachingResource->getFilename()
     * )));
     * }
     * } else {
     * $this->addFlash('error', "error opening the file " . $filename);
     * }
     * }
     * $urlFrom = $this->generateUrl('Admin__teachingResource_list');
     * return $this->redirect($urlFrom);
     * } //
     */

    /**
     * Search TeachingResource with pagination 10/page
     *
     * @param Request $request
     * @param integer $page
     *
     * @return RedirectResponse|Response
     */
    public function searchAction(Request $request, $page = 1)
    {
        ;
        $q = $request->get('q');
        if (null == $q || trim($q) == "") {
            return $this->redirect($this->generateUrl("Admin__teachingResource_list"));
        }
        $q = trim($q);
        $dm = $this->getMongoManager();
        $count = $dm->getRepository('IlcfranceWorldspeakSharedDataBundle:TeachingResource')->countSearch($q);
        $query = $dm->getRepository('IlcfranceWorldspeakSharedDataBundle:TeachingResource')->searchQuery($q);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 10);
        $pagination->setPageRange(10);

        $teachingResource = new TeachingResource();
        $teachingResourceAddForm = $this->createForm(TeachingResourceAddTForm::class, $teachingResource);
        $this->addTwigVar('teachingResourceAddForm', $teachingResourceAddForm->createView());

        $this->addTwigVar('teachingResources', $pagination);
        $this->addTwigVar('countQ', $count);
        $this->addTwigVar('q', $q);
        $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__teachingResource_search_txt', array(
            '%q%' => $q
        )));

        $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__teachingResource_search', array(
            '%q%' => $q
        )));

        $this->addTwigVar('smenu_active', 'teachingResource.list');

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:TeachingResource:search.html.twig', $this->getTwigVars());
    }

    /**
     * Add new TeachingResource (method GET)
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addAction(Request $request)
    {
        $teachingResourceAddForm = $this->createForm(TeachingResourceAddTForm::class);

        $this->addTwigVar('teachingResourceAddForm', $teachingResourceAddForm->createView());
        $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__teachingResource_add'));
        $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__teachingResource_add'));
        $this->addTwigVar('smenu_active', 'teachingResource.add');

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:TeachingResource:add.html.twig', $this->getTwigVars());
    }

    /**
     * Add new TeachingResource (method POST)
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function addPostAction(Request $request)
    {
        $urlFrom = $this->getReferer($request);
        if (null == $urlFrom || trim($urlFrom) == '') {
            return $this->redirect($this->generateUrl('Admin__teachingResource_add_get'));
        }

        $teachingResourceAddForm = $this->createForm(TeachingResourceAddTForm::class);

        ;
        $data = $request->request->all();
        if (isset($data['TeachingResourceAddForm'])) {
            $teachingResourceAddForm->handleRequest($request);

            if ($teachingResourceAddForm->isValid()) {
                $formData = $teachingResourceAddForm->getData();
                $upload = $formData['file'];

                $teachingResource = new TeachingResource();

                $teachingResource->setFile($upload->getPathname());
                $teachingResource->setFilename($upload->getClientOriginalName());
                $teachingResource->setMimeType($upload->getClientMimeType());
                $teachingResource->setLevel($formData['level']);
                $teachingResource->setType($formData['type']);

                $dm = $this->getMongoManager();

                $dm->persist($teachingResource);
                $dm->flush();
                $this->addFlash('success', $this->translate('TeachingResource.addSuccess', array(
                    '%teachingResource%' => $teachingResource->getFilename()
                )));

                return $this->redirect($this->generateUrl('Admin__teachingResource_edit_get', array(
                    'id' => $teachingResource->getId()
                )));
            } else {
                $this->addTwigVar('teachingResourceAddForm', $teachingResourceAddForm->createView());
                $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__teachingResource_add'));
                $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__teachingResource_add'));
                $this->addTwigVar('smenu_active', 'teachingResource.add');

                return $this->render('IlcfranceWorldspeakAdminFrontBundle:TeachingResource:add.html.twig', $this->getTwigVars());
            }
        } else {
            return $this->redirect($urlFrom);
        }
    }

    /**
     * Download TeachingResource
     *
     * @param Request $request
     * @param string $id
     *
     * @return RedirectResponse|Response
     */
    public function downloadAction(Request $request, $id)
    {
        $urlFrom = $this->getReferer($request);
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('Admin__teachingResource_list');
        }
        $dm = $this->getMongoManager();
        try {
            $teachingResource = $dm->getRepository('IlcfranceWorldspeakSharedDataBundle:TeachingResource')->findOneBy(array(
                'id' => $id
            ));

            if (null == $teachingResource) {
                $this->addFlash('warning', 'TeachingResource.downloadNotfound');

                return $this->redirect($urlFrom);
            }

            $response = new Response();
            $response->headers->set('Cache-Control', 'private');
            $response->headers->set('Content-type', $teachingResource->getMimeType());
            $response->headers->set('Content-Disposition', 'attachment; filename="' . $teachingResource->getFilename() . '"');

            $response->headers->set('Content-length', $teachingResource->getLength());
            // Send headers before outputting anything
            $response->sendHeaders();

            $response->setContent($teachingResource->getFile()
                ->getBytes());

            return $response;
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addError($e->getLine() . ' ' . $e->getMessage());

            $this->addFlash('warning', 'TeachingResource.downloadNotfound');

            return $this->redirect($urlFrom);
        }
    }

    /**
     * Edit TeachingResource (method GET)
     *
     * @param Request $request
     * @param string $id
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, $id)
    {
        $urlFrom = $this->getReferer($request);
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('Admin__teachingResource_list');
        }
        $dm = $this->getMongoManager();
        try {
            $teachingResource = $dm->getRepository('IlcfranceWorldspeakSharedDataBundle:TeachingResource')->findOneBy(array(
                'id' => $id
            ));

            if (null == $teachingResource) {
                $this->addFlash('warning', 'TeachingResource.editNotfound');

                return $this->redirect($urlFrom);
            }

            $teachingResourceEditForm = $this->createForm(TeachingResourceEditTForm::class, $teachingResource);
            $this->addTwigVar('teachingResourceEditForm', $teachingResourceEditForm->createView());

            $this->addTwigVar('teachingResource', $teachingResource);
            $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__teachingResource_edit_txt', array(
                '%teachingResource%' => $teachingResource->getFilename()
            )));

            $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__teachingResource_edit', array(
                '%teachingResource%' => $teachingResource->getFilename()
            )));

            return $this->render('IlcfranceWorldspeakAdminFrontBundle:TeachingResource:edit.html.twig', $this->getTwigVars());
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addError($e->getLine() . ' ' . $e->getMessage());
        }

        return $this->redirect($urlFrom);
    }

    /**
     * Edit TeachingResource (method POST)
     *
     * @param Request $request
     * @param string $id
     *
     * @return RedirectResponse|Response
     */
    public function editPostAction(Request $request, $id)
    {
        $urlFrom = $this->getReferer($request);
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('Admin__teachingResource_list');
        }
        $dm = $this->getMongoManager();
        try {
            $teachingResource = $dm->getRepository('IlcfranceWorldspeakSharedDataBundle:TeachingResource')->findOneBy(array(
                'id' => $id
            ));

            if (null == $teachingResource) {
                $this->addFlash('warning', 'TeachingResource.editNotfound');

                return $this->redirect($urlFrom);
            }

            $teachingResourceEditForm = $this->createForm(TeachingResourceEditTForm::class, $teachingResource);

            ;
            $data = $request->request->all();
            if (isset($data['TeachingResourceEditForm'])) {
                $teachingResourceEditForm->handleRequest($request);
                if ($teachingResourceEditForm->isValid()) {
                    $dm->persist($teachingResource);
                    $dm->flush();

                    $this->addFlash('success', $this->translate('TeachingResource.editSuccess', array(
                        '%teachingResource%' => $teachingResource->getFilename()
                    )));

                    return $this->redirect($urlFrom);
                } else {
                    $dm->refresh($teachingResource);

                    $this->addFlash('error', $this->translate('TeachingResource.editError', array(
                        '%teachingResource%' => $teachingResource->getFilename()
                    )));
                }
            }

            $this->addTwigVar('teachingResourceEditForm', $teachingResourceEditForm->createView());

            $this->addTwigVar('tabActive', 2);
            $this->addTwigVar('teachingResource', $teachingResource);
            $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__teachingResource_edit_txt', array(
                '%teachingResource%' => $teachingResource->getFilename()
            )));

            $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__teachingResource_edit', array(
                '%teachingResource%' => $teachingResource->getFilename()
            )));

            return $this->render('IlcfranceWorldspeakAdminFrontBundle:TeachingResource:edit.html.twig', $this->getTwigVars());
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addError($e->getLine() . ' ' . $e->getMessage());
        }

        return $this->redirect($urlFrom);
    }

    /**
     * Delete TeachingResource
     *
     * @param Request $request
     * @param string $id
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $urlFrom = $this->getReferer($request);
        if (null == $urlFrom || trim($urlFrom) == '') {
            return $this->redirect($this->generateUrl('Admin__teachingResource_list'));
        }
        $dm = $this->getMongoManager();

        try {
            $teachingResource = $dm->getRepository('IlcfranceWorldspeakSharedDataBundle:TeachingResource')->findOneBy(array(
                'id' => $id
            ));

            if (null != $teachingResource) {
                $em = $this->getEntityManager();
                $tcds = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCreditDocument')->findBy(array(
                    'teachingResource' => $teachingResource
                ));

                foreach ($tcds as $tcd) {
                    $em->remove($tcd);
                }
                $em->flush();
                $dm->remove($teachingResource);
                $dm->flush();

                $this->addFlash('success', $this->translate('TeachingResource.deleteSuccess', array(
                    '%teachingResource%' => $teachingResource->getFilename()
                )));
            } else {
                $this->addFlash('warning', 'TeachingResource.deleteNotfound');
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addError($e->getLine() . ' ' . $e->getMessage());
            $this->addFlash('error', 'TeachingResource.deleteError');
        }

        return $this->redirect($urlFrom);
    }
}
