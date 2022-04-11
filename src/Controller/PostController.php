<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */

class PostController extends AbstractController
{
    private $_em;

    public function __construct(ManagerRegistry $registry){
        $this->_em = $registry;
    }

    /**
     * @Route("/", name="post_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_show", methods={"GET"}, requirements={"id" = "\d+"})
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
    * @Route("/new", name="post_new", methods={"GET","POST"})
    */
    public function new(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->_em->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/new.html.twig', [
        'post' => $post,
        'form' => $form->createView(),
        ]);
    }

    /**
    * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
    */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->_em->getManager()->flush();
            return $this->redirectToRoute('post_index');
        }
        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"GET", "POST"})
     */
    public function delete(Request $request, Post $post): Response
    {
        $this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'));
        $entityManager = $this->_em->getManager();
        $entityManager->remove($post);
        $entityManager->flush();
        return $this->redirectToRoute('post_index');
    }

    /**
    * @Route("/{slug}", name="post_show_slug", methods={"GET"}, requirements={"page"="\w+"})
    */
    public function show_slug(string $slug): Response
    {
        return new Response("<html><body><p>post from slug $slug</p></body></html>");
    }
}
