<?php

namespace App\Controller;

use App\Entity\Commentaires;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommentairesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class CommentairesController extends AbstractController
{
    #[Route('/commentaires', name: 'app_commentaires')]
    public function index(): Response
    {
        return $this->render('commentaires/index.html.twig', [
            'controller_name' => 'CommentairesController',
        ]);
    }
    #[Route('/delete/comment/{id}', name: 'delete_comment', methods: ['GET', 'DELETE'])]
    public function delete($id, EntityManagerInterface $entityManager, Commentaires $commentarie): Response {
        
        $entityManager->remove($commentarie);
        $entityManager->flush();

        return $this->redirectToRoute('homepage');
    } 
}
