<?php

namespace App\Controller;

use App\Entity\Categorieannonce;
use App\Form\CategorieannonceType;
use App\Repository\CategorieannonceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorieannonce')]
class CategorieannonceController extends AbstractController
{
    #[Route('/affc', name: 'app_categorieannonce_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categorieannonces = $entityManager
            ->getRepository(Categorieannonce::class)
            ->findAll();

        return $this->render('categorieannonce/index.html.twig', [
            'categorieannonces' => $categorieannonces,
        ]);
    }

    #[Route('/new', name: 'app_categorieannonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,UserRepository $userRepository,CategorieannonceRepository $categorieannonceRepository): Response
    {
        $categorieannonce = new Categorieannonce();
        $form = $this->createForm(CategorieannonceType::class, $categorieannonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorieannonce);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorieannonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorieannonce/new.html.twig', [
            'categorieannonce' => $categorieannonce,
            'form' => $form,
        ]);
    }

    #[Route('/{idc}', name: 'app_categorieannonce_show', methods: ['GET'])]
    public function show(Categorieannonce $categorieannonce): Response
    {
        return $this->render('categorieannonce/show.html.twig', [
            'categorieannonce' => $categorieannonce,
        ]);
    }

    #[Route('/{idc}/edit', name: 'app_categorieannonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorieannonce $categorieannonce, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieannonceType::class, $categorieannonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_categorieannonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorieannonce/edit.html.twig', [
            'categorieannonce' => $categorieannonce,
            'form' => $form,
        ]);
    }

    #[Route('/{idc}', name: 'app_categorieannonce_delete', methods: ['POST'])]
    public function delete(Request $request, Categorieannonce $categorieannonce, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieannonce->getIdc(), $request->request->get('_token'))) {
            $entityManager->remove($categorieannonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorieannonce_index', [], Response::HTTP_SEE_OTHER);
    }
}
