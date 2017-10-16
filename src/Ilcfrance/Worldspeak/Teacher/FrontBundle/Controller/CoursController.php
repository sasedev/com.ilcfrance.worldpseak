<?php
namespace Ilcfrance\Worldspeak\Teacher\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Cours;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TeacherLog;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCreditDocument;
use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Ilcfrance\Worldspeak\Teacher\FrontBundle\Form\CoursCorrectionTForm;
use Ilcfrance\Worldspeak\Teacher\FrontBundle\Form\CoursKpiTForm;
use Ilcfrance\Worldspeak\Teacher\FrontBundle\Form\CoursPhoneTForm;
use Ilcfrance\Worldspeak\Teacher\FrontBundle\Form\CoursStatusTForm;
use Ilcfrance\Worldspeak\Teacher\FrontBundle\Form\CoursTypeTForm;
use Ilcfrance\Worldspeak\Teacher\FrontBundle\Form\TimeCreditCefEndTForm;
use Ilcfrance\Worldspeak\Teacher\FrontBundle\Form\TimeCreditDocumentAddTForm;
use Ilcfrance\Worldspeak\Teacher\FrontBundle\Form\TimeCreditEndReportTForm;
use Ilcfrance\Worldspeak\Teacher\FrontBundle\Form\TraineeEmailTForm;
use Ilcfrance\Worldspeak\Teacher\FrontBundle\Form\TraineeProfileAdvancedTForm;
use Ilcfrance\Worldspeak\Teacher\FrontBundle\Form\TraineeProfileTForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cours Controller
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class CoursController extends BaseController
{

    /**
     * Edit Cours (method GET)
     *
     * @param string $id
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, $id)
    {
        $urlFrom = $this->getReferer($request);
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('Teacher__default_homepage');
        }
        $em = $this->getEntityManager();
        try {
            $cours = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->findOneBy(array(
                'id' => $id
            ));

            if (null == $cours) {
                $this->addFlash('warning', 'Cours.editNotfound');

                return $this->redirect($urlFrom);
            }

            $coursCorrectionForm = $this->createForm(CoursCorrectionTForm::class, $cours);
            $coursKpiForm = $this->createForm(CoursKpiTForm::class, $cours);
            $coursPhoneForm = $this->createForm(CoursPhoneTForm::class, $cours);
            $coursStatusForm = $this->createForm(CoursStatusTForm::class, $cours);
            $coursTypeForm = $this->createForm(CoursTypeTForm::class, $cours);

            $timeCredit = $cours->getTimeCredit();

            $timeCreditCefEndForm = $this->createForm(TimeCreditCefEndTForm::class, $timeCredit);
            $timeCreditEndReportForm = $this->createForm(TimeCreditEndReportTForm::class, $timeCredit);

            $this->addTwigVar('timeCreditCefEndForm', $timeCreditCefEndForm->createView());
            $this->addTwigVar('timeCreditEndReportForm', $timeCreditEndReportForm->createView());

            $timeCreditDocument = new TimeCreditDocument();
            $timeCreditDocument->setNotifyByMail(TimeCreditDocument::NOTIFYBYMAIL_SENT);
            $timeCreditDocument->setTimeCredit($timeCredit);
            $timeCreditDocumentAddForm = $this->createForm(TimeCreditDocumentAddTForm::class, $timeCreditDocument, array(
                'level' => $timeCredit->getLevel()
            ));

            $this->addTwigVar('timeCreditDocumentAddForm', $timeCreditDocumentAddForm->createView());

            $trainee = $timeCredit->getTrainee();

            $traineeEmailForm = $this->createForm(TraineeEmailTForm::class, $trainee);
            $this->addTwigVar('traineeEmailForm', $traineeEmailForm->createView());

            $traineeProfileForm = $this->createForm(TraineeProfileTForm::class, $trainee);
            $this->addTwigVar('traineeProfileForm', $traineeProfileForm->createView());

            $traineeProfileAdvancedForm = $this->createForm(TraineeProfileAdvancedTForm::class, $trainee);
            $this->addTwigVar('traineeProfileAdvancedForm', $traineeProfileAdvancedForm->createView());

            $dateFormatter = $this->get('ilc_france_worldspeak_shared_res.date_formatter');

            $this->addTwigVar('cours', $cours);

            $this->addTwigVar('coursCorrectionForm', $coursCorrectionForm->createView());
            $this->addTwigVar('coursKpiForm', $coursKpiForm->createView());
            $this->addTwigVar('coursPhoneForm', $coursPhoneForm->createView());
            $this->addTwigVar('coursStatusForm', $coursStatusForm->createView());
            $this->addTwigVar('coursTypeForm', $coursTypeForm->createView());

            $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleTeacher__cours_edit_txt', array(
                '%trainee%' => $cours->getTimeCredit()
                    ->getTrainee()
                    ->getFullname(),
                '%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
                '%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
                '%duration%' => $cours->getDuration()
            )));

            $this->addTwigVar('pagetitle', $this->translate('_pagetitleTeacher__cours_edit', array(
                '%trainee%' => $cours->getTimeCredit()
                    ->getTrainee()
                    ->getFullname(),
                '%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
                '%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
                '%duration%' => $cours->getDuration()
            )));

            return $this->render('IlcfranceWorldspeakTeacherFrontBundle:Cours:edit.html.twig', $this->getTwigVars());
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addError($e->getLine() . ' ' . $e->getMessage());
        }

        return $this->redirect($urlFrom);
    }

    /**
     * Edit Cours (method POST)
     *
     * @param string $id
     *
     * @return RedirectResponse|Response
     */
    public function editPostAction(Request $request, $id)
    {
        $urlFrom = $this->getReferer($request);
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('Teacher__default_homepage');
        }
        $em = $this->getEntityManager();
        $currentUser = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();

        try {
            $cours = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->findOneBy(array(
                'id' => $id
            ));

            $dtStart = $cours->getDtStart();

            if (null == $cours) {
                $this->addFlash('warning', 'Cours.editNotfound');

                return $this->redirect($urlFrom);
            }

            $timeCredit = $cours->getTimeCredit();

            $oldStatus = $cours->getStatus();

            $coursCorrectionForm = $this->createForm(CoursCorrectionTForm::class, $cours);
            $coursKpiForm = $this->createForm(CoursKpiTForm::class, $cours);
            $coursPhoneForm = $this->createForm(CoursPhoneTForm::class, $cours);
            $coursStatusForm = $this->createForm(CoursStatusTForm::class, $cours);
            $coursTypeForm = $this->createForm(CoursTypeTForm::class, $cours);

            $dateFormatter = $this->get('ilc_france_worldspeak_shared_res.date_formatter');

            ;
            $data = $request->request->all();
            if (isset($data['CoursCorrectionForm'])) {
                $coursCorrectionForm->handleRequest($request);
                if ($coursCorrectionForm->isValid()) {
                    $em->persist($cours);
                    $em->flush();

                    $teacherLog = new TeacherLog();
                    $teacherLog->setTeacher($currentUser);
                    $teacherLog->setMsg("Succes Correction Cours du " . $dateFormatter->format($dtStart, 'long', 'medium') . " : <br>" . $cours->getProgress() . "<br>" . $cours->getCorrectionVocabulairy() . "<br>" . $cours->getCorrectionStructure() . "<br>" . $cours->getCorrectionPrononciation() . "<br>" . $cours->getComments() . "<br>");
                    $em->persist($teacherLog);
                    $em->flush();

                    $this->addFlash('success', $this->translate('Cours.editSuccessCorrection', array(
                        '%trainee%' => $cours->getTimeCredit()
                            ->getTrainee()
                            ->getFullname(),
                        '%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
                        '%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
                        '%duration%' => $cours->getDuration()
                    )));

                    return $this->redirect($urlFrom);
                } else {

                    // $em->refresh($cours);

                    $teacherLog = new TeacherLog();
                    $teacherLog->setTeacher($currentUser);
                    $teacherLog->setMsg("Echec Correction Cours du " . $dateFormatter->format($dtStart, 'long', 'medium') . " : <br>" . $coursCorrectionForm['progress']->getData() . "<br>" . $coursCorrectionForm['correctionVocabulairy']->getData() . "<br>" . $coursCorrectionForm['correctionStructure']->getData() . "<br>" . $coursCorrectionForm['correctionPrononciation']->getData() . "<br>" . $coursCorrectionForm['comments']->getData() . "<br>");
                    $em->persist($teacherLog);
                    $em->flush();

                    $this->addFlash('danger', $this->translate('Cours.editErrorCorrection', array(
                        '%trainee%' => $cours->getTimeCredit()
                            ->getTrainee()
                            ->getFullname(),
                        '%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
                        '%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
                        '%duration%' => $cours->getDuration()
                    )));
                }
            } elseif (isset($data['CoursKpiForm'])) {
                $coursKpiForm->handleRequest($request);
                if ($coursKpiForm->isValid()) {
                    $em->persist($cours);
                    $em->flush();

                    $teacherLog = new TeacherLog();
                    $teacherLog->setTeacher($currentUser);
                    $teacherLog->setMsg("Succes KPI Cours du " . $dateFormatter->format($dtStart, 'long', 'medium') . " : <br>" . $cours->getKpiCorrectionConsideration() . "<br>" . $cours->getKpiHomeworkPerformed() . "<br>" . $cours->getKpiParticipation() . "<br>" . $cours->getKpiVocabularyRetention() . "<br>");
                    $em->persist($teacherLog);
                    $em->flush();

                    $this->addFlash('success', $this->translate('Cours.editSuccessKpi', array(
                        '%trainee%' => $cours->getTimeCredit()
                            ->getTrainee()
                            ->getFullname(),
                        '%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
                        '%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
                        '%duration%' => $cours->getDuration()
                    )));

                    return $this->redirect($urlFrom);
                } else {

                    // $em->refresh($cours);

                    $teacherLog = new TeacherLog();
                    $teacherLog->setTeacher($currentUser);
                    $teacherLog->setMsg("Echec KPI Cours du " . $dateFormatter->format($dtStart, 'long', 'medium') . " : <br>" . $coursKpiForm['kpiCorrectionConsideration']->getData() . "<br>" . $coursKpiForm['kpiHomeworkPerformed']->getData() . "<br>" . $coursKpiForm['kpiParticipation']->getData() . "<br>" . $coursKpiForm['kpiVocabularyRetention']->getData() . "<br>");
                    $em->persist($teacherLog);
                    $em->flush();

                    $this->addFlash('error', $this->translate('Cours.editErrorKpi', array(
                        '%trainee%' => $cours->getTimeCredit()
                            ->getTrainee()
                            ->getFullname(),
                        '%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
                        '%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
                        '%duration%' => $cours->getDuration()
                    )));
                }
            } elseif (isset($data['CoursPhoneForm'])) {
                $coursPhoneForm->handleRequest($request);
                if ($coursPhoneForm->isValid()) {
                    $em->persist($cours);
                    $em->flush();

                    $teacherLog = new TeacherLog();
                    $teacherLog->setTeacher($currentUser);
                    $teacherLog->setMsg("Succes Phone Cours du " . $dateFormatter->format($dtStart, 'long', 'medium') . " : <br>" . $cours->getPhone());
                    $em->persist($teacherLog);
                    $em->flush();

                    $this->addFlash('success', $this->translate('Cours.editSuccessPhone', array(
                        '%trainee%' => $cours->getTimeCredit()
                            ->getTrainee()
                            ->getFullname(),
                        '%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
                        '%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
                        '%duration%' => $cours->getDuration()
                    )));

                    return $this->redirect($urlFrom);
                } else {

                    $em->refresh($cours);

                    $teacherLog = new TeacherLog();
                    $teacherLog->setTeacher($currentUser);
                    $teacherLog->setMsg("Echec Phone Cours du " . $dateFormatter->format($dtStart, 'long', 'medium') . " : <br>" . $coursPhoneForm['phone']->getData() . "<br>");
                    $em->persist($teacherLog);
                    $em->flush();

                    $this->addFlash('error', $this->translate('Cours.editErrorPhone', array(
                        '%trainee%' => $cours->getTimeCredit()
                            ->getTrainee()
                            ->getFullname(),
                        '%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
                        '%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
                        '%duration%' => $cours->getDuration()
                    )));
                }
            } elseif (isset($data['CoursStatusForm'])) {
                $coursStatusForm->handleRequest($request);
                if ($coursStatusForm->isValid()) {

                    if ($oldStatus == $cours::STATUS_ABSENT) {
                        $timeCredit->setLostHours($timeCredit->getLostHours() - (1 * $cours->getDuration() / 60));
                    }
                    if ($oldStatus == Cours::STATUS_DONE) {
                        $timeCredit->setDoneHours($timeCredit->getDoneHours() - (1 * $cours->getDuration() / 60));
                    }
                    if ($oldStatus == Cours::STATUS_PLANNED || $oldStatus == Cours::STATUS_PLANNED_PENDING) {

                        $timeCredit->setReservedHours($timeCredit->getReservedHours() - (1 * $cours->getDuration() / 60));
                    }

                    if ($cours->getStatus() == $cours::STATUS_ABSENT) {
                        $timeCredit->setLostHours($timeCredit->getLostHours() + (1 * $cours->getDuration() / 60));
                    }
                    if ($cours->getStatus() == Cours::STATUS_DONE) {
                        $timeCredit->setDoneHours($timeCredit->getDoneHours() + (1 * $cours->getDuration() / 60));
                    }
                    if ($cours->getStatus() == Cours::STATUS_PLANNED || $cours->getStatus() == Cours::STATUS_PLANNED_PENDING) {

                        $timeCredit->setReservedHours($timeCredit->getReservedHours() + (1 * $cours->getDuration() / 60));
                    }

                    $em->persist($timeCredit);

                    $em->persist($cours);
                    $em->flush();

                    $teacherLog = new TeacherLog();
                    $teacherLog->setTeacher($currentUser);
                    $teacherLog->setMsg("Succes Status Cours du " . $dateFormatter->format($dtStart, 'long', 'medium'));
                    $em->persist($teacherLog);
                    $em->flush();

                    $this->addFlash('success', $this->translate('Cours.editSuccessStatus', array(
                        '%trainee%' => $cours->getTimeCredit()
                            ->getTrainee()
                            ->getFullname(),
                        '%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
                        '%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
                        '%duration%' => $cours->getDuration()
                    )));

                    return $this->redirect($urlFrom);
                } else {

                    $em->refresh($cours);

                    $teacherLog = new TeacherLog();
                    $teacherLog->setTeacher($currentUser);
                    $teacherLog->setMsg("Error Status Cours du " . $dateFormatter->format($dtStart, 'long', 'medium'));
                    $em->persist($teacherLog);
                    $em->flush();

                    $this->addFlash('error', $this->translate('Cours.editErrorStatus', array(
                        '%trainee%' => $cours->getTimeCredit()
                            ->getTrainee()
                            ->getFullname(),
                        '%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
                        '%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
                        '%duration%' => $cours->getDuration()
                    )));
                }
            } elseif (isset($data['CoursTypeForm'])) {
                $coursTypeForm->handleRequest($request);
                if ($coursTypeForm->isValid()) {
                    $em->persist($cours);
                    $em->flush();

                    $teacherLog = new TeacherLog();
                    $teacherLog->setTeacher($currentUser);
                    $teacherLog->setMsg("Succes Type Cours du " . $dateFormatter->format($dtStart, 'long', 'medium'));
                    $em->persist($teacherLog);
                    $em->flush();

                    $this->addFlash('success', $this->translate('Cours.editSuccessType', array(
                        '%trainee%' => $cours->getTimeCredit()
                            ->getTrainee()
                            ->getFullname(),
                        '%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
                        '%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
                        '%duration%' => $cours->getDuration()
                    )));

                    return $this->redirect($urlFrom);
                } else {

                    $em->refresh($cours);

                    $teacherLog = new TeacherLog();
                    $teacherLog->setTeacher($currentUser);
                    $teacherLog->setMsg("Error Type Cours du " . $dateFormatter->format($dtStart, 'long', 'medium'));
                    $em->persist($teacherLog);
                    $em->flush();

                    $this->addFlash('error', $this->translate('Cours.editErrorType', array(
                        '%trainee%' => $cours->getTimeCredit()
                            ->getTrainee()
                            ->getFullname(),
                        '%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
                        '%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
                        '%duration%' => $cours->getDuration()
                    )));
                }
            } else {
                $teacherLog = new TeacherLog();
                $teacherLog->setTeacher($currentUser);
                $teacherLog->setMsg($this->translate('Cours.editErrorUnknown', array(
                    '%trainee%' => $cours->getTimeCredit()
                        ->getTrainee()
                        ->getFullname(),
                    '%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
                    '%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
                    '%duration%' => $cours->getDuration()
                )));
                $em->persist($teacherLog);
                $em->flush();

                $this->addFlash('error', $this->translate('Cours.editErrorUnknown', array(
                    '%trainee%' => $cours->getTimeCredit()
                        ->getTrainee()
                        ->getFullname(),
                    '%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
                    '%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
                    '%duration%' => $cours->getDuration()
                )));
            }

            $this->addTwigVar('tabActive', 2);
            $this->addTwigVar('cours', $cours);

            $timeCreditDocument = new TimeCreditDocument();
            $timeCreditDocument->setNotifyByMail(TimeCreditDocument::NOTIFYBYMAIL_SENT);
            $timeCreditDocument->setTimeCredit($timeCredit);
            $timeCreditDocumentAddForm = $this->createForm(TimeCreditDocumentAddTForm::class, $timeCreditDocument, array(
                'level' => $timeCredit->getLevel()
            ));

            $this->addTwigVar('timeCreditDocumentAddForm', $timeCreditDocumentAddForm->createView());

            $trainee = $timeCredit->getTrainee();

            $traineeEmailForm = $this->createForm(TraineeEmailTForm::class, $trainee);
            $this->addTwigVar('traineeEmailForm', $traineeEmailForm->createView());

            $traineeProfileForm = $this->createForm(TraineeProfileTForm::class, $trainee);
            $this->addTwigVar('traineeProfileForm', $traineeProfileForm->createView());

            $traineeProfileAdvancedForm = $this->createForm(TraineeProfileAdvancedTForm::class, $trainee);
            $this->addTwigVar('traineeProfileAdvancedForm', $traineeProfileAdvancedForm->createView());

            $this->addTwigVar('coursCorrectionForm', $coursCorrectionForm->createView());
            $this->addTwigVar('coursKpiForm', $coursKpiForm->createView());
            $this->addTwigVar('coursPhoneForm', $coursPhoneForm->createView());
            $this->addTwigVar('coursStatusForm', $coursStatusForm->createView());
            $this->addTwigVar('coursTypeForm', $coursTypeForm->createView());

            $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleTeacher__cours_edit_txt', array(
                '%trainee%' => $cours->getTimeCredit()
                    ->getTrainee()
                    ->getFullname(),
                '%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
                '%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
                '%duration%' => $cours->getDuration()
            )));

            $this->addTwigVar('pagetitle', $this->translate('_pagetitleTeacher__cours_edit', array(
                '%trainee%' => $cours->getTimeCredit()
                    ->getTrainee()
                    ->getFullname(),
                '%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
                '%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
                '%duration%' => $cours->getDuration()
            )));

            return $this->render('IlcfranceWorldspeakTeacherFrontBundle:Cours:edit.html.twig', $this->getTwigVars());
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addError($e->getLine() . ' ' . $e->getMessage());
        }

        return $this->redirect($urlFrom);
    }
}
