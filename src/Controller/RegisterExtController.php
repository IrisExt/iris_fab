<?php

namespace App\Controller;

use App\Entity\TgAdrMail;
use App\Entity\TgHabilitation;
use App\Entity\TgPersonne;
use App\Entity\TrGenre;
use App\Entity\TrProfil;
use App\Form\UserExtRegistrationFormType;
use App\Entity\User;
use Doctrine\DBAL\DBALException;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use FOS\UserBundle\Model\UserManagerInterface;
/**
 * Class RegisterExt
 * @package App\Controller
 * Controller managing the registration.
 * @Route("register")
 */
class RegisterExtController extends BaseController
{
    private $eventDispatcher;
//    private $formFactory;
    private $userManager;

    public function __construct(EventDispatcherInterface $eventDispatcher, UserManagerInterface $userManager)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->userManager = $userManager;
//        $this->formFactory = $formFactory;
    }

    /**
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @return RedirectResponse|Response|null
     * @throws \Exception
     * @Route("/user-register" ,name="register_ext")
     */
    public function registerCustomuser(Request $request, Swift_Mailer $mailer)
    {

        $user = $this->userManager->createUser();
        $user->setEnabled(false);

        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }
        $form = $this->createForm(UserExtRegistrationFormType::class , $user);
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $event = new FormEvent($form, $request);
                $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $idGenre = $this->getEm()->getRepository(TrGenre::class)->find(4);
                $profil = $this->getEm()->getRepository(TrProfil::class)->find(15);
                $tgAdrMail = ($this->getEm()->getRepository(TgAdrMail::class)->findOneBy(['adrMail' => $form->get('email')->getData()]))?: null;

            try{
                $tgPersonne = new TgPersonne();
                $tgPersonne->setIdGenre($idGenre);
                $tgPersonne->setLbNomUsage($form->get('nom')->getData());
                $tgPersonne->setLbPrenom($form->get('prenom')->getData());

                $this->getEm()->persist($tgPersonne);

               if (!$tgAdrMail) {
                   $tgAdrMail = new TgAdrMail();
                   $tgAdrMail
                       ->setAdrMail($form->get('email')->getData())
                       ->setIdPersonne($tgPersonne);
                   $this->getEm()->persist($tgAdrMail);

                   $tgPersonne->addIdAdrMail($tgAdrMail);
               }

                $user->setConfirmationToken($this->generateToken(32));
//                $user->addRole('ROLE_PORTEUR_PROJET');

                $user->setIdPersonne($tgPersonne);
                $user->setUsername($form->get('email')->getData());
                $user->setUsernameCanonical(strtolower($form->get('email')->getData()));
                $this->userManager->updateUser($user);
                $tgHabiltation = new TgHabilitation();
                $tgHabiltation
                    ->setIdPersonne($tgPersonne)
                    ->setIdProfil($profil)
                    ->setBlSupprime(1)
                    ->setLbRespMaj($tgPersonne->getLbNomUsage() . ' ' . $tgPersonne->getIdPersonne())
                    ->setDhMaj(new \DateTime());
                $this->getEm()->persist($tgHabiltation);
            } catch (DBALException $e) {
				dd($e);
                $this->addFlash('error', "Impossible de vous enregistrer veuillez contacter l'administrateur");
                return $this->redirect($request->headers->get('referer'));
            };
                $this->getEm()->flush();

                if (null === $response = $event->getResponse()) {
                    $response = $this->redirectToRoute('confirm-mail', ['email' => $form->get('email')->getData()]);
                }

                $confirmation_email = 'http://' . $request->headers->get('host') . '/fr/register/confirme-email/' . $user->getConfirmationToken();
                $message = (new Swift_Message('Email de confirmation de création d\'un compte IRIS'))
                    ->setFrom(['noreplay@agencerecherche.fr' => 'ANR'])
                    ->setTo([$form->get('email')->getData()])
                    ->setBody(
                        $this->renderView(
                            'emails/registrationporteur.html.twig', ['confirmation_email' => $confirmation_email]),
                        'text/html');

                // Send the message
                $mailer->send($message);

                $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
                return $response;
            }

            $event = new FormEvent($form, $request);
            $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

