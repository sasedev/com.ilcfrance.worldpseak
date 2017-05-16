<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\AdminNotif;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TeacherNotif;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TraineeNotif;

/**
 * Mail Notif command
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class MailNotifCommand extends ContainerAwareCommand
{

	/**
	 * (non-PHPdoc)
	 *
	 * @see \Symfony\Component\Console\Command\Command::configure()
	 */
	protected function configure()
	{
		parent::configure();

		$this->setName('worldspeak:mailNotif')->setDescription('Sends Email Notifications To Trainees And Teachers.');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$container = $this->getContainer();

		$em = $container->get('doctrine.orm.default_entity_manager');
		$trans = $container->get('translator.default');
		$mailer = $container->get('mailer');
		$logger = $container->get('logger');
		// $transport = $container->get('swiftmailer.transport.real');
		$templating = $container->get('templating');
		// $spool = $mailer->getTransport()->getSpool();

		$from = $container->getParameter('mail_from');
		$fromName = $container->getParameter('mail_from_name');

		$logger->addNotice('---------------------------------------------------------------------------------');
		$logger->addNotice('Début Envoie email notifications');
		$logger->addNotice('---------------------------------------------------------------------------------');

		$teacherNotifs = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherNotif')->getAllOldPendingEmail(false);

		$teacherMailSent = 0;
		$teacherMailNotSent = 0;

		foreach ($teacherNotifs as $notif) {
			$locale = null;

			if ($notif->getType() == TeacherNotif::TYPE_EMAIL_COURS_EDIT && $notif->getStatus() == TeacherNotif::PENDING) {
				$teacher = $notif->getTeacher();
				try {
					if (null != $teacher->getEmail()) {
						if (null != $teacher->getPreferedLocale()) {
							$locale = $teacher->getPreferedLocale()->getPrefix();
						}

						$mvars = array();
						$mvars['notif'] = $notif;
						$mvars['userPreferedLocale'] = $locale;

						$subject = $trans->trans('_mail.teacher.notif.cours.subject', array(), null, $locale);

						$message = \Swift_Message::newInstance()->setFrom($from, $fromName)->setTo($teacher->getEmail(), $teacher->getFullname())->setSubject($subject)->setBody($templating->render('IlcfranceWorldspeakSharedResBundle:Mail:teacher.notif.cours.html.twig', $mvars), 'text/html');

						$notif->setStatus(TeacherNotif::DONE);
						$em->persist($notif);
						$em->flush();
						$mailer->send($message);
						// $spool->flushQueue($transport);
						$teacherMailSent++;

						echo "Mail Notif Cours Edit Formateur " . $teacher->getFullname() . " (" . $teacher->getEmail() . ") pour le Cours de " . $notif->getCours()->getDtStart()->format('Y-m-d H:i') . " du stagiaire " . $notif->getTimeCredit()->getTrainee()->getFullname() . "\n";
					} else {
						$logger->addNotice("Impossible d'envoyer un email de notification pour la notif formateur CE (Email NA) " . $teacher->getFullname() . ' ' . $notif->getId());
						$teacherMailNotSent++;
						$notif->setStatus(TeacherNotif::ERROR);
						$em->persist($notif);
						// $em->flush();
					}
				} catch (\Exception $e) {
					$logger->addNotice("Impossible d'envoyer un email de notification pour la notif formateur CE " . $teacher->getFullname() . ' ' . $notif->getId() . ' ' . $e->getMessage());
					$teacherMailNotSent++;
					$notif->setStatus(TeacherNotif::ERROR);
					$em->persist($notif);
					// $em->flush();
				}
			} elseif ($notif->getType() == TeacherNotif::TYPE_EMAIL_TIMECREDIT_EDIT && $notif->getStatus() == TeacherNotif::PENDING) {
				// *
				$teacher = $notif->getTeacher();
				try {
					if (null != $teacher->getEmail()) {
						if (null != $teacher->getPreferedLocale()) {
							$locale = $teacher->getPreferedLocale()->getPrefix();
						}

						$mvars = array();
						$mvars['notif'] = $notif;
						$mvars['userPreferedLocale'] = $locale;

						$subject = $trans->trans('_mail.teacher.notif.timeCredit.subject', array(), null, $locale);

						$message = \Swift_Message::newInstance()->setFrom($from, $fromName)->setTo($teacher->getEmail(), $teacher->getFullname())->setSubject($subject)->setBody($templating->render('IlcfranceWorldspeakSharedResBundle:Mail:teacher.notif.timeCredit.html.twig', $mvars), 'text/html');

						$notif->setStatus(TeacherNotif::DONE);
						$em->persist($notif);
						$em->flush();
						$mailer->send($message);
						// $spool->flushQueue($transport);
						$teacherMailSent++;

						echo "Mail Notif TimeCredit Edit Formateur " . $teacher->getFullname() . " (" . $teacher->getEmail() . ") pour le Credit de " . $notif->getTimeCredit()->getTotalHours() . " Heures du stagiaire " . $notif->getTimeCredit()->getTrainee()->getFullname() . "\n";
					} else {
						$logger->addNotice("Impossible d'envoyer un email de notification pour la notif formateur TE (Email NA) " . $teacher->getFullname() . ' ' . $notif->getId());
						$teacherMailNotSent++;
						$notif->setStatus(TeacherNotif::ERROR);
						$em->persist($notif);
						// $em->flush();
					}
				} catch (\Exception $e) {
					$logger->addNotice("Impossible d'envoyer un email de notification pour la notif formateur TE " . $teacher->getFullname() . ' ' . $notif->getId() . ' ' . $e->getMessage());
					$teacherMailNotSent++;
					$notif->setStatus(TeacherNotif::ERROR);
					$em->persist($notif);
					// $em->flush();
				}
				// */
			}
		}
		$em->flush();

		$traineeNotifs = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TraineeNotif')->getAllOldPendingEmail(false);

		$traineeMailSent = 0;
		$traineeMailNotSent = 0;

		// *
		foreach ($traineeNotifs as $notif) {
			$locale = null;

			if ($notif->getType() == TraineeNotif::TYPE_EMAIL_24H_BEFORE_COURS && $notif->getStatus() == TraineeNotif::PENDING) {
				$trainee = $notif->getTrainee();
				try {
					if (null != $trainee->getEmail()) {
						if (null != $trainee->getPreferedLocale()) {
							$locale = $trainee->getPreferedLocale()->getPrefix();
						}

						$mvars = array();
						$mvars['notif'] = $notif;
						$mvars['userPreferedLocale'] = $locale;

						$subject = $trans->trans('_mail.trainee.notif.cours24h.subject', array(), null, $locale);

						$message = \Swift_Message::newInstance()->setFrom($from, $fromName)->setTo($trainee->getEmail(), $trainee->getFullname())->setSubject($subject)->setBody($templating->render('IlcfranceWorldspeakSharedResBundle:Mail:trainee.notif.cours24h.html.twig', $mvars), 'text/html');

						$notif->setStatus(TraineeNotif::DONE);
						$em->persist($notif);
						$em->flush();
						$mailer->send($message);
						// $spool->flushQueue($transport);
						$traineeMailSent++;

						echo "Mail Notif Cours 24H Stagiaire " . $trainee->getFullname() . " (" . $trainee->getEmail() . ") pour le Cours de " . $notif->getCours()->getDtStart()->format('Y-m-d H:i') . "\n";
					} else {
						$logger->addNotice("Impossible d'envoyer un email de notification pour la notif stagiaire 24h (Email NA)" . $trainee->getFullname() . ' ' . $notif->getId());
						$traineeMailNotSent++;
						$notif->setStatus(TraineeNotif::ERROR);
						$em->persist($notif);
						// $em->flush();
					}
				} catch (\Exception $e) {
					$logger->addNotice("Impossible d'envoyer un email de notification pour la notif stagiaire 24h " . $trainee->getFullname() . ' ' . $notif->getId() . ' ' . $e->getMessage());
					$traineeMailNotSent++;
					$notif->setStatus(TraineeNotif::ERROR);
					$em->persist($notif);
					// $em->flush();
				}
			} elseif ($notif->getType() == TraineeNotif::TYPE_EMAIL_15D_AFTER_COURS && $notif->getStatus() == TraineeNotif::PENDING) {
				$trainee = $notif->getTrainee();
				try {
					if (null != $trainee->getEmail()) {
						if (null != $trainee->getPreferedLocale()) {
							$locale = $trainee->getPreferedLocale()->getPrefix();
						}

						$mvars = array();
						$mvars['notif'] = $notif;
						$mvars['userPreferedLocale'] = $locale;

						$subject = $trans->trans('_mail.trainee.notif.timeCredit15d.subject', array(), null, $locale);

						$message = \Swift_Message::newInstance()->setFrom($from, $fromName)->setTo($trainee->getEmail(), $trainee->getFullname())->setSubject($subject)->setBody($templating->render('IlcfranceWorldspeakSharedResBundle:Mail:trainee.notif.' . 'timeCredit15d.html.twig', $mvars), 'text/html');

						$notif->setStatus(TraineeNotif::DONE);
						$em->persist($notif);
						$em->flush();
						$mailer->send($message);
						// $spool->flushQueue($transport);
						$traineeMailSent++;

						echo "Mail Notif TimeCredit 15D Stagiaire " . $trainee->getFullname() . " (" . $trainee->getEmail() . ") pour le Credit de " . $notif->getTimeCredit()->getTotalHours() . " Heures " . "\n";
					} else {
						$logger->addNotice("Impossible d'envoyer un email de notification pour la notif stagiaire 15d (Email NA)" . $trainee->getFullname() . ' ' . $notif->getId());
						$traineeMailNotSent++;
						$notif->setStatus(TraineeNotif::ERROR);
						$em->persist($notif);
						// $em->flush();
					}
				} catch (\Exception $e) {
					$logger->addNotice("Impossible d'envoyer un email de notification pour la notif stagiaire 15d " . $trainee->getFullname() . ' ' . $notif->getId() . ' ' . $e->getMessage());
					$traineeMailNotSent++;
					$notif->setStatus(TraineeNotif::ERROR);
					$em->persist($notif);
					// $em->flush();
				}
			} elseif ($notif->getType() == TraineeNotif::TYPE_EMAIL_30D_AFTER_COURS && $notif->getStatus() == TraineeNotif::PENDING) {
				$trainee = $notif->getTrainee();
				try {
					if (null != $trainee->getEmail()) {
						if (null != $trainee->getPreferedLocale()) {
							$locale = $trainee->getPreferedLocale()->getPrefix();
						}

						$mvars = array();
						$mvars['notif'] = $notif;
						$mvars['userPreferedLocale'] = $locale;

						$subject = $trans->trans('_mail.trainee.notif.timeCredit30d.subject', array(), null, $locale);

						$message = \Swift_Message::newInstance()->setFrom($from, $fromName)->setTo($trainee->getEmail(), $trainee->getFullname())->setSubject($subject)->setBody($templating->render('IlcfranceWorldspeakSharedResBundle:Mail:trainee.notif.' . 'timeCredit30d.html.twig', $mvars), 'text/html');

						$notif->setStatus(TraineeNotif::DONE);
						$em->persist($notif);
						$em->flush();
						$mailer->send($message);
						// $spool->flushQueue($transport);
						$traineeMailSent++;

						echo "Mail Notif TimeCredit 30D Stagiaire " . $trainee->getFullname() . " (" . $trainee->getEmail() . ") pour le Credit de " . $notif->getTimeCredit()->getTotalHours() . " Heures " . "\n";
					} else {
						$logger->addNotice("Impossible d'envoyer un email de notification pour la notif stagiaire 30d (Email NA)" . $trainee->getFullname() . ' ' . $notif->getId());
						$traineeMailNotSent++;
						$notif->setStatus(TraineeNotif::ERROR);
						$em->persist($notif);
						// $em->flush();
					}
				} catch (\Exception $e) {
					$logger->addNotice("Impossible d'envoyer un email de notification pour la notif stagiaire 30d " . $trainee->getFullname() . ' ' . $notif->getId() . ' ' . $e->getMessage());
					$traineeMailNotSent++;
					$notif->setStatus(TraineeNotif::ERROR);
					$em->persist($notif);
					// $em->flush();
				}
			} elseif ($notif->getType() == TraineeNotif::TYPE_EMAIL_SURVEYBEGIN && $notif->getStatus() == TraineeNotif::PENDING) {
				$trainee = $notif->getTrainee();
				try {
					if (null != $trainee->getEmail()) {
						if (null != $trainee->getPreferedLocale()) {
							$locale = $trainee->getPreferedLocale()->getPrefix();
						}

						$mvars = array();
						$mvars['notif'] = $notif;
						$mvars['userPreferedLocale'] = $locale;

						$subject = $trans->trans('_mail.trainee.notif.timeCreditSB.subject', array(), null, $locale);

						$message = \Swift_Message::newInstance()->setFrom($from, $fromName)->setTo($trainee->getEmail(), $trainee->getFullname())->setSubject($subject)->setBody($templating->render('IlcfranceWorldspeakSharedResBundle:Mail:trainee.notif.timeCreditSB.html.twig', $mvars), 'text/html');

						$notif->setStatus(TraineeNotif::DONE);
						$em->persist($notif);
						$em->flush();
						$mailer->send($message);
						// $spool->flushQueue($transport);
						$traineeMailSent++;

						echo "Mail Notif TimeCredit SB Stagiaire " . $trainee->getFullname() . " (" . $trainee->getEmail() . ") pour le Credit de " . $notif->getTimeCredit()->getTotalHours() . " Heures " . "\n";
					} else {
						$logger->addNotice("Impossible d'envoyer un email de notification pour la notif stagiaire SB (Email NA)" . $trainee->getFullname() . ' ' . $notif->getId());
						$traineeMailNotSent++;
						$notif->setStatus(TraineeNotif::ERROR);
						$em->persist($notif);
						// $em->flush();
					}
				} catch (\Exception $e) {
					$logger->addNotice("Impossible d'envoyer un email de notification pour la notif stagiaire SB " . $trainee->getFullname() . ' ' . $notif->getId() . ' ' . $e->getMessage());
					$traineeMailNotSent++;
					$notif->setStatus(TraineeNotif::ERROR);
					$em->persist($notif);
					// $em->flush();
				}
			} elseif ($notif->getType() == TraineeNotif::TYPE_EMAIL_SURVEYEND && $notif->getStatus() == TraineeNotif::PENDING) {
				$trainee = $notif->getTrainee();
				try {
					if (null != $trainee->getEmail()) {
						if (null != $trainee->getPreferedLocale()) {
							$locale = $trainee->getPreferedLocale()->getPrefix();
						}

						$mvars = array();
						$mvars['notif'] = $notif;
						$mvars['userPreferedLocale'] = $locale;

						$subject = $trans->trans('_mail.trainee.notif.timeCreditSE.subject', array(), null, $locale);

						$message = \Swift_Message::newInstance()->setFrom($from, $fromName)->setTo($trainee->getEmail(), $trainee->getFullname())->setSubject($subject)->setBody($templating->render('IlcfranceWorldspeakSharedResBundle:Mail:trainee.notif.timeCreditSE.html.twig', $mvars), 'text/html');

						$notif->setStatus(TraineeNotif::DONE);
						$em->persist($notif);
						$em->flush();
						$mailer->send($message);
						// $spool->flushQueue($transport);
						$traineeMailSent++;

						echo "Mail Notif TimeCredit SE Stagiaire " . $trainee->getFullname() . " (" . $trainee->getEmail() . ") pour le Credit de " . $notif->getTimeCredit()->getTotalHours() . " Heures " . "\n";
					} else {
						$logger->addNotice("Impossible d'envoyer un email de notification pour la notif stagiaire SE (Email NA)" . $trainee->getFullname() . ' ' . $notif->getId());
						$traineeMailNotSent++;
						$notif->setStatus(TraineeNotif::ERROR);
						$em->persist($notif);
						// $em->flush();
					}
				} catch (\Exception $e) {
					$logger->addNotice("Impossible d'envoyer un email de notification pour la notif stagiaire SE " . $trainee->getFullname() . ' ' . $notif->getId() . ' ' . $e->getMessage());
					$traineeMailNotSent++;
					$notif->setStatus(TraineeNotif::ERROR);
					$em->persist($notif);
					// $em->flush();
				}
			}
		} // */

		$em->flush();

		$adminNotifs = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:AdminNotif')->getAll(false);
		foreach ($adminNotifs as $notif) {
			if ($notif->getStatus() == AdminNotif::PENDING) {
				$em->remove($notif);
			}
		}
		$em->flush();

		$teacherNotifs = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherNotif')->getAll(false);
		foreach ($teacherNotifs as $notif) {
			if ($notif->getStatus() == TeacherNotif::PENDING) {
				$em->remove($notif);
			}
		}
		$em->flush();

		$traineeNotifs = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TraineeNotif')->getAll(false);
		foreach ($traineeNotifs as $notif) {
			if ($notif->getStatus() == TraineeNotif::PENDING) {
				$em->remove($notif);
			}
		}
		$em->flush();

		$courses = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->findAll(false);
		foreach ($courses as $cours) {
			$em->refresh($cours);
		}

		$timeCredits = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->findAll(false);
		foreach ($timeCredits as $timeCredit) {
			$em->refresh($timeCredit);
		}

		$em->flush();

		$logger->addNotice('---------------------------------------------------------------------------------');
		$logger->addNotice(count($teacherNotifs) . ' Notifications Formateurs Trouvée');
		$logger->addNotice('---------------------------------------------------------------------------------');
		$logger->addNotice($teacherMailSent . ' Notifs formateur Envoyés');
		$logger->addNotice($teacherMailNotSent . ' Notifs formateur Non Envoyés');
		$logger->addNotice('---------------------------------------------------------------------------------');
		$logger->addNotice(count($traineeNotifs) . ' Notifications stagiaire Trouvée');
		$logger->addNotice('---------------------------------------------------------------------------------');
		$logger->addNotice($traineeMailSent . ' Notifs stagiaire Envoyés');
		$logger->addNotice($traineeMailNotSent . ' Notifs stagiaire Non Envoyés');
		$logger->addNotice('---------------------------------------------------------------------------------');
	}
}
