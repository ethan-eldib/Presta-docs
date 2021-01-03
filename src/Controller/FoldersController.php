<?php

namespace App\Controller;

use App\Entity\Documents;
use App\Entity\Folders;
use App\Form\FoldersType;
use App\Repository\FoldersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dossier")
 */
class FoldersController extends AbstractController
{
    /**
     * Affiche le formulaire pour déposer des documents 
     * 
     * @Route("/dépôt-document", name="folders_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $folder = new Folders();
        $user = $this->getUser();
        $form = $this->createForm(FoldersType::class, $folder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On recupère les documents transmis
            $documents = $form->get('folders')->getData();

            // On boucle sur les documents
            foreach ($documents as $document) {
                // On genere un nouveau nom de fichier
                $file = md5(uniqid()) . '.' . $document->guessExtension();

                // On copie le fichier dans le dossier uploads
                $document->move(
                    $this->getParameter('documents_directory'),
                    $file
                );

                // On stocke le ou les documents dans la BDD (son nom)
                $doc = new Documents();
                $doc->setName($file);
                $doc->setUser($user);
                $folder->addDocument($doc);
            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($folder);
            $entityManager->flush();

            // return $this->redirectToRoute('folders_index');
            $this->addFlash(
                'success',
                'Document transmit avec succès'
            );
        }

        return $this->render('folders/new.html.twig', [
            'folder' => $folder,
            'form' => $form->createView(),
        ]);
    }

    // /**
    //  * @Route("/{id}", name="folders_show", methods={"GET"})
    //  */
    // public function show(Folders $folder): Response
    // {
    //     return $this->render('folders/show.html.twig', [
    //         'folder' => $folder,
    //     ]);
    // }

    /**
     * @Route("/{id}/edit", name="folders_edit", methods={"GET","POST"})
     */
    // public function edit(Request $request, Folders $folder): Response
    // {
    //     $form = $this->createForm(FoldersType::class, $folder);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         // On recupère les documents tranmis
    //         $documents = $form->get('folders')->getData();

    //         // On boucle sur les documents
    //         foreach ($documents as $document) {
    //             // On genere un nouveau nom de fichier
    //             $file = md5(uniqid()) . '.' . $document->guessExtension();

    //             // On copie le fichier dans le dossier uploads
    //             $document->move(
    //                 $this->getParameter('documents_directory'),
    //                 $file
    //             );

    //             // On stocke le ou les documents dans la BDD (son nom)
    //             $doc = new Documents();
    //             $doc->setName($file);
    //             $folder->addDocument($doc);
    //         }

    //         $this->getDoctrine()->getManager()->flush();

    //         return $this->redirectToRoute('folders_index');
    //     }

    //     return $this->render('folders/edit.html.twig', [
    //         'folder' => $folder,
    //         'form' => $form->createView(),
    //     ]);
    // }

    /**
     * @Route("/{id}", name="folders_delete", methods={"DELETE"})
     */
    // public function delete(Request $request, Folders $folder): Response
    // {
    //     if ($this->isCsrfTokenValid('delete' . $folder->getId(), $request->request->get('_token'))) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->remove($folder);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('folders_index');
    // }

    /**
     * @Route("/suppression/document/{id}", name="delete_document", methods={"DELETE"})
     */
    // public function deleteDocument(Documents $document, Request $request)
    // {
    //     $data = json_decode($request->getContent(), true);

    //     // On vérifie si le Token est valide
    //     if ($this->isCsrfTokenValid('delete'.$document->getId(), $data['_token'])) {
    //         // On récupère le nom du document
    //         $name = $document->getName();
    //         // On supprime le document
    //         unlink($this->getParameter('documents_directory').'/'.$name);

    //         //  On supprime l'entrée de la base
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->remove($document);
    //         $entityManager->flush();

    //         // On répond en json
    //         return new JsonResponse(['success' => 1]);
    //     }else {
    //         return new JsonResponse(['error' => 'token invalide'], 400);
    //     }
    // }
}
