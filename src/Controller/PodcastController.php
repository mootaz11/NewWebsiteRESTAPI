<?php

namespace App\Controller;

use App\Entity\Podcast;
use App\Repository\PodcastRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PodcastController extends AbstractController
{

    /**
     * @var PodcastRepository
     */
    private $podcastRepository;
    
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

  public function __construct(PodcastRepository $podcastRepository , EntityManagerInterface $entityManager)
  {
        $this->podcastRepository=$podcastRepository;
        $this->entityManager=$entityManager;  

  }
  public function createpodcast(Request $request){
      $podcast = new Podcast();
      $podcast->setNomPodcast($request->request->get("nomPodcast"));
      $podcast->setTypePodcast($request->request->get("typePodcast"));
      $this->entityManager->persist($podcast);
      $this->entityManager->flush();
      return $this->json($podcast,201);
  }


public function updatepodcast(Request $request , $idpodcast)
{
    $podcast=$this->podcastRepository->find($idpodcast);
    if($request->request->get("nomPodcast"))
    {
        $podcast->setNomPodcast($request->request->get("nomPodcast"));
    }
    if($request->request->get("typePodcast")){
        $podcast->setTypePodcast($request->request->get("typePodcast"));
    }
    $this->entityManager->flush();
    return $this->json($podcast,200);
}


public function deletePodcast($idpodcast)
{
    $podcast=$this->podcastRepository->find($idpodcast);
    $this->entityManager->remove($podcast);
    $this->entityManager->flush();
    return $this->json(['message'=>'podcast deleted'],200);
}


public function getAllPodcasts()
{
    $podcasts = $this->podcastRepository->findAll();
    return $this->json($podcasts,200);
}

public function getPodcastsByType(Request $request)
{
    $podcasts = $this->podcastRepository->findBy(["typePodcast"=>$request->request->get("typePodcast")]);
    return $this->json($podcasts,200);
}

public function getPodcast($idpodcast)
{
    $podcast = $this->podcastRepository->find($idpodcast);
    return $this->json($podcast,200);
}

}
