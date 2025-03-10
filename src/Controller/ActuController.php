<?php

namespace App\Controller;

use App\Entity\Actu;
use App\Form\ActuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/actu')]
final class ActuController extends AbstractController
{
    #[Route('/new', name: 'app_actu_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $actu = new Actu();
        $form = $this->createForm(ActuType::class, $actu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($actu);
            $entityManager->flush();

            return $this->redirectToRoute('app_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('actu/new.html.twig', [
            'actu' => $actu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_actu_show', methods: ['GET'])]
    public function show(Actu $actu): Response
    {
        return $this->render('actu/show.html.twig', [
            'actu' => $actu,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_actu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Actu $actu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActuType::class, $actu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_actu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('actu/edit.html.twig', [
            'actu' => $actu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_actu_delete', methods: ['POST'])]
    public function delete(Request $request, Actu $actu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$actu->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($actu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_actu_index', [], Response::HTTP_SEE_OTHER);
    }
}
