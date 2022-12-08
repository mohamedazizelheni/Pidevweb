<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Form\SearchType;
use App\Repository\AnnonceRepository;
use App\Repository\CategorieannonceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Class\Search;

#[Route('/')]
class AnnonceController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager=$entityManager;
    }

    public function listAction(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request)
    {
        $dql   = "SELECT a FROM AcmeMainBundle:Annonce a";
        $query = $em->createQuery($dql);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        // parameters to template
        return $this->render('Client/index.html.twig', ['pagination' => $pagination]);
    }

    #[Route('/', name: 'display_client', methods: ['GET'])]
    public function indexClinet(CategorieannonceRepository $categorieannonceRepository): Response
    {
        $u=$categorieannonceRepository->findAll();
        return $this->render('Client/homepage.html.twig',['u'=>$u]) ;



    }

    #[Route('/client/annonce', name: 'app_annonce_indexc', methods: ['GET'])]
    public function indexc(EntityManagerInterface $em,Request $request , PaginatorInterface $paginator): Response
    {$annonce = $this->entityManager->getRepository(Annonce::class)->findAll();
        $search= new Search();
        $form=$this->createForm(SearchType::class,$search);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $annonce = $this->entityManager->getRepository(Annonce::class)->findwithSearch($search);

        }


        $annonce = $paginator->paginate(
            $annonce, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 );/*limit per page*/
        return $this->render('Client/index.html.twig', [
            'annonces'=>$annonce,

            'form'=>$form->createView()
        ]);
    }




    #[Route('/client/annonce', name: 'app_annonce_indexJ', methods: ['GET'])]
    public function indexJ(EntityManagerInterface $entityManager): Response
    {
        $annonces = $entityManager
            ->getRepository(Annonce::class)
            ->findBy(['categorieannonce'=> '2']);

        return $this->render('Client/index.html.twig', [
            'annonces' => $annonces,
        ]);
    }




    #[Route('/admin/annonce', name: 'app_annonce_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $annonces = $entityManager
            ->getRepository(Annonce::class)
            ->findAll();

        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonces,
        ]);
    }
    /**
     * @Route("/admin", name="display_admin")
     */
    public function indexAdmin(): Response
    {

        return $this->render('Admin/index.html.twig') ;

    }
    #[Route('/newc', name: 'app_annonce_newc', methods: ['GET', 'POST'])]
    public function newc(Request $request, EntityManagerInterface $entityManager,AnnonceRepository $annonceRepository, CategorieannonceRepository $categorieannonceRepository,UserRepository $userRepository): Response
    {
        $annonce = new Annonce();

        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonceRepository->save($annonce,true);
            $this->addFlash('succes','Bien ajouter!');



            return $this->redirectToRoute('app_annonce_indexc', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Client/addannonce.html.twig', [
            'annonce' => $annonce,
            'form' => $form,

        ]);
    }

    #[Route('/new', name: 'app_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request , SluggerInterface $slugger, EntityManagerInterface $entityManager,AnnonceRepository $annonceRepository, CategorieannonceRepository $categorieannonceRepository,UserRepository $userRepository): Response
    {
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonceRepository->save($annonce,true);
            $this->addFlash('succes','Bien ajouter!');



            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{idannonce}', name: 'app_annonce_showc', methods: ['GET'])]
    public function showc(Annonce $annonce): Response
    {
        return $this->render('Client/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    #[Route('/{idannonce}', name: 'app_annonce_show', methods: ['GET'])]
    public function show(Annonce $annonce): Response
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }
    #[Route('/client/annonce/download', name: 'app_annonce_tel', methods: ['GET'])]
    public function DataDownload(){
        $pdfOptions =new Options();
        $pdfOptions->set('defaultFont','Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $dompdf->setHttpContext($context);
            $html = $this->renderView('Client/show.html.twig');

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $fichier = 'user-data-'. $this->getUser()->getId() .'.pdf';

        // On envoie le PDF au navigateur
        $dompdf->stream($fichier, [
            'Attachment' => true
        ]);

        return new Response();
    }


    #[Route('/{idannonce}/edit', name: 'app_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonce $annonce, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('succes','Bien Modifiée!');

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{idannonce}', name: 'app_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, Annonce $annonce, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getIdannonce(), $request->request->get('_token'))) {
            $entityManager->remove($annonce);
            $entityManager->flush();
            $this->addFlash('succes','Bien Supprimer!');
        }

        return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
    }








}
