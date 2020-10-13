<?php

namespace App\Controller;

use App\Entity\TgComite;
use App\Entity\TgHabilitation;
use App\Entity\TgMessage;
use App\Entity\TgParticipation;
use App\Entity\TgPersonne;
use App\Form\TgMessageType;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/message")
 */
class MessageController extends BaseController
{
    /**
     * @Route("/", name="tg_message_index", methods={"GET"})
     */
    public function index(MessageRepository $messageRepository): Response
    {
        return $this->render('tg_message/index.html.twig', [
            'tg_messages' => $messageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tg_message_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tgMessage = new TgMessage();
        $form = $this->createForm(TgMessageType::class, $tgMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tgMessage);
            $entityManager->flush();

            return $this->redirectToRoute('tg_message_index');
        }

        return $this->render('tg_message/new.html.twig', [
            'tg_message' => $tgMessage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idMessage}", name="tg_message_show", methods={"GET"})
     */
    public function show(TgMessage $tgMessage): Response
    {
        return $this->render('tg_message/show.html.twig', [
            'tg_message' => $tgMessage,
        ]);
    }

    /**
     * @Route("/{idMessage}/edit", name="tg_message_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TgMessage $tgMessage): Response
    {
        $form = $this->createForm(TgMessageType::class, $tgMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tg_message_index', [
                'idMessage' => $tgMessage->getIdMessage(),
            ]);
        }

        return $this->render('tg_message/edit.html.twig', [
            'tg_message' => $tgMessage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idMessage}", name="tg_message_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TgMessage $tgMessage): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tgMessage->getIdMessage(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tgMessage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tg_message_index');
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/messages/m")
     */
    public function consultMessagePers(Request $request)
    {
        $personne = $this->getUserConnect();
        $message = $this->getDoctrine()->getManager()->getRepository(TgMessage::class)->findBy(['destinataire' => $personne], ['idMessage' => 'DESC'], 3);
        $cont = count($message);

        return $this->render('tg_message/messagenotipers.html.twig', [
            'messages' => $message
        ]);
    }

    /**
     * @param $comite
     * @return Response
     * retourne les 2 messages du comité
     */
    public function consultMessageComite($comite): Response
    {
        $message = $this->getDoctrine()->getManager()->getRepository(TgMessage::class)->findMessagePersHabi($comite);

        return $this->render('tg_message/messagePers.html.twig', [
            'messages' => $message
        ]);
    }

    /**
     * @param $idParticipation
     * @return Response
     * Constitution CES Messages membres
     */
    public function consultMessageConstitutionCes($idParticipation)
    {
        $messages = $this->getDoctrine()->getManager()->getRepository(TgMessage::class)->findMessagePartici($idParticipation);

        return $this->render('constitutionces/messageparticipant.html.twig',
            [
                'messages' => $messages

            ]);
    }

    /**
     * message depuis la liste comité
     * @Route("/newmessagecomite/{idComite}/{idParticipation}", name="new_message_comite")
     */
    public function newmessagecomite(Request $request, TgComite $comite, $idParticipation = null): Response
    {
        $tgMessage = new TgMessage();


        $form = $this->createForm(TgMessageType::class, $tgMessage,
            [
                'participant' => $idParticipation,
                'personne' => $this->getUserConnect(),
                'comiteObject' => $comite,
                'appel' =>  $request->getSession()->get('appel')
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $tgMessage->setDhEnvoi(new \DateTime());
            $tgMessage->setIdComite($comite);
            $entityManager->persist($tgMessage);
            $entityManager->flush();

            if(null == $idParticipation){
                $this->addFlash('success', 'note enregistré'); // un message flash pour confirmer l'envoi du message
                return $this->redirect($request->headers->get('referer'));
//                return $this->redirect(parse_url($request->headers->get('referer'), PHP_URL_PATH));
            }else{
                $this->addFlash('success', 'Le message a bien été transmis'); // un message flash pour confirmer l'envoi du message
                return $this->redirect($request->headers->get('referer'));
            }

        }
        if(!empty($idParticipation)){
            return $this->render('tg_message/modal/ajoutmessagparticip.html.twig',[
                'tg_message' => $tgMessage,
                'comite' => $comite,
                'participant' => $idParticipation,
                'form' => $form->createView()
            ]);
        }else{
            return $this->render('tg_message/modal/ajoutermessage.html.twig', [
                'personne' => $this->getUserConnect(),
                'tg_message' => $tgMessage,
                'comite' => $comite,
                'form' => $form->createView(),]);
        }
    }


}
