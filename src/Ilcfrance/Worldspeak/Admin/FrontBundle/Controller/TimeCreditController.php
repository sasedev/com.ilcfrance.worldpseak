<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TimeCreditAddTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TimeCreditCefBeginTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TimeCreditCefEndTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TimeCreditDeadLineTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TimeCreditDetailsTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TimeCreditDocumentAddTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TimeCreditEndReportTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TimeCreditForcedKpiTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TimeCreditFtypeTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TimeCreditLevelTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TimeCreditLockoutTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TimeCreditShowReportTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TimeCreditTotalHoursTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TraineeCefTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TraineeCompanyTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TraineeEmailTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TraineeLockoutTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TraineePreferedLocaleTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TraineeProfileAdvancedTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TraineeProfileTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TraineeProjectTForm;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCredit;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCreditDocument;
use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Swift_Message;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * TimeCredit Controller
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TimeCreditController extends BaseController
{

    /**
     * Class Constructor
     */
    public function __construct()
    {
        $this->addTwigVar('menu_active', 'trainee');
    }

    /**
     * Get TimeCredit with pagination 10/page
     *
     * @param Request $request
     * @param integer $page
     *
     * @return Response
     */
    public function listAction(Request $request, $page = 1)
    {
        $em = $this->getEntityManager();
        $query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->getAllQuery();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 10);
        $pagination->setPageRange(10);

        $this->addTwigVar('timeCredits', $pagination);
        $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__timeCredit_list'));
        $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__timeCredit_list'));
        $this->addTwigVar('smenu_active', 'timeCredit.list');

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:TimeCredit:list.html.twig', $this->getTwigVars());
    }

    /**
     * Get TimeCredit Buggy with pagination 10/page
     *
     * @param Request $request
     * @param integer $page
     *
     * @return Response
     */
    public function listBuggyAction(Request $request, $page = 1)
    {
        $em = $this->getEntityManager();
        $query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->getAllBuggyQuery();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 10);
        $pagination->setPageRange(10);

        $this->addTwigVar('timeCredits', $pagination);
        $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__timeCredit_listBuggy'));
        $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__timeCredit_listBuggy'));
        $this->addTwigVar('smenu_active', 'timeCredit.listBuggy');

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:TimeCredit:listBuggy.html.twig', $this->getTwigVars());
    }

    /**
     * Search TimeCredit with pagination 10/page
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
            return $this->redirect($this->generateUrl("Admin__timeCredit_list"));
        }
        $q = trim($q);
        $em = $this->getEntityManager();
        $count = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->countSearch($q);
        $query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->searchQuery($q);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 10);
        $pagination->setPageRange(10);

        $this->addTwigVar('timeCredits', $pagination);
        $this->addTwigVar('countQ', $count);
        $this->addTwigVar('q', $q);
        $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__timeCredit_search_txt', array(
            '%q%' => $q
        )));
        $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__timeCredit_search', array(
            '%q%' => $q
        )));
        $this->addTwigVar('smenu_active', 'timeCredit.list');

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:TimeCredit:search.html.twig', $this->getTwigVars());
    }

    /**
     * Add new TimeCredit (method POST)
     *
     * @param Request $request
     * @param string $id
     *
     * @return RedirectResponse|Response
     */
    public function addPostAction(Request $request, $id)
    {
        $urlFrom = $this->getReferer($request);
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('Admin__trainee_list');
        }

        if ($this->endswith($urlFrom, $this->generateUrl('Admin__timeCredit_add_post', array(
            'id' => $id
        )))) {
            $urlFrom = $this->generateUrl('Admin__trainee_edit_get', array(
                'id' => $id
            ));
        }
        $em = $this->getEntityManager();
        try {
            $trainee = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->findOneBy(array(
                'id' => $id
            ));

            if (null == $trainee) {
                $this->addFlash('warning', 'Trainee.editNotfound');

                return $this->redirect($urlFrom);
            }

            $traineeCompanyForm = $this->createForm(TraineeCompanyTForm::class, $trainee);

            $traineeProjectForm = $this->createForm(TraineeProjectTForm::class, $trainee);

            $traineeEmailForm = $this->createForm(TraineeEmailTForm::class, $trainee);

            $traineeCefForm = $this->createForm(TraineeCefTForm::class, $trainee);

            $traineeLockoutForm = $this->createForm(TraineeLockoutTForm::class, $trainee);

            $traineePreferedLocaleForm = $this->createForm(TraineePreferedLocaleTForm::class, $trainee);

            $traineeProfileForm = $this->createForm(TraineeProfileTForm::class, $trainee);

            $traineeProfileAdvancedForm = $this->createForm(TraineeProfileAdvancedTForm::class, $trainee);

            $timeCredits = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->getAllByTrainee($trainee);

            $timeCredit = new TimeCredit();
            $timeCredit->setTrainee($trainee);
            if (null != $trainee->getCef()) {
                $timeCredit->setCefBegin($trainee->getCef());
            }

            $timeCreditAddForm = $this->createForm(TimeCreditAddTForm::class, $timeCredit);

            ;
            $data = $request->request->all();
            if (isset($data['TimeCreditAddForm'])) {

                $timeCreditAddForm->handleRequest($request);
                if ($timeCreditAddForm->isValid()) {
                    $canAddNewtimeCredit = true;
                    foreach ($timeCredits as $tc) {
                        if ($tc->getStatus() != TimeCredit::STATUS_FULL_FINISHED && $tc->getStatus() != TimeCredit::STATUS_DEADLINE_EXCEEDED) {
                            $canAddNewtimeCredit = false;
                        }
                    }
                    if ($canAddNewtimeCredit) {

                        $em->persist($timeCredit);
                        $em->flush();

                        if ($timeCredit->getNotifyByMail() == TimeCredit::NOTIFYBYMAIL_SENT && null != $trainee->getEmail() && trim($trainee->getEmail()) != '') {

                            $locale = null;
                            if (null != $trainee->getPreferedLocale()) {
                                $locale = $trainee->getPreferedLocale()->getPrefix();
                            }
                            $mvars = array();
                            $mvars['timeCredit'] = $timeCredit;
                            $mvars['userPreferedLocale'] = $locale;
                            $from = $this->getParameter('mail_from');
                            $fromName = $this->getParameter('mail_from_name');
                            $subject = $this->translate('_mail.new_timeCredit_subject', array(), null, $locale);

                            $message = Swift_Message::newInstance()->setFrom($from, $fromName)
                                ->setTo($trainee->getEmail(), $trainee->getFullname())
                                ->setSubject($subject)
                                ->setBody($this->renderView('IlcfranceWorldspeakSharedResBundle:Mail:trainee.new_timeCredit.html.twig', $mvars), 'text/html');

                            $this->sendmail($message);

                            $this->addFlash('success', $this->translate('TimeCredit.addSuccessWithMail', array(
                                '%totalHours%' => $timeCredit->getTotalHours(),
                                '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                                '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                                '%trainee%' => $timeCredit->getTrainee()
                                    ->getFullname()
                            )));
                        } else {
                            $this->addFlash('success', $this->translate('TimeCredit.addSuccess', array(
                                '%totalHours%' => $timeCredit->getTotalHours(),
                                '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                                '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                                '%trainee%' => $timeCredit->getTrainee()
                                    ->getFullname()
                            )));
                        }

                        return $this->redirect($urlFrom);
                    } else {
                        $this->addFlash('error', $this->translate('TimeCredit.addErrorPendings'));
                    }
                } else {
                    $this->addFlash('error', $this->translate('TimeCredit.addErrorInvalid'));
                }
            } else {
                $this->addFlash('error', $this->translate('TimeCredit.addErrorUnknown'));
            }

            $this->addTwigVar('timeCredits', $timeCredits);

            $this->addTwigVar('timeCredits', $timeCredits);

            if ($this->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
                $notifs = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TraineeNotif')->getAllByTrainee($trainee);
            } else {
                $notifs = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TraineeNotif')->getAllOldPendingTxtByTrainee($trainee);
            }

            $this->addTwigVar('notifs', $notifs);

            $this->addTwigVar('timeCreditAddForm', $timeCreditAddForm->createView());

            $this->addTwigVar('traineeCompanyForm', $traineeCompanyForm->createView());
            $this->addTwigVar('traineeProjectForm', $traineeProjectForm->createView());
            $this->addTwigVar('traineeEmailForm', $traineeEmailForm->createView());
            $this->addTwigVar('traineeCefForm', $traineeCefForm->createView());
            $this->addTwigVar('traineeLockoutForm', $traineeLockoutForm->createView());
            $this->addTwigVar('traineePreferedLocaleForm', $traineePreferedLocaleForm->createView());
            $this->addTwigVar('traineeProfileForm', $traineeProfileForm->createView());
            $this->addTwigVar('traineeProfileAdvancedForm', $traineeProfileAdvancedForm->createView());

            $this->addTwigVar('tabActive', 3);
            $this->addTwigVar('trainee', $trainee);
            $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__trainee_edit_txt', array(
                '%trainee%' => $trainee->getFullname()
            )));

            $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__trainee_edit', array(
                '%trainee%' => $trainee->getFullname()
            )));

            return $this->render('IlcfranceWorldspeakAdminFrontBundle:Trainee:edit.html.twig', $this->getTwigVars());
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addError($e->getLine() . ' ' . $e->getMessage());
        }

        return $this->redirect($urlFrom);
    }

    /**
     * notifyByMail TimeCredit (method GET)
     *
     * @param Request $request
     * @param string $id
     *
     * @return RedirectResponse
     */
    public function notifyByMailAction(Request $request, $id)
    {
        $urlFrom = $this->getReferer($request);
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('Admin__trainee_list');
        }
        $em = $this->getEntityManager();
        try {
            $timeCredit = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->findOneBy(array(
                'id' => $id
            ));

            if (null == $timeCredit) {
                $this->addFlash('warning', 'TimeCredit.mailNotfound');

                return $this->redirect($urlFrom);
            }
            $trainee = $timeCredit->getTrainee();

            if (null != $trainee->getEmail() && trim($trainee->getEmail()) != '') {

                $locale = null;
                if (null != $trainee->getPreferedLocale()) {
                    $locale = $trainee->getPreferedLocale()->getPrefix();
                }
                $mvars = array();
                $mvars['timeCredit'] = $timeCredit;
                $mvars['userPreferedLocale'] = $locale;
                $from = $this->getParameter('mail_from');
                $fromName = $this->getParameter('mail_from_name');
                $subject = $this->translate('_mail.new_timeCredit_subject', array(), null, $locale);

                $message = Swift_Message::newInstance()->setFrom($from, $fromName)
                    ->setTo($trainee->getEmail(), $trainee->getFullname())
                    ->setSubject($subject)
                    ->setBody($this->renderView('IlcfranceWorldspeakSharedResBundle:Mail:trainee.new_timeCredit.html.twig', $mvars), 'text/html');

                $this->sendmail($message);

                $timeCredit->setNotifyByMail(TimeCredit::NOTIFYBYMAIL_SENT);
                $em->persist($timeCredit);
                $em->flush();

                $this->addFlash('success', $this->translate('TimeCredit.mailSuccess', array(
                    '%totalHours%' => $timeCredit->getTotalHours(),
                    '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                    '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                    '%trainee%' => $trainee->getFullname()
                )));
            } else {

                $this->addFlash('error', $this->translate('TimeCredit.mailErrorNotValid', array(
                    '%totalHours%' => $timeCredit->getTotalHours(),
                    '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                    '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                    '%trainee%' => $trainee->getFullname()
                )));
            }
        } catch (\Exception $e) {
            $this->addFlash('error', $this->translate('TimeCredit.mailErrorUnknown'));
            $logger = $this->getLogger();
            $logger->addError($e->getLine() . ' ' . $e->getMessage());
        }

        return $this->redirect($urlFrom);
    }

    /**
     * Edit TimeCredit (method GET)
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
            $urlFrom = $this->generateUrl('Admin__trainee_list');
        }
        $em = $this->getEntityManager();
        try {
            $timeCredit = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->findOneBy(array(
                'id' => $id
            ));

            if (null == $timeCredit) {
                $this->addFlash('warning', 'TimeCredit.editNotfound');

                return $this->redirect($urlFrom);
            }

            $timeCreditCefBeginForm = $this->createForm(TimeCreditCefBeginTForm::class, $timeCredit);
            $timeCreditCefEndForm = $this->createForm(TimeCreditCefEndTForm::class, $timeCredit);
            $timeCreditDeadLineForm = $this->createForm(TimeCreditDeadLineTForm::class, $timeCredit);
            $timeCreditDetailsForm = $this->createForm(TimeCreditDetailsTForm::class, $timeCredit);
            $timeCreditEndReportForm = $this->createForm(TimeCreditEndReportTForm::class, $timeCredit);
            $timeCreditForcedKpiForm = $this->createForm(TimeCreditForcedKpiTForm::class, $timeCredit);
            $timeCreditFtypeForm = $this->createForm(TimeCreditFtypeTForm::class, $timeCredit);
            $timeCreditLevelForm = $this->createForm(TimeCreditLevelTForm::class, $timeCredit);
            $timeCreditLockoutForm = $this->createForm(TimeCreditLockoutTForm::class, $timeCredit);
            $timeCreditShowReportForm = $this->createForm(TimeCreditShowReportTForm::class, $timeCredit);
            $timeCreditTotalHoursForm = $this->createForm(TimeCreditTotalHoursTForm::class, $timeCredit);

            $this->addTwigVar('timeCredit', $timeCredit);

            $this->addTwigVar('timeCreditCefBeginForm', $timeCreditCefBeginForm->createView());
            $this->addTwigVar('timeCreditCefEndForm', $timeCreditCefEndForm->createView());
            $this->addTwigVar('timeCreditDeadLineForm', $timeCreditDeadLineForm->createView());
            $this->addTwigVar('timeCreditDetailsForm', $timeCreditDetailsForm->createView());
            $this->addTwigVar('timeCreditEndReportForm', $timeCreditEndReportForm->createView());
            $this->addTwigVar('timeCreditForcedKpiForm', $timeCreditForcedKpiForm->createView());
            $this->addTwigVar('timeCreditFtypeForm', $timeCreditFtypeForm->createView());
            $this->addTwigVar('timeCreditLevelForm', $timeCreditLevelForm->createView());
            $this->addTwigVar('timeCreditLockoutForm', $timeCreditLockoutForm->createView());
            $this->addTwigVar('timeCreditShowReportForm', $timeCreditShowReportForm->createView());
            $this->addTwigVar('timeCreditTotalHoursForm', $timeCreditTotalHoursForm->createView());

            $timeCreditDocument = new TimeCreditDocument();
            $timeCreditDocument->setTimeCredit($timeCredit);
            $timeCreditDocumentAddForm = $this->createForm(TimeCreditDocumentAddTForm::class, $timeCreditDocument, array(
                'level' => $timeCredit->getLevel()
            ));

            $this->addTwigVar('timeCreditDocumentAddForm', $timeCreditDocumentAddForm->createView());

            $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__timeCredit_edit_txt', array(
                '%totalHours%' => $timeCredit->getTotalHours(),
                '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                '%trainee%' => $timeCredit->getTrainee()
                    ->getFullname()
            )));

            $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__timeCredit_edit', array(
                '%totalHours%' => $timeCredit->getTotalHours(),
                '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                '%trainee%' => $timeCredit->getTrainee()
                    ->getFullname()
            )));

            return $this->render('IlcfranceWorldspeakAdminFrontBundle:TimeCredit:edit.html.twig', $this->getTwigVars());
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addError($e->getLine() . ' ' . $e->getMessage());
        }

        return $this->redirect($urlFrom);
    }

    /**
     * Edit TimeCredit (method Post)
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
            $urlFrom = $this->generateUrl('Admin__trainee_list');
        }
        $em = $this->getEntityManager();
        try {
            $timeCredit = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->findOneBy(array(
                'id' => $id
            ));

            if (null == $timeCredit) {
                $this->addFlash('warning', 'TimeCredit.editNotfound');

                return $this->redirect($urlFrom);
            }

            $timeCreditCefBeginForm = $this->createForm(TimeCreditCefBeginTForm::class, $timeCredit);
            $timeCreditCefEndForm = $this->createForm(TimeCreditCefEndTForm::class, $timeCredit);
            $timeCreditDeadLineForm = $this->createForm(TimeCreditDeadLineTForm::class, $timeCredit);
            $timeCreditDetailsForm = $this->createForm(TimeCreditDetailsTForm::class, $timeCredit);
            $timeCreditEndReportForm = $this->createForm(TimeCreditEndReportTForm::class, $timeCredit);
            $timeCreditForcedKpiForm = $this->createForm(TimeCreditForcedKpiTForm::class, $timeCredit);
            $timeCreditFtypeForm = $this->createForm(TimeCreditFtypeTForm::class, $timeCredit);
            $timeCreditLevelForm = $this->createForm(TimeCreditLevelTForm::class, $timeCredit);
            $timeCreditLockoutForm = $this->createForm(TimeCreditLockoutTForm::class, $timeCredit);
            $timeCreditShowReportForm = $this->createForm(TimeCreditShowReportTForm::class, $timeCredit);
            $timeCreditTotalHoursForm = $this->createForm(TimeCreditTotalHoursTForm::class, $timeCredit);

            ;
            $data = $request->request->all();
            if (isset($data['TimeCreditCefBeginForm'])) {
                $timeCreditCefBeginForm->handleRequest($request);
                if ($timeCreditCefBeginForm->isValid()) {
                    $em->persist($timeCredit);
                    $em->flush();

                    $this->addFlash('success', $this->translate('TimeCredit.editSuccessCefBegin', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));

                    return $this->redirect($urlFrom);
                } else {
                    $em->refresh($timeCredit);

                    $this->addFlash('error', $this->translate('TimeCredit.editErrorCefBegin', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));
                }
            } elseif (isset($data['TimeCreditCefEndForm'])) {
                $timeCreditCefEndForm->handleRequest($request);
                if ($timeCreditCefEndForm->isValid()) {
                    $em->persist($timeCredit);
                    $em->flush();

                    $this->addFlash('success', $this->translate('TimeCredit.editSuccessCefEnd', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));

                    return $this->redirect($urlFrom);
                } else {
                    $em->refresh($timeCredit);

                    $this->addFlash('error', $this->translate('TimeCredit.editErrorCefEnd', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));
                }
            } elseif (isset($data['TimeCreditDeadLineForm'])) {
                $timeCreditDeadLineForm->handleRequest($request);
                if ($timeCreditDeadLineForm->isValid()) {
                    $em->persist($timeCredit);
                    $em->flush();

                    $this->addFlash('success', $this->translate('TimeCredit.editSuccessDeadLine', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));

                    return $this->redirect($urlFrom);
                } else {
                    $em->refresh($timeCredit);

                    $this->addFlash('error', $this->translate('TimeCredit.editErrorDeadLine', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));
                }
            } elseif (isset($data['TimeCreditDetailsForm'])) {
                $timeCreditDetailsForm->handleRequest($request);
                if ($timeCreditDetailsForm->isValid()) {
                    $em->persist($timeCredit);
                    $em->flush();

                    $this->addFlash('success', $this->translate('TimeCredit.editSuccessDetails', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));

                    return $this->redirect($urlFrom);
                } else {
                    $em->refresh($timeCredit);

                    $this->addFlash('error', $this->translate('TimeCredit.editErrorDetails', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));
                }
            } elseif (isset($data['TimeCreditEndReportForm'])) {
                $timeCreditEndReportForm->handleRequest($request);
                if ($timeCreditEndReportForm->isValid()) {
                    $em->persist($timeCredit);
                    $em->flush();

                    $this->addFlash('success', $this->translate('TimeCredit.editSuccessEndReport', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));

                    return $this->redirect($urlFrom);
                } else {
                    $em->refresh($timeCredit);

                    $this->addFlash('error', $this->translate('TimeCredit.editErrorEndReport', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));
                }
            } elseif (isset($data['TimeCreditForcedKpiForm'])) {
                $timeCreditForcedKpiForm->handleRequest($request);
                if ($timeCreditForcedKpiForm->isValid()) {
                    $em->persist($timeCredit);
                    $em->flush();

                    $this->addFlash('success', $this->translate('TimeCredit.editSuccessForcedKpi', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));

                    return $this->redirect($urlFrom);
                } else {
                    $em->refresh($timeCredit);

                    $this->addFlash('error', $this->translate('TimeCredit.editErrorForcedKpi', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));
                }
            } elseif (isset($data['TimeCreditFtypeForm'])) {
                $timeCreditFtypeForm->handleRequest($request);
                if ($timeCreditFtypeForm->isValid()) {
                    $em->persist($timeCredit);
                    $em->flush();

                    $this->addFlash('success', $this->translate('TimeCredit.editSuccessFtype', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));

                    return $this->redirect($urlFrom);
                } else {
                    $em->refresh($timeCredit);

                    $this->addFlash('error', $this->translate('TimeCredit.editErrorFtype', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));
                }
            } elseif (isset($data['TimeCreditLevelForm'])) {
                $timeCreditLevelForm->handleRequest($request);
                if ($timeCreditLevelForm->isValid()) {
                    $em->persist($timeCredit);
                    $em->flush();

                    $this->addFlash('success', $this->translate('TimeCredit.editSuccessLevel', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));

                    return $this->redirect($urlFrom);
                } else {
                    $em->refresh($timeCredit);

                    $this->addFlash('error', $this->translate('TimeCredit.editErrorLevel', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));
                }
            } elseif (isset($data['TimeCreditLockoutForm'])) {
                $timeCreditLockoutForm->handleRequest($request);
                if ($timeCreditLockoutForm->isValid()) {
                    $em->persist($timeCredit);
                    $em->flush();

                    $this->addFlash('success', $this->translate('TimeCredit.editSuccessLockout', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));

                    return $this->redirect($urlFrom);
                } else {
                    $em->refresh($timeCredit);

                    $this->addFlash('error', $this->translate('TimeCredit.editErrorLockout', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));
                }
            } elseif (isset($data['TimeCreditShowReportForm'])) {
                $timeCreditShowReportForm->handleRequest($request);
                if ($timeCreditShowReportForm->isValid()) {
                    $em->persist($timeCredit);
                    $em->flush();

                    $this->addFlash('success', $this->translate('TimeCredit.editSuccessShowReport', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));

                    return $this->redirect($urlFrom);
                } else {
                    $em->refresh($timeCredit);

                    $this->addFlash('error', $this->translate('TimeCredit.editErrorShowReport', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));
                }
            } elseif (isset($data['TimeCreditTotalHoursForm'])) {
                $timeCreditTotalHoursForm->handleRequest($request);
                if ($timeCreditTotalHoursForm->isValid()) {

                    $em->persist($timeCredit);
                    $em->flush();

                    $this->addFlash('success', $this->translate('TimeCredit.editSuccessTotalHours', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));

                    return $this->redirect($urlFrom);
                } else {
                    $em->refresh($timeCredit);

                    $this->addFlash('error', $this->translate('TimeCredit.editErrorTotalHours', array(
                        '%totalHours%' => $timeCredit->getTotalHours(),
                        '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                        '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                        '%trainee%' => $timeCredit->getTrainee()
                            ->getFullname()
                    )));
                }
            }

            $this->addTwigVar('timeCredit', $timeCredit);

            $this->addTwigVar('timeCreditCefBeginForm', $timeCreditCefBeginForm->createView());
            $this->addTwigVar('timeCreditCefEndForm', $timeCreditCefEndForm->createView());
            $this->addTwigVar('timeCreditDeadLineForm', $timeCreditDeadLineForm->createView());
            $this->addTwigVar('timeCreditDetailsForm', $timeCreditDetailsForm->createView());
            $this->addTwigVar('timeCreditEndReportForm', $timeCreditEndReportForm->createView());
            $this->addTwigVar('timeCreditForcedKpiForm', $timeCreditForcedKpiForm->createView());
            $this->addTwigVar('timeCreditFtypeForm', $timeCreditFtypeForm->createView());
            $this->addTwigVar('timeCreditLevelForm', $timeCreditLevelForm->createView());
            $this->addTwigVar('timeCreditLockoutForm', $timeCreditLockoutForm->createView());
            $this->addTwigVar('timeCreditShowReportForm', $timeCreditShowReportForm->createView());
            $this->addTwigVar('timeCreditTotalHoursForm', $timeCreditTotalHoursForm->createView());

            $timeCreditDocument = new TimeCreditDocument();
            $timeCreditDocument->setTimeCredit($timeCredit);
            $timeCreditDocumentAddForm = $this->createForm(TimeCreditDocumentAddTForm::class, $timeCreditDocument, array(
                'level' => $timeCredit->getLevel()
            ));

            $this->addTwigVar('timeCreditDocumentAddForm', $timeCreditDocumentAddForm->createView());

            $this->addTwigVar('tabActive', 2);
            $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__timeCredit_edit_txt', array(
                '%totalHours%' => $timeCredit->getTotalHours(),
                '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                '%trainee%' => $timeCredit->getTrainee()
                    ->getFullname()
            )));

            $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__timeCredit_edit', array(
                '%totalHours%' => $timeCredit->getTotalHours(),
                '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                '%trainee%' => $timeCredit->getTrainee()
                    ->getFullname()
            )));

            return $this->render('IlcfranceWorldspeakAdminFrontBundle:TimeCredit:edit.html.twig', $this->getTwigVars());
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addError($e->getLine() . ' ' . $e->getMessage());
        }

        return $this->redirect($urlFrom);
    }

    /**
     * Report TimeCredit
     *
     * @param Request $request
     * @param string $id
     *
     * @return RedirectResponse|Response
     */
    public function reportAction(Request $request, $id)
    {
        $urlFrom = $this->getReferer($request);
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('Admin__trainee_list');
        }
        $em = $this->getEntityManager();
        try {
            $timeCredit = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->findOneBy(array(
                'id' => $id
            ));

            if (null == $timeCredit) {
                $this->addFlash('warning', 'TimeCredit.editNotfound');

                return $this->redirect($urlFrom);
            }

            $this->addTwigVar('timeCredit', $timeCredit);

            $this->addTwigVar('pagetitle', $this->translate('TimeCredit.report.pdf'));

            $pdf = $this->get('white_october.tcpdf')->create();

            $pdf->SetCreator('Salah Abdelkader Seif Eddine');
            $pdf->SetAuthor('ILCFrance - WorldSpeak');
            $pdf->SetTitle($this->translate('TimeCredit.report') . ' ' . $timeCredit->getTrainee()
                ->getFullname());
            $pdf->SetSubject($this->translate('TimeCredit.report') . ' ' . $timeCredit->getTrainee()
                ->getFullname());

            $pdf->SetHeaderData(null, 0, 'ILC WorldSpeak - ' . $this->translate('TimeCredit.report'), $timeCredit->getTrainee()
                ->getFullname(), array(
                0,
                32,
                255
            ), array(
                0,
                32,
                128
            ));
            $pdf->setFooterData(array(
                0,
                64,
                0
            ), array(
                0,
                64,
                128
            ));

            // set header and footer fonts
            $pdf->setHeaderFont(array(
                PDF_FONT_NAME_MAIN,
                '',
                PDF_FONT_SIZE_MAIN
            ));
            $pdf->setFooterFont(array(
                PDF_FONT_NAME_DATA,
                '',
                PDF_FONT_SIZE_DATA
            ));

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // set auto page breaks
            $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            $pdf->setFontSubsetting(true);

            // Set font
            // dejavusans is a UTF-8 Unicode font, if you only need to
            // print standard ASCII chars, you can use core fonts like
            // helvetica or times to reduce file size.
            $pdf->SetFont('helvetica', '', 11, '', true);

            // Add a page
            // This method has several options, check the source code documentation for more information.
            $pdf->AddPage();

            $html = $this->renderView('IlcfranceWorldspeakSharedResBundle:TimeCredit:report1.html.twig', $this->getTwigVars());

            $pdf->writeHTML($html, true, false, true, false, ''); // */

            $pdf->AddPage();

            $html = $this->renderView('IlcfranceWorldspeakSharedResBundle:TimeCredit:report2.html.twig', $this->getTwigVars());

            $pdf->writeHTML($html, true, false, true, false, '');

            $pdf->lastPage();

            $response = new Response($pdf->Output('ILCWorldSpeak_Formation_' . $timeCredit->getTrainee()
                ->getFullname() . '.pdf"', 'D'));
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'attachment; filename="Formation ' . $timeCredit->getTrainee()
                ->getFullname() . '.pdf"');
            $response->headers->set('Pragma', 'public');
            $response->headers->set('Cache-Control', 'maxage=1');

            return $response;
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addError($e->getLine() . ' ' . $e->getMessage());
        }

        return $this->redirect($urlFrom);
    }

    /**
     * Delete TimeCredit
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
            return $this->redirect($this->generateUrl('Admin__trainee_list'));
        }
        $em = $this->getEntityManager();

        try {
            $timeCredit = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->findOneBy(array(
                'id' => $id
            ));

            if (null != $timeCredit) {

                $em->remove($timeCredit);
                $em->flush();

                $this->addFlash('success', $this->translate('TimeCredit.deleteSuccess', array(
                    '%totalHours%' => $timeCredit->getTotalHours(),
                    '%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
                    '%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
                    '%trainee%' => $timeCredit->getTrainee()
                        ->getFullname()
                )));
            } else {
                $this->addFlash('warning', 'TimeCredit.deleteNotfound');
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addError($e->getLine() . ' ' . $e->getMessage());
            $this->addFlash('error', 'TimeCredit.deleteError');
        }

        return $this->redirect($urlFrom);
    }
}
