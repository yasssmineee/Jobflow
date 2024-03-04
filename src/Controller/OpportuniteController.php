<?php

namespace App\Controller;

use App\Entity\Opportunite;
use App\Entity\Postuler;
use App\Form\OpportuniteSearchType;
use App\Form\OpportuniteType;
use App\Form\PostulerType;
use App\Repository\OpportuniteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/opportunite')]
class OpportuniteController extends AbstractController
{
    #[Route('/', name: 'app_opportunite_index', methods: ['GET'])]
    public function index(Request $request, OpportuniteRepository $opportuniteRepository): Response
    {
        // Get the client's IP address from the request
// Get your local network IP address and concatenate the port
    
        // Generate the URL with the client's IP address
        // filter by opportunite name 
        $form = $this->createForm(OpportuniteSearchType::class);
        $form->handleRequest($request);
        $opportunites = $opportuniteRepository->findAll();
        $qrCodes = [];
        $localIpAddress = trim(shell_exec("ifconfig en0 | grep inet | grep -v inet6 | awk '{print $2}'")) .':8000';

    
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $opportunites = $opportuniteRepository->search($data['q'], $data['type'], $data['sort_by']);
        }
    
        $writer = new PngWriter();
    
        foreach ($opportunites as $opportunite) {
            $url = sprintf('http://%s%s', $localIpAddress, $this->generateUrl('app_opportunite_show', ['id' => $opportunite->getId()]));
    
            $qrCode = QrCode::create($url)
                ->setEncoding(new Encoding('UTF-8'))
                ->setSize(120)
                ->setMargin(0)
                ->setForegroundColor(new Color(0, 0, 0))
                ->setBackgroundColor(new Color(255, 255, 255));
            
            $qrCodes[$opportunite->getId()] = $writer->write(
                $qrCode,
                null,
                Label::create('')->setFont(new NotoSans(8))->setText($opportunite->getNom())
            )->getDataUri();
        }
    
        // get the current route name
        $routeName = $request->attributes->get('_route');
        
        return $this->render(
            'opportunite/index.html.twig',
            array(
                'form' => $form->createView(),
                'opportunites' => $opportunites,
                'q' => $data['q'] ?? null,
                'type' => $data['type'] ?? null,
                'sort_by' => $data['sort_by'] ?? 'name',
                'routeName' => $routeName,
                'qrCodes' => $qrCodes
            )
        );
    }
    

    #[Route('/new', name: 'app_opportunite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $opportunite = new Opportunite();
        $form = $this->createForm(OpportuniteType::class, $opportunite);
        $form->handleRequest($request);
        $opportunite->setIsFavorite(false);
        $opportunite->setUser($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($opportunite);
            $entityManager->flush();
            $this->addFlash('success', 'Created  successfully!');

            return $this->redirectToRoute('app_opportunite', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('opportunite/new.html.twig', [
            'opportunite' => $opportunite,
            'form' => $form,
        ]);
    }


    #[Route('/userOpp', name: 'app_opportunite')]
    public function userProjects(OpportuniteRepository $repository,Request $request): Response
    {
        $form = $this->createForm(OpportuniteSearchType::class);
        $form->handleRequest($request);
        $opportunites = $repository->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $opportunites = $repository->search($data['q'], $data['type'], $data['sort_by']);
        }
        
        $qrCodes = [];
        $localIpAddress = trim(shell_exec("ifconfig en0 | grep inet | grep -v inet6 | awk '{print $2}'")) .':8000';
        $opportunites = $repository->findBy(['user' => $this->getUser()]);
        // get the current route name
        $writer = new PngWriter();
    
        foreach ($opportunites as $opportunite) {
            $url = sprintf('http://%s%s', $localIpAddress, $this->generateUrl('app_opportunite_show', ['id' => $opportunite->getId()]));
    
            $qrCode = QrCode::create($url)
                ->setEncoding(new Encoding('UTF-8'))
                ->setSize(120)
                ->setMargin(0)
                ->setForegroundColor(new Color(0, 0, 0))
                ->setBackgroundColor(new Color(255, 255, 255));
            
            $qrCodes[$opportunite->getId()] = $writer->write(
                $qrCode,
                null,
                Label::create('')->setFont(new NotoSans(8))->setText($opportunite->getNom())
            )->getDataUri();
        }
    
        // get the current route name
        $routeName = $request->attributes->get('_route');
        
        return $this->render(
            'opportunite/index.html.twig',
            array(
                'form' => $form->createView(),
                'opportunites' => $opportunites,
                'q' => $data['q'] ?? null,
                // Add this line to pass the search parameters to the template
                'type' => $data['type'] ?? null,
                'sort_by' => $data['sort_by'] ?? 'name',
                'qrCodes' => $qrCodes

            )
        
        );
    }

    #[Route('/{id}', name: 'app_opportunite_show', methods: ['GET'])]
    public function show(Opportunite $opportunite): Response
    {
        return $this->render('opportunite/show.html.twig', [
            'opportunite' => $opportunite,
        ]);
    }


    #[Route('/new/{idOpportunite}', name: 'app_postuler_new', methods: ['GET', 'POST'])]
    public function postuler(Request $request, EntityManagerInterface $entityManager,ManagerRegistry $doctrine, SluggerInterface $slugger, $idOpportunite): Response
    {
        $postuler = new Postuler();
        $form = $this->createForm(PostulerType::class, $postuler);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('cv')->getData();
            // get params idOpportunite 





            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('project_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $postuler->setCv($newFilename);
            }
            $em = $doctrine->getManager();
            $entityManager->persist($postuler);
            $entityManager->flush();

            return $this->redirectToRoute('app_postuler_index', [], Response::HTTP_SEE_OTHER);
        }

   
            
        
        

        return $this->renderForm('postuler/new.html.twig', [
            'postuler' => $postuler,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_opportunite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Opportunite $opportunite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OpportuniteType::class, $opportunite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_opportunite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('opportunite/edit.html.twig', [
            'opportunite' => $opportunite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_opportunite_delete', methods: ['POST'])]
    public function delete(Request $request, Opportunite $opportunite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$opportunite->getId(), $request->request->get('_token'))) {
            $entityManager->remove($opportunite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_opportunite_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_opportunite_edit', methods: ['GET', 'POST'])]
    public function addToFavList(Request $request, Opportunite $opportunite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OpportuniteType::class, $opportunite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_opportunite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('opportunite/edit.html.twig', [
            'opportunite' => $opportunite,
            'form' => $form,
        ]);
    }
    
}