<?php

namespace App\Controller;

use App\Entity\FunFact;
use App\Form\FunFactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/fun/fact')]
final class FunFactController extends AbstractController
{
    #[Route('/new', name: 'app_fun_fact_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $funFact = new FunFact();
        $form = $this->createForm(FunFactType::class, $funFact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($funFact);
            $entityManager->flush();

            return $this->redirectToRoute('app_fun_fact_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fun_fact/new.html.twig', [
            'fun_fact' => $funFact,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fun_fact_show', methods: ['GET'])]
    public function show(FunFact $funFact): Response
    {
        return $this->render('fun_fact/show.html.twig', [
            'fun_fact' => $funFact,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fun_fact_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FunFact $funFact, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FunFactType::class, $funFact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fun_fact_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fun_fact/edit.html.twig', [
            'fun_fact' => $funFact,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fun_fact_delete', methods: ['POST'])]
    public function delete(Request $request, FunFact $funFact, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$funFact->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($funFact);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fun_fact_index', [], Response::HTTP_SEE_OTHER);
    }
}
