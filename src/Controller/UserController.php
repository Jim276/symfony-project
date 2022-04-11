<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/user")
 */

class UserController extends AbstractController
{
    private $_em;
    private $user;

    public function __construct(ManagerRegistry $registry, Security $security){
        $this->_em = $registry;
        $this->user = $security->getUser();
    }

    /**
    * @Route("/edit", name="user_edit", methods={"GET","POST"})
    */
    public function edit(Request $request): Response
    {
        $form = $this->createForm(UserType::class, $this->user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->_em->getManager()->flush();
            return $this->redirectToRoute('post_index');
        }
        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
