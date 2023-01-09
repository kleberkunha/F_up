<?php
namespace App\Controller;

use App\Entity\Commentaires;
use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductsRepository;
use App\Repository\CommentairesRepository;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Form\Extension\Core\DataTransformer\UlidToStringTransformer;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Egulias\EmailValidator\Warning\Comment;
use Twig\Node\SetNode;

class ProductsController extends AbstractController
{
/*     #[Route('/products/edit/{id}', name: 'edit_comment')]
    public function edit($id): Response{
        dd($id);
        exit;
    } */

    #[Route('/products/{id}/commentaire/new', name: 'commentaires_new', methods: ['POST'])]
    public function newCommentaire($id,EntityManagerInterface $entityManager, CommentairesRepository $commentRepo, ProductsRepository $productRepo): Response
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $comment_email = $_POST['comment_email'];
            $person_nickname = $_POST['person_name'];
            $product_note = $_POST['product_note'];
            $comment_value = $_POST['comment_text'];

            $product = $productRepo->find($id);
    
            $comment = new Commentaires;
    
            $comment->setEmail($comment_email)
            ->setPersonName($person_nickname)
            ->setCommentDescription($comment_value)
            ->setNotes($product_note)
            ->setProducts($product);
    
            $entityManager->persist($comment);
            
            $entityManager->flush();
    
            return $this->redirectToRoute('show_product', ['id'=>$id]);
        }
        return $this->render('products/show.html.twig');
    }

/*     #[Route('/products/{id}/commentNote', name: 'commentNote', methods: ['GET'])]
    public function sortByNote($id,Commentaires $comment, Request $request): Response
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET"){
            $formValue = $_GET['commentNote'];

            $notes = $comment->getNotes($formValue);

            return $this->render('products/show.html.twig', [
                'result' => $notes,
            ]);
        }
        return $this->render('products/show.html.twig');
    } */

    #[Route('/products/{id}', methods: ['GET','POST'], name: 'show_product')]
    public function show(Products $product, Request $request, CommentairesRepository $commentRepo): Response
    {
        $count = $product->getCommentaireId()->count();
        $comments = $product->getCommentaireId();

        return $this->render('products/show.html.twig', [
            'product' => $product,
            'comments' => $comments,
            'count' => $count,
        ]);
    }

    #[Route('/new', name: 'new_products', methods: ['GET','POST'])]
    public function new(EntityManagerInterface $entityManager,ProductsRepository $productRepo, Request $request): Response {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // collect value of input field
            $prod_name = $_POST['product_name'];
            $prod_desc = $_POST['description'];
            $prod_image = $_POST['fileToUpload'];

            $product = new Products;
        
            $product->setName($prod_name)
            ->setDescription($prod_desc)
            ->setImage($prod_image, FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ]);
            
            $entityManager->persist($product);
        
            $entityManager->flush();

            return $this->redirectToRoute('homepage');
        }
        return $this->render('products/new.html.twig');
    }

    #[Route('/delete/{id}', name: 'delete_product', methods: ['GET', 'DELETE'])]
    public function delete($id, Products $product, EntityManagerInterface $entityManager,ProductsRepository $productRepo): Response {
        $entityManager->remove($product);
        $entityManager->flush();
        return $this->redirectToRoute('homepage');
    }
}
