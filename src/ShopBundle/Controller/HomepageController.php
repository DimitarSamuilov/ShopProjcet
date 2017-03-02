<?php

namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShopBundle\Entity\Merchandise;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomepageController extends Controller
{
    /**
     * @Route("/",name="homepage")
     */
    public function homepageAction()
    {
        $merchandise=$this->getDoctrine()->getRepository(Merchandise::class)->findAll();
        return $this->render("main/homepage.html.twig",['merchandise'=>$merchandise]);
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/homepage")
     */
    public function homepageOriginalAction()
    {
        $merchandise=$this->getDoctrine()->getRepository(Merchandise::class)->findBy([],[],8);
        return $this->render("homepage.html.twig",['merchandise'=>$merchandise]);
    }
}
