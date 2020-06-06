<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Actualite;
use App\Repository\ActualiteRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
   {
        /**
     * @var CategoryRepository
     */
      
        private $categoryRepository;

    
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ActualiteRepository
     */
    
    private $actualiteRepository;
  
    public function __construct(CategoryRepository $cateogryRepository , ActualiteRepository $actualiteRepository, EntityManagerInterface $entityManager)
    {

        $this->categoryRepository=$cateogryRepository;
        $this->actualiteRepository=$actualiteRepository;
        $this->entityManager=$entityManager;
}



    /**
     * @Route("/category", name="category")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CategoryController.php',
        ]);
    }


    public function getAllCategories()
    {
      $categories=$this->categoryRepository->findAll();

      return $this->json($categories,200);
    }

    public function createCategory(Request $request)
    {
        $category= new Category();
        $category->setNomCategory($request->request->get("nomCategory"));
       $this->entityManager->persist($category);
       $this->entityManager->flush();
        return $this->json($category,201);
        
    }

    public function updateCategory(Request $request,$idcategory){
        $category=$this->categoryRepository->find($idcategory);
        $category->setNomCategory($request->request->get("nomCategory"));
        $this->entityManager->flush();
        return $this->json($category,200);
        }

    public function showCategory(Request $request,$idcategory){
        $category=$this->categoryRepository->find($idcategory);
        return $this->json($category,200);

    }

    public function deleteCategory($idcategory)
{
    $category=$this->categoryRepository->find($idcategory);
    $this->entityManager->remove($category);
    $this->entityManager->flush();
    return $this->json(['message'=>'category deleted'],200);

}
   }