//        try{

//        } catch (DBALException $e) {
//                $this->addFlash('error', "Impossible de vous enregistrer veuillez contacter l'administrateur");
//                return $this->redirect($request->headers->get('referer'));
//        };

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }

        return $this->render('bundles/FOSUserBundle/Registration/registerexterne.html.twig',
        [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param $email
     * @return Response
     * @Route("/confirme-mail/{email}" , name="confirm-mail")
     */
    public function confirmePageMail($email){
        return $this->render('emails/confirmepagemail.html.twig',['email' => $email]);
    }

    /**
     * @Route("/confirme-email/{token}" , name="confirm_token_email")
     */
    public function confirmationTokenEmail(string $token){
        $userToken = $this->getEm()->getRepository(User::class)->findOneBy(['confirmationToken' => $token]);

        if(!$userToken){
            throw new \Exception('Token no found', 404);
        };
        if($userToken && $userToken->isEnabled()){
            $message = 'Votre compte est déja activé !';
            return $this->render("emails/confirmationmail.html.twig",['message'=> $message]);
        };
        $userToken->setEnabled(true);
        $this->getEm()->persist($userToken);

        $this->getEm()->flush();
        $message ='Félicitations, votre compte est maintenant activé.';
        return $this->render("emails/confirmationmail.html.twig",['message'=> $message]);
    }


    /**
     * @return string
     * @throws \Exception
     */
    public function generateToken($nbr=32)
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    /**
     * @param string $token
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @return RedirectResponse|Response|null
     * @throws \Exception
     * @Route("/user-register-sc/{token}" ,name="register_sc")
     */
    public function registeRespSc(string $token, Request $request, Swift_Mailer $mailer)
    {
        $user = $this->getEm()->getRepository(User::class)->findOneBy(['confirmationToken' => $token]);

        if(!$user){
            throw new \Exception('Token no found', 404);
        };
        if($user && $user->isEnabled()){
            $message = 'Votre compte est déja activé !';
            return $this->render("emails/confirmationmail.html.twig",['message'=> $message]);
        };

        $mail = $user->getEmail();
        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }
        $form = $this->createForm(UserExtRegistrationFormType::class , $user);
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $event = new FormEvent($form, $request);
                $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                try{
                    $user->setUsername($this->after ('@', $mail));
                    $user->setUsernameCanonical(strtolower($this->after ('@', $mail)));
                    $user->setEmail($mail);
                    $user->setEmailCanonical(strtolower($mail));
                    $user->setEnabled(true);
                    $this->getEm()->persist($user);

                    $this->getEm()->flush();

                } catch (DBALException $e) {
                    $this->addFlash('error', "Impossible de vous enregistrer veuillez contacter l'administrateur");
                    return $this->redirect($request->headers->get('referer'));
                };
                $this->getEm()->flush();

                if (null === $response = $event->getResponse()) {
                    $response = $this->redirectToRoute('confirm-mail', ['email' => $mail]);
                }

                $confirmation_email = 'http://' . $request->headers->get('host') . '/fr/register/confirme-email/' . $user->getConfirmationToken();
                $message = (new Swift_Message('Email de confirmation IRIS'))
                    ->setFrom(['noreplay@agencerecherche.fr' => 'ANR'])
                    ->setTo([$mail])
                    ->setBody(
                        $this->renderView(
                            'emails/registrationporteur.html.twig', ['confirmation_email' => $confirmation_email]),
                        'text/html');

                // Send the message
                $mailer->send($message);

                $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
                return $response;
            }

            $event = new FormEvent($form, $request);
            $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }

        return $this->render('bundles/FOSUserBundle/Registration/registersc.html.twig',
            [
                'form' => $form->createView(),
                'nom' => $user->getUsername(),
                'token' => $token
            ]);
    }

    /**
     * @param $arobase
     * @param $inthat
     * @return false|string
     * retourne un subtr de @
     */
    private function after ($arobase, $email)
    {
        if (!is_bool(strpos($email, $arobase)))
            return substr($email, strpos($email,$arobase)+strlen($arobase));
    }

}
