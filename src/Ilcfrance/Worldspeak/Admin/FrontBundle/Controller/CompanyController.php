<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\CompanyAddTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\CompanyEditTForm;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Company;
use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Company Controller
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class CompanyController extends BaseController
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->addTwigVar('menu_active', 'company');
    }

    /**
     * Get Company with pagination 10/page
     *
     * @param Request $request
     * @param integer $page
     *
     * @return Response
     */
    public function listAction(Request $request, $page = 1)
    {
        $em = $this->getEntityManager();
        $query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Company')->getAllQuery();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 10);
        $pagination->setPageRange(10);

        $company = new Company();
        $companyAddForm = $this->createForm(CompanyAddTForm::class, $company);
        $this->addTwigVar('companyAddForm', $companyAddForm->createView());

        $this->addTwigVar('companies', $pagination);
        $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__company_list'));
        $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__company_list'));
        $this->addTwigVar('smenu_active', 'company.list');

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:Company:list.html.twig', $this->getTwigVars());
    }

    /**
     * Search Company with pagination 10/page
     *
     * @param Request $request
     * @param integer $page
     *
     * @return RedirectResponse|Response
     */
    public function searchAction(Request $request, $page = 1)
    {
        $q = $request->get('q');
        if (null == $q || trim($q) == "") {
            return $this->redirect($this->generateUrl("Admin__company_list"));
        }
        $q = trim($q);
        $em = $this->getEntityManager();
        $count = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Company')->countSearch($q);
        $query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Company')->searchQuery($q);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 10);
        $pagination->setPageRange(10);

        $company = new Company();
        $companyAddForm = $this->createForm(CompanyAddTForm::class, $company);
        $this->addTwigVar('companyAddForm', $companyAddForm->createView());

        $this->addTwigVar('companies', $pagination);
        $this->addTwigVar('countQ', $count);
        $this->addTwigVar('q', $q);
        $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__company_search_txt', array(
            '%q%' => $q
        )));

        $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__company_search', array(
            '%q%' => $q
        )));

        $this->addTwigVar('smenu_active', 'company.list');

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:Company:search.html.twig', $this->getTwigVars());
    }

    /**
     * Add new Company (method GET)
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addAction(Request $request)
    {
        $company = new Company();
        $companyAddForm = $this->createForm(CompanyAddTForm::class, $company);

        $this->addTwigVar('companyAddForm', $companyAddForm->createView());
        $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__company_add'));
        $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__company_add'));
        $this->addTwigVar('smenu_active', 'company.add');

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:Company:add.html.twig', $this->getTwigVars());
    }

    /**
     * Add new Company (method POST)
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function addPostAction(Request $request)
    {
        $urlFrom = $this->getReferer($request);
        if (null == $urlFrom || trim($urlFrom) == '') {
            return $this->redirect($this->generateUrl('Admin__company_add_get'));
        }

        $company = new Company();
        $companyAddForm = $this->createForm(CompanyAddTForm::class, $company);

        $data = $request->request->all();
        if (isset($data['CompanyAddForm'])) {
            $companyAddForm->handleRequest($request);

            if ($companyAddForm->isValid()) {
                $em = $this->getEntityManager();

                $em->persist($company);
                $em->flush();
                $this->addFlash('success', $this->translate('Company.addSuccess', array(
                    '%company%' => $company->getName()
                )));

                return $this->redirect($this->generateUrl('Admin__company_edit_get', array(
                    'id' => $company->getId()
                )));
            } else {
                $this->addTwigVar('companyAddForm', $companyAddForm->createView());
                $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__company_add'));
                $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__company_add'));
                $this->addTwigVar('smenu_active', 'company.add');

                return $this->render('IlcfranceWorldspeakAdminFrontBundle:Company:add.html.twig', $this->getTwigVars());
            }
        } else {
            return $this->redirect($urlFrom);
        }
    }

    /**
     * Edit Company (method GET)
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
            $urlFrom = $this->generateUrl('Admin__company_list');
        }
        $em = $this->getEntityManager();
        try {
            $company = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Company')->findOneBy(array(
                'id' => $id
            ));

            if (null == $company) {
                $this->addFlash('warning', 'Company.editNotfound');

                return $this->redirect($urlFrom);
            }

            $companyEditForm = $this->createForm(CompanyEditTForm::class, $company);
            $this->addTwigVar('companyEditForm', $companyEditForm->createView());

            $this->addTwigVar('company', $company);
            $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__company_edit_txt', array(
                '%company%' => $company->getName()
            )));

            $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__company_edit', array(
                '%company%' => $company->getName()
            )));

            return $this->render('IlcfranceWorldspeakAdminFrontBundle:Company:edit.html.twig', $this->getTwigVars());
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addError($e->getLine() . ' ' . $e->getMessage());
        }

        return $this->redirect($urlFrom);
    }

    /**
     * Edit Company (method POST)
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
            $urlFrom = $this->generateUrl('Admin__company_list');
        }
        $em = $this->getEntityManager();
        try {
            $company = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Company')->findOneBy(array(
                'id' => $id
            ));

            if (null == $company) {
                $this->addFlash('warning', 'Company.editNotfound');

                return $this->redirect($urlFrom);
            }

            $companyEditForm = $this->createForm(CompanyEditTForm::class, $company);

            $data = $request->request->all();
            if (isset($data['CompanyEditForm'])) {
                $companyEditForm->handleRequest($request);
                if ($companyEditForm->isValid()) {
                    $em->persist($company);
                    $em->flush();

                    $this->addFlash('success', $this->translate('Company.editSuccess', array(
                        '%company%' => $company->getName()
                    )));

                    return $this->redirect($urlFrom);
                } else {
                    $em->refresh($company);

                    $this->addFlash('error', $this->translate('Company.editError', array(
                        '%company%' => $company->getName()
                    )));
                }
            }

            $this->addTwigVar('companyEditForm', $companyEditForm->createView());

            $this->addTwigVar('tabActive', 2);
            $this->addTwigVar('company', $company);
            $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__company_edit_txt', array(
                '%company%' => $company->getName()
            )));

            $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__company_edit', array(
                '%company%' => $company->getName()
            )));

            return $this->render('IlcfranceWorldspeakAdminFrontBundle:Company:edit.html.twig', $this->getTwigVars());
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addError($e->getLine() . ' ' . $e->getMessage());
        }

        return $this->redirect($urlFrom);
    }

    /**
     * Delete Company
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
            return $this->redirect($this->generateUrl('Admin__company_list'));
        }
        $em = $this->getEntityManager();

        try {
            $company = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Company')->findOneBy(array(
                'id' => $id
            ));

            if (null != $company) {
                $em->remove($company);
                $em->flush();

                $this->addFlash('success', $this->translate('Company.deleteSuccess', array(
                    '%company%' => $company->getName()
                )));
            } else {
                $this->addFlash('warning', 'Company.deleteNotfound');
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addError($e->getLine() . ' ' . $e->getMessage());
            $this->addFlash('error', 'Company.deleteError');
        }

        return $this->redirect($urlFrom);
    }
}
