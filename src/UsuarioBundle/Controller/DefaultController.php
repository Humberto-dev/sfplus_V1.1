<?php

namespace UsuarioBundle\Controller;

use UsuarioBundle\Entity\User;
use UsuarioBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('UsuarioBundle:Default:index.html.twig');
    }

     /**
     * @Route("/usuarios", name="usuarios")
     */
    public function usuariosAction()
    {
        return $this->render('UsuarioBundle:Default:index.html.twig');
    }


    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request)
    {
        $user= new User();
        $form= $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $password= $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $entityManager= $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return new Response('Usuario registrado');
        }

        return $this->render(
            'UsuarioBundle:Default:register.html.twig',
            array('form'=>$form->createView())
        );


    }

    /**
     * @Route("/usuarios/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
    
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
    
        return $this->render('UsuarioBundle:Default:login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
}
