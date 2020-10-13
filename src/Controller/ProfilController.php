<?php

namespace App\Controller;

use App\Entity\TgAdrMail;
use App\Entity\TgAdrMailNotification;
use App\Entity\TgPersonne;
use App\Form\PersonneUpdateNameType;
use App\Form\TgAdrMailNotificationType;
use App\Form\TgAdrMailType;
use App\Service\DbEntityService;
use DateTime;
use DateTimeZone;
use Doctrine\DBAL\DBALException;
use FOS\UserBundle\Form\Type\ChangePasswordFormType;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class ProfilController.
 *
 * @Route("profile")
 */
class ProfilController extends BaseController
{
    private $userManager;
    /**
     * @var DbEntityService
     */
    private $dbEntity;
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * ProfilController constructor.
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EventDispatcherInterface $eventDispatcher, UserManagerInterface $userManager, DbEntityService $dbEntity, Swift_Mailer $mailer)
    {
        $this->userManager = $userManager;
        $this->dbEntity = $dbEntity;
        $this->mailer = $mailer;
        $this->eventDispatcher = $eventDispatcher;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     * @Route("/show", name="mon_profil")
     */
    public function showProfil(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $form = $this->createForm(ChangePasswordFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $oldPwd = $form->get('current_password')->getData();
            $newPwd = $form->get('plainPassword')->getData();
            $checkPass = $this->passwordEncoder->isPasswordValid($user, $oldPwd);

            if (true === $checkPass && $newPwd) {
                $user->setPassword($checkPass);
                $this->getEm()->persist($user);
                $this->getEm()->flush();
                $this->addFlash('success', 'Le mot de passe a bien modifié.');

                return $this->redirectToRoute('mon_profil');
            }
        }

        $Adrmails = $this->getEm()->getRepository(TgAdrMail::class)->findBy(['idPersonne' => $this->getUserConnect()]);
        $mailNotif = $this->getEm()->getRepository(TgAdrMailNotification::class)->findBy(['idPersonne' => $this->getUserConnect()]);

        $heureDiff72h = null;
        $heures = null;
        if (null !== $user->getIdentifiantDemande() && null !== $dateenvoi = $user->getDhEnvoiCode()) {
            $dateNow = new DateTime();
            $interval = $dateenvoi->diff($dateNow);
            $heureDiff72h = $interval->format('%R%d');
            $heures = $interval->days * 24 + $interval->h;

            if ($heureDiff72h >= 3) {
                $this->addFlash('infos', 'le délai d’activation de votre identifiant est dépassé (72 heures). Merci de bien vouloir recommencer la procédure de création d’identifiant’.');
            }
        }

        return $this->render('profilpersonne/showprofil.htm.twig', [
            'user' => $user,
            'diffDateEnvoi' => $heureDiff72h,
            'heure' => $heures,
            'mails' => $Adrmails,
            'mailNotifs' => $mailNotif,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/change-password", name="change_password_profil")
     */
    public function changePassword(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $form = $this->createForm(ChangePasswordFormType::class, $user);
        $form->handleRequest($request);

        return $this->render('profil/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return Response
     * @Route("/show/change-mail", name="change_mail_update")
     */
    public function changeEmailModal()
    {
        return $this->render('profil/modal/_form_change_mail.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @param UserManagerInterface $userManager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws \Exception
     * @Route("/changeMail", name="change_mail")
     */
    public function changeIdentifiant(Request $request)
    {
        $timezone = new DateTimeZone('Europe/Paris');
        $user = $this->getUser();
        if ($this->isCsrfTokenValid('change'.$this->getUser()->getIdPersonne(), $request->request->get('_token'))) {
            $newMail = $request->request->get('emailChange');
            $findEmailExiste = $this->userManager->findUserBy(['email' => $newMail]);
            $userExiste = $this->userManager->findUserBy(['username' => $newMail]);
            if ($findEmailExiste && $userExiste) {
                $this->addFlash('error', 'Cet identifiant existe déja !');

                return $this->redirectToRoute('mon_profil');
            }
            try {
                $user->setIdentifiantDemande($newMail);
                $user->setCdActivation($this->generateToken(8));
                $user->setDhenvoiCode(new \DateTime('now', $timezone));
                $this->getEm()->persist($user);
                $this->getEm()->flush();

                $activation_code = $user->getCdActivation();
                $message = (new Swift_Message('Changement d\'identifiant IRIS'))
                    ->setFrom(['noreplay@agencerecherche.fr' => 'ANR'])
                    ->setTo([$newMail])
                    ->setBody(
                        $this->renderView(
                            'emails/changeidentifiant.html.twig', ['activation_code' => $activation_code]),
                        'text/html');

                // Send the message
                $this->mailer->send($message);

                $this->addFlash('success', 'Un email est envoyé avec un code d\'activation ');

                return $this->redirectToRoute('mon_profil');
            } catch (DBALException $e) {
                $this->addFlash('error', 'Impossible de changer votre identifiant');

                return $this->redirect($request->headers->get('referer'));
            }
        }

        return $this->redirectToRoute('mon_profil');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/validateCode", name="validate_code")
     */
    public function activatEmailChange(Request $request)
    {
        if ($this->isCsrfTokenValid('change'.$this->getUser()->getIdPersonne(), $request->request->get('_token'))) {
            $user = $this->getUser();
            if (trim($request->request->get('_codeValidate')) === $user->getCdActivation()) {
                $personne = $this->getEm()->getRepository(TgPersonne::class)->find($user->getIdPersonne());
                $emailPrev = $user->getEmail();
                try {
                    $idDemande = $user->getIdentifiantDemande();
                    $user
                        ->setEmail($idDemande)
                        ->setEmailCanonical(strtolower($idDemande))
                        ->setUsername($idDemande)
                        ->setUsernameCanonical(strtolower($idDemande))
                        ->setCdActivation(null)
                        ->setIdentifiantDemande(null);
                    $this->getEm()->persist($user);

                    $this->dbEntity->dbAdrMail(new TgAdrMail(), $emailPrev, $personne, false, false, false);
                    $this->dbEntity->dbAdrMail(new TgAdrMail(), $idDemande, $personne, true, true, true);

                    return $this->redirectToRoute('fos_user_security_logout');
                } catch (DBALException $e) {
                    $this->addFlash('error', "Impossible d'activer l'identifiant, veuillez contacter l'administrateur");

                    return $this->redirect($request->headers->get('referer'));
                }
            } else {
                $this->addFlash('error', "Le code d'activation est incorrecte, veuillez réessayer svp.");
            }
        }

        return $this->redirectToRoute('mon_profil');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/mailAdd", name="add_mail")
     */
    public function addMail(Request $request)
    {
        $adrMail = new TgAdrMail();
        $form_mail = $this->createForm(TgAdrMailType::class, $adrMail);
        $form_mail->handleRequest($request);
        if ($form_mail->isSubmitted() && $form_mail->isValid()) {
            $mailUsers = $this->userManager->findUserByUsernameOrEmail($adrMail->getAdrMail());
            $mail = $this->getEm()->getRepository(TgAdrMail::class)->findOneBy(['adrMail' => $adrMail->getAdrMail()]);
            if ($mail || $mailUsers) {
                $this->addFlash('error', 'ce mail '.$adrMail->getAdrMail().' existe déja ! veuillez réessayer un autre. ');

                return $this->redirectToRoute('mon_profil');
            }
            $adrMail->setIdPersonne($this->getUserConnect());
            $this->getEm()->persist($adrMail);   // insert to TgAdrMail

            if (true === $form_mail->getData()->getBlNotification()) {
                $this->insertAdrMailNotif($request->request->get('tg_adr_mail')['adrMail'], $this->getUserConnect());
            }

            $this->getEm()->flush();

            $this->addFlash('success', 'Le mail a bien été ajouté.');

            return $this->redirectToRoute('mon_profil');
        }

        return $this->render('profil/modal/_form_add_mail.html.twig', [
            'form_mail' => $form_mail->createView(),
        ]);
    }

    /**
     * @param string $adrMail
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/update/{adrmail}" , name="update_mail")
     */
    public function updateAdrMail(Request $request, string $adrmail)
    {
        $tgadrmail = $this->getEm()->getRepository(TgAdrMail::class)->find($adrmail);
        $form = $this->createForm(TgAdrMailType::class, $tgadrmail);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $oldMail = $request->request->get('old_mail');
                $tlPersMailNotif = $this->getEm()->getRepository(TgAdrMailNotification::class)->findOneBy(
                    ['idPersonne' => $this->getUserConnect(), 'adrMailNotif' => $oldMail]);
                $emailTlAdrold = ($tlPersMailNotif) ? $tlPersMailNotif->getAdrMailNotif() : null;

                $this->getEm()->persist($tgadrmail);

                $tlPersMailNotifNv = $this->getEm()->getRepository(TgAdrMailNotification::class)->findOneBy(
                    ['idPersonne' => $this->getUserConnect(), 'adrMailNotif' => $tgadrmail->getAdrMail()]);

                if ($tgadrmail->getAdrMail() != $emailTlAdrold) {
                    if (null !== $emailTlAdrold) {
                        if (false == $tgadrmail->getBlNotification()) {
                            $this->getEm()->remove($tlPersMailNotif); // supprimer l'ancienne adresse
                        } else {
                            $this->getEm()->remove($tlPersMailNotif);
                            $this->insertAdrMailNotif($tgadrmail->getAdrMail(), $this->getUserConnect()); // ajouter la nouvelle
                        }
                    } elseif (null == $emailTlAdrold && true == $tgadrmail->getBlNotification()) {
                        $this->insertAdrMailNotif($tgadrmail->getAdrMail(), $this->getUserConnect()); // ajouter la nouvelle
                    }
                } else {
                    if (true == $tgadrmail->getBlNotification() && !$tlPersMailNotifNv) {
                        $this->insertAdrMailNotif($tgadrmail->getAdrMail(), $this->getUserConnect());
                    } elseif (false == $tgadrmail->getBlNotification() && $tlPersMailNotifNv) {
                        $this->getEm()->remove($tlPersMailNotifNv);
                    }
                }
                $this->getEm()->flush();
            } catch (DBALException $e) {
                $this->addFlash('error', "impossible d'ajouter ce mail!");

                return $this->redirectToRoute('mon_profil');
            }

            $this->addFlash('success', 'le mail à bien été changé .');

            return $this->redirectToRoute('mon_profil');
        }

        return $this->render('profil/modal/_form_update.html.twig', [
            'adrmail' => $tgadrmail,
            'form_mail' => $form->createView(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     * @Route("/delete/{adrmail}" , name="delete_mail")
     */
    public function deleteAdrMail(Request $request, string $adrmail)
    {
        $tgadrMail = $this->getEm()->getRepository(TgAdrMail::class)->find($adrmail);
        if (!$tgadrMail) {
            throw new \Exception('Cet mail n\'existe pas', 404);
        }

        if ($this->isCsrfTokenValid('delete'.$tgadrMail->getAdrMail(), $request->request->get('_token'))) {
            $tlMailNotif = $this->getEm()->getRepository(TgAdrMailNotification::class)->findOneBy(['idPersonne' => $this->getUserConnect(), 'adrMailNotif' => $tgadrMail->getAdrMail()]);
            if ($tlMailNotif) {
                $this->getEm()->remove($tlMailNotif);
            }
            $this->getEm()->remove($tgadrMail);
            $this->getEm()->flush();

            $this->addFlash('success', 'le mail à bien été supprimé.');

            return $this->redirectToRoute('mon_profil');
        }

        return $this->render('profil/modal/_from_delete.html.twig', [
            'adrMail' => $tgadrMail,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/add/mail-notif", name="add_mail_notif")
     */
    public function addMailNotif(Request $request)
    {
        $adrMailNotif = new TgAdrMailNotification();
        $form_notif = $this->createForm(TgAdrMailNotificationType::class, $adrMailNotif);
        $form_notif->handleRequest($request);
        if ($form_notif->isSubmitted() && $form_notif->isValid()) {
            /* verification du mail de notif et le mail login  */
            if ($this->mailNotifEqualMailLogin(strtolower($adrMailNotif->getAdrMailNotif()))) {
                $adrMailNotifExist = $this->getEm()->getRepository(TgAdrMailNotification::class)->findOneBy(['idPersonne' => $this->getUserConnect(), 'adrMailNotif' => $adrMailNotif->getAdrMailNotif()]);
                $tgAdrMail = $this->getEm()->getRepository(TgAdrMail::class)->findOneBy(['idPersonne' => $this->getUserConnect(), 'adrMail' => $adrMailNotif->getAdrMailNotif()]);
                if ($adrMailNotifExist) {
                    $this->addFlash('infos', 'Ce mail existe déja dans votre liste ! ');

                    return $this->redirectToRoute('mon_profil');
                }
                if ($tgAdrMail) {
                    $tgAdrMail->setBlNotification(true);
                    $this->getEm()->persist($tgAdrMail);
                }
                $adrMailNotif->setIdPersonne($this->getUserConnect());
                $this->getEm()->persist($adrMailNotif);
                $this->getEm()->flush();
                $this->addFlash('success', 'un mail de notification à bien été ajouté.');
            }

            return $this->redirectToRoute('mon_profil');
        }

        return $this->render('profil/modal/_form_add_mail_notif.html.twig', [
            'form_notif' => $form_notif->createView(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/update/mailNotif/{mailNotification}", name="update_mail_notif")
     */
    public function updateMailNotif(Request $request, string $mailNotification)
    {
        $tgMailNotif = $this->getEm()->getRepository(TgAdrMailNotification::class)
            ->findOneBy(['idPersonne' => $this->getUserConnect(), 'adrMailNotif' => $mailNotification]);
        if (!$tgMailNotif) {
            throw $this->createNotFoundException('Aucun mail trouvé');
        }
        $form = $this->createForm(TgAdrMailNotificationType::class, $tgMailNotif);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->mailNotifEqualMailLogin(strtolower($tgMailNotif->getAdrMailNotif()))) {
                try {
                    $tgadrMail = $this->getEm()->getRepository(TgAdrMail::class)
                        ->findOneBy(['idPersonne' => $this->getUserConnect(), 'adrMail' => $request->request->get('old_mail_notif')]);

                    if ($tgadrMail && true == $tgadrMail->getBlNotification() && $tgMailNotif->getAdrMailNotif() !== $request->request->get('old_mail_notif')) {
                        $tgadrMail->setBlNotification(false);
                        $this->getEm()->persist($tgadrMail);
                    }

                    $this->getEm()->persist($tgMailNotif);
                    $this->getEm()->flush();
                } catch (DBALException $e) {
                    $this->addFlash('error', 'impossible de modifier ce mail !');

                    return $this->redirectToRoute('mon_profil');
                }
                $this->addFlash('success', 'un mail de notification à bien été modifié .');
            }

            return $this->redirectToRoute('mon_profil');
        }

        return $this->render('profil/modal/_form_update_mail_notif.html.twig', [
            'form_notif' => $form->createView(),
            'mailNotif' => $mailNotification,
        ]);
    }

    /**
     * @param TgAdrMailNotification $mailNotification
     *
     * @return Response
     *
     * @throws \Exception
     * @Route("/delete/mailNotif/{mailNotification}", name="delete_mail_notif")
     */
    public function deleteMailNotif(Request $request, string $mailNotification)
    {
        $tgMailNotif = $this->getEm()->getRepository(TgAdrMailNotification::class)->findOneBy(['idPersonne' => $this->getUserConnect(), 'adrMailNotif' => $mailNotification]);
        $tgMailPerso = $this->getEm()->getRepository(TgAdrMail::class)->findOneBy(['idPersonne' => $this->getUserConnect(), 'adrMail' => $mailNotification]);
        if (!$tgMailNotif) {
            throw new \Exception('Ce mail n\'existe pas', 404);
        }
        if ($this->isCsrfTokenValid('delete'.$mailNotification, $request->request->get('_token'))) {
            $this->getEm()->remove($tgMailNotif);
            if ($tgMailPerso) {
                $tgMailPerso->setBlNotification(false);
                $this->getEm()->persist($tgMailPerso);
            }
            $this->getEm()->flush();
            $this->addFlash('success', 'le mail à bien été supprimé.');

            return $this->redirectToRoute('mon_profil');
        }

        return $this->render('profil/modal/_from_delete_mailNotif.html.twig', [
            'adrMail' => $tgMailNotif->getAdrMailNotif(),
        ]);
    }

    public function mailNotifEqualMailLogin($mail): bool
    {
        $verif = $this->getUser()->getEmailCanonical() != $mail;
        $verif ?: $this->addFlash('infos', "Impossible d'ajouter votre mail de login !.");

        return $verif;
    }

    /**
     * @return Response
     * @Route("/personne/update/name", name="update_name")
     */
    public function updateName(Request $request)
    {
        $personne = $this->getUserConnect();
        $form = $this->createForm(PersonneUpdateNameType::class, $personne);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->getEm()->persist($personne);
            $this->getEm()->flush();
            $this->addFlash('success', 'Nom, prénom ont été mises à jour .');

            return $this->redirectToRoute('mon_profil');
        }

        return $this->render('profil/modal/_from_update_name.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function insertAdrMailNotif($adrMail, $personne)
    {
        $adrNotif = $this->getEm()->getRepository(TgAdrMailNotification::class)->findOneBy(['adrMailNotif' => $adrMail, 'idPersonne' => $personne]);
        $tgAdrMailNotf = ($adrNotif) ? $adrNotif : new TgAdrMailNotification(); // search mail in notif

        $tgAdrMailNotf->setAdrMailNotif($adrMail);
        $tgAdrMailNotf->setIdPersonne($personne);
        $this->getEm()->persist($tgAdrMailNotf); // insert to TgAdrMailNotif
    }
}
