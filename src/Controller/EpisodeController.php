<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Repository\EpisodeRepository;
use App\Repository\PodcastRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

class EpisodeController extends AbstractController
{
   /**
    * @var EpisodeRepository
    */
    private $episodeRepository;

    /**
     * @var PodcastRepository
     */
    private $podcastRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

public function __construct(PodcastRepository $podcastRepository , EpisodeRepository $episodeRepository , EntityManagerInterface $entityManager)
{
 $this->podcastRepository=$podcastRepository;
 $this->entityManager=$entityManager;
 $this->episodeRepository=$episodeRepository;   

}


public function getAllEpisodes()
{
    $episodes = $this->episodeRepository->findAll();
    return $this->json($episodes,200);

}

public function createEpisode(Request $request , $idpodcast)
{
    $episode = new Episode();
    $episodeFile=$request->files->get('sequence');
    $result = new DateTime();
    $result=$result->format('Y-m-d-H-i-s');
    $newFilename=$result.'.'.$episodeFile->guessExtension();
    $episodeFile->move(
        $this->getParameter('episodes_directory'),$newFilename
    );
    $sequenceUrl=$request->getSchemeAndHttpHost().'/episodes/'.$newFilename;
    $episode->setSequence($sequenceUrl);

    $episode->setNom($request->request->get("nom"));
    $episode->setDateCreation(new DateTime());
    $episode->setInvites($request->request->get("invites"));
    $podcast = $this->podcastRepository->find($idpodcast);
    $this->entityManager->persist($episode);
    $podcast->addEpisode($episode);
    $this->entityManager->flush();
    return $this->json($episode,201);
}

public function updateEpisode(Request $request , $idepisode)
{
    $episode = $this->episodeRepository->find($idepisode);

    if($request->request->get("sequence")){
        $filesystem=new Filesystem();
        $oldepisodename=strrchr($episode->getSequence(),'/');
        $filesystem->remove($this->getParameter('episodes_directory').$oldepisodename);



            $episodeFile=$request->files->get('sequence');
            $result = new DateTime();
            $result=$result->format('Y-m-d-H-i-s');
            $newFilename=$result.'.'.$episodeFile->guessExtension();
            $episodeFile->move(
            $this->getParameter('episodes_directory'),$newFilename
    );
             $sequenceUrl=$request->getSchemeAndHttpHost().'/episodes/'.$newFilename;
            $episode->setSequence($sequenceUrl);

        }
        if($request->request->get("invites"))
        {
            $episode->setInvites($request->request->get("invites"));
        }
        if($request->request->get("nom")){
            $episode->setNom($request->request->get("nom"));
        }
        $this->entityManager->flush();
        return $this->json($episode,200);
}

public function delete_episode($idepisode)
{
    $episode = $this->episodeRepository->find($idepisode);
    $filesystem=new Filesystem();
    $oldepisodename=strrchr($episode->getSequence(),'/');
    $filesystem->remove($this->getParameter('episodes_directory').$oldepisodename);

    $podcast = $episode->getPodcast();
    $podcast->removeEpisode($episode);
    $this->entityManager->remove($episode);
    $this->entityManager->flush();
    return $this->json(['message'=>'episode deleted done !'],200);
}
public function getEpisodesbyPodcast($idpodcast)
{
    $episodes= $this->episodeRepository->findBy(['podcast'=>$idpodcast]);
    return $this->json($episodes,200);
}

public function getEpisode($idepisode)
{
    $episode = $this->episodeRepository->find($idepisode);
    return $this->json($episode,200);
}

}
