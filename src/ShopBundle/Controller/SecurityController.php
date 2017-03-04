<?php

namespace ShopBundle\Controller;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShopBundle\Entity\Role;
use ShopBundle\Entity\User;
use ShopBundle\Form\UserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/login",name="security_login")
     */
    public function loginAction()
    {
        $helper=$this->get('security.authentication_utils');

        return $this->render('security/login.html.twig',[
            'lastUser'=>$helper->getLastUsername(),
            'error'=>$helper->getLastAuthenticationError()
        ]);
    }
    /**
     *
     * @Route("/register" ,name="security_register")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerUserAction(Request $request)
    {
        $user=new User();
        $form=$this->createForm(UserFormType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $user=$this->prepareUser($user);
            try{
                $em=$this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            }
            catch ( Exception $exception){
                $this->get('session')->getFlashBag()->add('error', $exception->getMessage());
                var_dump($exception);
                return $this->render('security/register.html.twig',['form'=>$form->createView()]);
            }catch (DBALException $exception){
                $this->get('session')->getFlashBag()->add('error', "Потребителското име е заето");
                return $this->render('security/register.html.twig',['form'=>$form->createView()]);

            }
            return $this->render("security/login.html.twig");
        }
        return $this->render('security/register.html.twig',['form'=>$form->createView()]);
    }

    /**
     * @param $user User
     * @return User
     */
    private function prepareUser($user)
    {
        $doctrine = $this->getDoctrine();
        $roleRepo = $doctrine->getRepository(Role::class);
        $userRole = $roleRepo->findOneBy(['name' => 'ROLE_USER']);

        $password = $this->get('security.password_encoder')
            ->encodePassword($user, $user->getPassword());;

        $user->setPassword($password);
        $user->addRoles($userRole);
        return $user;
    }

    /**
     * This is the route the user can use to logout.
     *
     * But, this will never be executed. Symfony will intercept this first
     * and handle the logout automatically. See logout in app/config/security.yml
     *
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction()
    {
        throw new \Exception('This should never be reached!');
    }
}
