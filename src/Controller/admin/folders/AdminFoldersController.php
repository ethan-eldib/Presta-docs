<?php

namespace App\Controller\admin\folders;

use App\Entity\Folders;
use App\Entity\Documents;
use App\Form\FoldersType;
use App\Repository\DocumentsRepository;
use App\Repository\FoldersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminFoldersController extends AbstractController
{
    
    /**
     * Permet d'afficher la liste des dossiers déposés par les utilisateurs
     * 
     * @Route("/admin/dossiers", name="admin_folders_index", methods={"GET"})
     */
    public function index(FoldersRepository $foldersRepository): Response
    {
        return $this->render('admin/folders/index.html.twig', [
            'folders' => $foldersRepository->findAll()
        ]);
    }

    /**
     * Permet de voir le contenu d'un dossier
     * 
     * @Route("admin/document/{id}", name="admin_document_show", methods={"GET"})
     */
    public function show(Documents $documents, DocumentsRepository $documentsRepository): Response
    {
        return $this->render('admin/documents/show.html.twig', [
            'document' => $documents,
            'allDocuments' => $documentsRepository->findAll()
        ]);
    }

        /**
     * @Route("/{id}/modification", name="admin_documents_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Folders $folder): Response
    {
        $form = $this->createForm(FoldersType::class, $folder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On recupère les documents tranmis
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
                $folder->addDocument($doc);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('folders_index');
        }

        return $this->render('folders/edit.html.twig', [
            'folder' => $folder,
            'form' => $form->createView(),
        ]);
    }
}
