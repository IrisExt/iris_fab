<?php

namespace App\Controller;

use App\Entity\TgParticipation;
use App\Form\ParticipationType;
use App\Repository\ParticipationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("particip")
 */
class ParticipationController extends AbstractController
{
    /**
     * @Route("/", name="tg_participation_index", methods={"GET"})
     */
    public function index(ParticipationRepository $participationRepository): Response
    {
        return $this->render('tg_participation/index.html.twig', [
            'tg_participations' => $participationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tg_participation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tgParticipation = new TgParticipation();
        $form = $this->createForm(ParticipationType::class, $tgParticipation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tgParticipation);
            $entityManager->flush();

            return $this->redirectToRoute('tg_participation_index');
        }

        return $this->render('tg_participation/new.html.twig', [
            'tg_participation' => $tgParticipation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idParticipation}", name="tg_participation_show", methods={"GET"})
     */
    public function show(TgParticipation $tgParticipation): Response
    {
        return $this->render('tg_participation/show.html.twig', [
            'tg_participation' => $tgParticipation,
        ]);
    }

    /**
     * @Route("/{idParticipation}/edit", name="tg_participation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TgParticipation $tgParticipation): Response
    {
        $form = $this->createForm(ParticipationType::class, $tgParticipation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tg_participation_index', [
                'idParticipation' => $tgParticipation->getIdParticipation(),
            ]);
        }

        return $this->render('tg_participation/edit.html.twig', [
            'tg_participation' => $tgParticipation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idParticipation}", name="tg_participation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TgParticipation $tgParticipation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tgParticipation->getIdParticipation(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tgParticipation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tg_participation_index');
    }

    /**
     * @Route("/mbr-comite/{idComite}", name="membre_comite")
     */
    public function membresparticipant(Request $request,  $idComite)
    {
        $mmbreComite = $this->getDoctrine()->getManager()->getRepository(TgParticipation::class)->findPartParComite($idComite);

        return $this->render('comite/gestioncomite/modal/listemembremodal.html.twig',
            [
                'mmbreComite' => $mmbreComite
            ]);

    }

    public function UserParticipant($idComite, $user)
    {
        $this->getDoctrine()->getManager()->getRepository(TgParticipation::class)->userParticipantComite($idComite , $user);

    }
}
