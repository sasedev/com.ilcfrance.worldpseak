<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\LocaleAddTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\LocaleEditTForm;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Locale;
use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Locale Controller
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class LocaleController extends BaseController
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->addTwigVar('menu_active', 'locale');
    }

    /**
     * Get Locale with pagination 25/page
     *
     * @param Request $request
     * @param integer $page
     *
     * @return Response
     */
    public function listAction(Request $request, $page = 1)
    {
        $sc = $this->getSecurityAuthorizationChecker();
        $em = $this->getEntityManager();
        $query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Locale')->getAllquery();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 25);
        $pagination->setPageRange(10);

        if ($sc->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
            $locale = new Locale();
            $localeAddForm = $this->createForm(LocaleAddTForm::class, $locale);
            $this->addTwigVar('localeAddForm', $localeAddForm->createView());
        }

        $this->addTwigVar('locales', $pagination);
        $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__locale_list'));
        $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__locale_list'));
        $this->addTwigVar('smenu_active', 'locale.list');

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:Locale:list.html.twig', $this->getTwigVars());
    }

    /**
     * Add new Locale (method GET)
     * @Security("has_role('ROLE_SUPER_SUPER_ADMIN')")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addAction(Request $request)
    {
        $locale = new Locale();
        $localeAddForm = $this->createForm(LocaleAddTForm::class, $locale);

        $this->addTwigVar('localeAddForm', $localeAddForm->createView());
        $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__locale_add'));
        $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__locale_add'));
        $this->addTwigVar('smenu_active', 'locale.add');

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:Locale:add.html.twig', $this->getTwigVars());
    }

    /**
     * Add new Locale (method POST)
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function addPostAction(Request $request)
    {
        $urlFrom = $this->getReferer($request);
        if (null == $urlFrom || trim($urlFrom) == '') {
            return $this->redirect($this->generateUrl('Admin__locale_add_get'));
        }
        $locale = new Locale();
        $localeAddForm = $this->createForm(LocaleAddTForm::class, $locale);
        $data = $request->request->all();
        if (isset($data['LocaleAddForm'])) {
            $localeAddForm->handleRequest($request);

            if ($localeAddForm->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($locale);
                $em->flush();
                $this->addFlash('success', $this->translate('Locale.addSuccess', array(
                    '%locale%' => $locale->getName()
                )));

                return $this->redirect($this->generateUrl('Admin__locale_edit_get', array(
                    'id' => $locale->getId()
                )));
            } else {
                $this->addTwigVar('localeAddForm', $localeAddForm->createView());
                $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__locale_add'));
                $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__locale_add'));
                $this->addTwigVar('smenu_active', 'locale.add');

                return $this->render('IlcfranceWorldspeakAdminFrontBundle:Locale:add.html.twig', $this->getTwigVars());
            }
        } else {
            return $this->redirect($urlFrom);
        }
    }

    /**
     * Edit Locale (method GET)
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
            $urlFrom = $this->generateUrl('Admin__locale_list');
        }
        $sc = $this->getSecurityAuthorizationChecker();
        $em = $this->getEntityManager();
        try {
            $locale = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Locale')->findOneBy(array(
                'id' => $id
            ));

            if (null == $locale) {
                $this->addFlash('warning', 'Locale.editNotfound');

                return $this->redirect($urlFrom);
            }

            if ($sc->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
                $localeEditForm = $this->createForm(LocaleEditTForm::class, $locale);
                $this->addTwigVar('localeEditForm', $localeEditForm->createView());
            }

            $this->addTwigVar('locale', $locale);
            $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__locale_edit_txt', array(
                '%locale%' => $locale->getName()
            )));

            $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__locale_edit', array(
                '%locale%' => $locale->getName()
            )));

            return $this->render('IlcfranceWorldspeakAdminFrontBundle:Locale:edit.html.twig', $this->getTwigVars());
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addError($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTrace());
        }

        return $this->redirect($urlFrom);
    }

    /**
     * Edit Locale (method POST)
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
            return $this->redirect($this->generateUrl('Admin__locale_edit_get', array(
                'id' => $id
            )));
        }
        $em = $this->getEntityManager();
        try {
            $locale = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Locale')->findOneBy(array(
                'id' => $id
            ));

            if (null == $locale) {
                $this->addFlash('warning', 'Locale.editNotfound');

                return $this->redirect($urlFrom);
            }
            $localeEditForm = $this->createForm(LocaleEditTForm::class, $locale);
            $data = $request->request->all();
            if (isset($data['LocaleEditForm'])) {
                $localeEditForm->handleRequest($request);

                if ($localeEditForm->isValid()) {
                    $em->persist($locale);
                    $em->flush();
                    $this->addFlash('success', $this->translate('Locale.editSuccess', array(
                        '%locale%' => $locale->getName()
                    )));

                    return $this->redirect($this->generateUrl('Admin__locale_edit_get', array(
                        'id' => $locale->getId()
                    )));
                } else {
                    $this->addTwigVar('tabActive', 2);
                    $em->refresh($locale);

                    $this->addTwigVar('locale', $locale);
                    $this->addTwigVar('localeEditForm', $localeEditForm->createView());
                    $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__locale_edit_txt', array(
                        '%locale%' => $locale->getName()
                    )));

                    $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__locale_edit', array(
                        '%locale%' => $locale->getName()
                    )));

                    return $this->render('IlcfranceWorldspeakAdminFrontBundle:Locale:edit.html.twig', $this->getTwigVars());
                }
            } else {
                return $this->redirect($urlFrom);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addError($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTrace());

            $this->addFlash('error', 'Locale.editError');
        }

        return $this->redirect($urlFrom);
    }

    /**
     * Delete Locale
     * @Security("has_role('ROLE_SUPER_SUPER_ADMIN')")
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
            return $this->redirect($this->generateUrl('Admin__locale_list'));
        }
        $em = $this->getEntityManager();
        try {
            $locale = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Locale')->findOneBy(array(
                'id' => $id
            ));

            if (null != $locale) {
                foreach ($locale->getAdmins() as $admin) {
                    $admin->setPreferedLocale(null);
                    $em->persist($admin);
                }
                foreach ($locale->getTeachers() as $teacher) {
                    $teacher->setPreferedLocale(null);
                    $em->persist($teacher);
                }
                foreach ($locale->getTrainees() as $trainee) {
                    $trainee->setPreferedLocale(null);
                    $em->persist($trainee);
                }
                $em->remove($locale);
                $em->flush();

                $this->addFlash('success', $this->translate('Locale.deleteSuccess', array(
                    '%locale%' => $locale->getName()
                )));
            } else {
                $this->addFlash('warning', 'Locale.deleteNotfound');
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addError($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTrace());

            $this->addFlash('error', 'Locale.deleteError');
        }

        return $this->redirect($urlFrom);
    }
}
