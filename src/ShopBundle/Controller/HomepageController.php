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


}
