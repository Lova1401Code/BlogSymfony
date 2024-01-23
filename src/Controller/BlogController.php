<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="app_blog")
     */
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }
    /**
     * @Route("/",name="home")
     */
    public function home(ArticleRepository $repo)
    {
        //$repo = $this->getDoctrine()->getRepository(Article::class);
        $artilces = $repo->findAll();
        return $this->render('blog/home.html.twig', [
            'title' => 'Mon Blog',
            'articles' => $artilces
        ]);
    }
    /**
     * @Route("/blog/create",name="createArticle")
     * @Route("/blog/edit/{id}", name="editArticle")
     */
    public function formArticle(Article $article=null, Request $request)
    {
        // $repo = $this->getDoctrine()->getRepository(Article::class);
        // $findArticle = $repo->find($id);
        if (!$article) {
            $article = new Article();
        }
        $formBuilder = $this->createFormBuilder($article);
        // $form = $formBuilder->add('title')
        //                     ->add('content')
        //                     ->getForm();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$article->getId()) {    
                $article->setCreatedAt(new \DateTime());
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('showArticle', ['id' => $article->getId()]);
        }
        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }
    /**
     * @Route("/blog/{id}",name="showArticle")
     */
    public function showArticle(ArticleRepository $repo,$id)
    {
        //$repo = $this->getDoctrine()->getRepository(Article::class);
        $article = $repo->find($id);
         return $this->render('blog/showArticle.html.twig',[
            'article' => $article
        ]);
    }
}
// dump($request);
        // if ($request->request->count() > 0) {
        //     $article = new Article();
        //     $article -> setContent($request->request->get('nom'))
        //              -> setTitle($request->request->get('prenom'))
        //              -> setCreatedAt(new \DateTime());
        //     $manager = $this->getDoctrine()->getManager();
        //     $manager->persist($article);
        //     $manager->flush();   
        //     return $this->redirectToRoute('showArticle', ['id'=>$article->getId()]);
        //}