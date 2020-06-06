<?php

namespace App\Controller;

use App\Entity\Commentaire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ActualiteRepository;
use App\Repository\CommentaireRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class CommentaireController extends AbstractController
{
   /**
    * @var CommentaireRepository
    */
    private $commentaireRepository;

/**
 *@var ActualiteRepository
 */

private $actualiteRepository;

/**
 * @var EntityManagerInterface
 */
private $entityManager;

public function __construct(ActualiteRepository $actualiteRepository,CommentaireRepository $commentaireRepository,EntityManagerInterface $entityManager)
{
    
    $this->entityManager=$entityManager;
    $this->commentaireRepository=$commentaireRepository;
    $this->actualiteRepository=$actualiteRepository;
}

public function showComments(){
    $comments=$this->commentaireRepository->findAll();
    return $this->json($comments,200);
}

public function showCommentsByActualite($idactualite){
    $comments=$this->commentaireRepository->findBy(['actualite'=>$idactualite]);
    return $this->json($comments,200);
}

public function createComment(Request $request , $idactualite){
    $comment = new Commentaire();
    $comment->setContenu($request->request->get("contenu"));
    $comment->setDateCreation(new DateTime());
    $actualite= $this->actualiteRepository->find($idactualite);
    $this->entityManager->persist($comment);
    $actualite->addComment($comment);
    $this->entityManager->flush($comment);
    return $this->json($comment,201);
}

public function deleteComment($idcomment){
    $comment=$this->commentaireRepository->find($idcomment);
    $actualite=$comment->getActualite();
    $actualite->removeComment($comment);
    $this->entityManager->remove($actualite);
    $this->entityManager->flush();
    return $this->json(['message'=>'comment deleted'],200);

}







}
