<?php

namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShopBundle\Entity\Merchandise;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomepageController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/",name="homepage")
     */
    public function homepageOriginalAction()
    {
        $slideInfo=$this->getDoctrine()->getRepository(Merchandise::class)->find(9);
        $merchandise=$this->getDoctrine()->getRepository(Merchandise::class)->findBy([],[],8);
        return $this->render("homepage.html.twig",['merchandise'=>$merchandise,'slideinfo'=>$slideInfo]);
    }
}
