<?php

namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShopBundle\Entity\Merchandise;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomepageController extends Controller
{

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
