<?php

namespace App\Controller\admin\folders;

use App\Entity\Documents;
use App\Entity\Folders;
use App\Repository\FoldersRepository;
use App\Repository\DocumentsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminFoldersController extends AbstractController
{
    
    /**
     * Permet d'afficher la liste des dossiers déposés par les utilisateurs
     * 
     * @Route("/admin/dossiers", name="admin_folders_index", methods={"GET"})
     * 
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
     * @Route("/admin/document/{id}", name="admin_document_show", methods={"GET"})
     * 
     */
    public function show(FoldersRepository $foldersRepository ,DocumentsRepository $documentsRepository, $id): Response
    {
        return $this->render('admin/document/show.html.twig', [
            'documents' => $documentsRepository->findBy(['folders' => $id ]),
            'folders' => $foldersRepository->findBy(['id' => $id ])
        ]);
    }
    

}
