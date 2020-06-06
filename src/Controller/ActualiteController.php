<?php

namespace App\Controller;

use App\Entity\Actualite;
use App\Repository\ActualiteRepository;
use App\Repository\AdminRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommentaireRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class ActualiteController extends AbstractController
{
/**
 * @var AdminRepository
 */
private $adminRepository;

/**
 * @var EntityManagerInterface
 */
private $entityManager;

/**
 * @var ActualiteRepository
 */

private $actualiteRepository;

/**
 * @var CategoryRepository
 */
private $categoryRepository;

/**
 * @var CommentaireRepository
 */

private $commentaireRepository;

public function __construct(EntityManagerInterface $entityManager,CommentaireRepository $commentaireRepository , AdminRepository $adminRepository ,CategoryRepository $categoryRepository,ActualiteRepository $actualiteRepository)
{
    $this->commentaireRepository=$commentaireRepository;
    $this->adminRepository=$adminRepository;
    $this->categoryRepository=$categoryRepository;
    $this->actualiteRepository=$actualiteRepository;
    $this->entityManager=$entityManager;
}


public function create(Request $request,$idadmin,$idcategory)
    {
        $actualite= new Actualite();
        $actualite->setTitre($request->request->get('titre'));
        $actualite->setContenu($request->request->get('contenu'));
        $actualite->setNbvisiteurs(0);
        $actualite->setDateCreation(new DateTime());
        $admin=$this->adminRepository->find($idadmin);
        $actualite->setCreePar($admin->getNom().' '.$admin->getPrenom());
       $imageFile=$request->files->get('image');
       $result = new DateTime();
       $result=$result->format('Y-m-d-H-i-s');
       $newFilename=$result.'.'.$imageFile->guessExtension();
       $imageFile->move(
           $this->getParameter('actualites_directory'),$newFilename
       );
       $imageurl=$request->getSchemeAndHttpHost().'/actualites/'.$newFilename;
       $actualite->setImage($imageurl);
       $category=$this->categoryRepository->find($idcategory);
       $this->entityManager->persist($actualite);
       $admin->addActualite($actualite);
       $category->addActualite($actualite);
       $this->entityManager->flush();
       return $this->json($actualite,201);
    }

    public function update(Request $request,$idactualite,$adminid)
    {
        $actualite=$this->actualiteRepository->find($idactualite);
        if($request->request->get('titre')){
            $actualite->setTitre($request->request->get('titre'));
        }
        if($request->request->get('contenu'))
        {
            $actualite->setContenu($request->request->get('contenu'));

        }
        if($request->files->get('image'))
        {
              
                $filesystem=new Filesystem();
                $oldimagename=strrchr($actualite->getImage(),'/');
                $filesystem->remove($this->getParameter('actualites_directory').$oldimagename);
              
              
                $imageFile=$request->files->get('image');
                $result = new DateTime();
                $result=$result->format('Y-m-d-H-i-s');
                $newFilename=$result.'.'.$imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('actualites_directory'),$newFilename
                );
                $imageurl=$request->getSchemeAndHttpHost().'/actualites/'.$newFilename;
                $actualite->setImage($imageurl);

                
        }
        $admin=$this->adminRepository->find($adminid);
        $actualite->setModifiePar($admin->getNom().' '.$admin->getPrenom());
        $this->entityManager->flush();
        return $this->json($actualite,200);

    }   
    public function delete($idactualite){
        $actualite=$this->actualiteRepository->find($idactualite);
        $admin=$actualite->getAdmin();
        $category=$actualite->getCategory();
        $admin->removeActualite($actualite);
        $category->removeActualite($actualite);
        $image=strrchr($actualite->getImage(),'/');
        $filesystem=new Filesystem();
        $filesystem->remove($this->getParameter('actualites_directory').$image);
        $this->entityManager->remove($actualite);
        $this->entityManager->flush();
        return $this->json(['message'=>'actualite deleted'],200);
    }
public function getLatestNews()
{
    $actualites=$this->actualiteRepository->findBy( [],['dateCreation'=>'DESC'], $limit=10);
    return $this->json($actualites,200);

}

public function getallActualites(){
    $actualites=$this->actualiteRepository->findAll();
    return $this->json($actualites,200);
}

public function getactualite($idactualite)
{
$actualite=$this->actualiteRepository->find($idactualite);
return $this->json($actualite,200);
}


public function getActualitesbycategory($idcategory)
{
    $actualites=$this->actualiteRepository->findBy(['category'=>$idcategory]);
    return $this->json($actualites,200);

}



}