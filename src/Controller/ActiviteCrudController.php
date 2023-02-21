<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;


#[Route('/activite/crud')]
class ActiviteCrudController extends AbstractController
{
    #[Route('/', name: 'app_activite_crud_index', methods: ['GET'])]
    public function index(Request $request, ActiviteRepository $activiteRepository): Response
    {
        return $this->render('activite_crud/index.html.twig', [
            'activites' => $activiteRepository->findAll(),
        ]);
    }

    #[Route('/viewActivite', name: 'app_activite_crud_index_front', methods: ['GET'])]
    public function viewActivite(ActiviteRepository $activiteRepository): Response
    {
        return $this->render('activite_crud/view.html.twig', [
            'activites' => $activiteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_activite_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ActiviteRepository $activiteRepository): Response
    {
        $activite = new Activite();
        $filesystem = new Filesystem();
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activiteRepository->save($activite, true);

            $uploadedFile = $form->get('imageActivite')->getData();
            $formData =  $uploadedFile->getPathname();
            $sourcePath = strval($formData);
            $destinationPath = 'uploads/photo'.$activite->getNomActivite().strval($activite->getId()).'.png';
            $activite->setImageActivite($destinationPath);
            $filesystem->copy($sourcePath, $destinationPath);
            $activiteRepository->save($activite, true);


            return $this->redirectToRoute('app_activite_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activite_crud/new.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_activite_crud_show', methods: ['GET'])]
    public function show(Activite $activite): Response
    {
        return $this->render('activite_crud/show.html.twig', [
            'activite' => $activite,
        ]);
    }

    #[Route('/show/{id}', name: 'app_activite_crud_show_front', methods: ['GET'])]
    public function showActivite(Activite $activite): Response
    {
        return $this->render('activite_crud/showFront.html.twig', [
            'activite' => $activite,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_activite_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Activite $activite, ActiviteRepository $activiteRepository): Response
    {
        $filesystem = new Filesystem();
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activiteRepository->save($activite, true);

            
            $uploadedFile = $form->get('imageActivite')->getData();
            $formData =  $uploadedFile->getPathname();
            $sourcePath = strval($formData);
            $destinationPath = 'uploads/photo'.$activite->getNomActivite().strval($activite->getId()).'.png';
            $activite->setImageActivite($destinationPath);
            $filesystem->copy($sourcePath, $destinationPath);
            $activiteRepository->save($activite, true);

            return $this->redirectToRoute('app_activite_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activite_crud/edit.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_activite_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Activite $activite, ActiviteRepository $activiteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activite->getId(), $request->request->get('_token'))) {
            $activiteRepository->remove($activite, true);
        }

        return $this->redirectToRoute('app_activite_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
