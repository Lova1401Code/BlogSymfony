<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     */
    public function create(Request $request)
    {
        dump($request);
        if ($request->request->count() > 0) {
            $article = new Article();
            $article -> setContent($request->request->get('nom'))
                     -> setTitle($request->request->get('prenom'))
                     -> setCreatedAt(new \DateTime());
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($article);
            $manager->flush();   
            return $this->redirectToRoute('showArticle', ['id'=>$article->getId()]);
        }
        return $this->render('blog/create.html.twig');
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
