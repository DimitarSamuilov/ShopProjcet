<?php

namespace ShopBundle\Controller;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\AbstractDriverException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShopBundle\Entity\Merchandise;
use ShopBundle\Form\MerchandiseFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminController
 * @package ShopBundle\Controller
 * @Route("/admin")
 */
class AdminController extends Controller
{

    /**
     * @Route("/list",name="admin_list")
     */
    public function listMerchandiseAction()
    {
        $merchandise = $this->getDoctrine()->getRepository(Merchandise::class)->findby([],['dateAdded'=> 'desc']);
        return $this->render("admin/list.html.twig", ['merchandise' => $merchandise]);

    }

    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/edit/{id}",name="admin_edit_merchandise")
     */
    public function editMerchandiseAction($id, Request $request)
    {
        $merchandise = $this->getDoctrine()->getRepository(Merchandise::class)->find($id);
        if ($merchandise == null) {
            return $this->redirectToRoute("admin_list");
        }
        $form = $this->createForm(MerchandiseFormType::class, $merchandise);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($merchandise);
                $em->flush();
            }catch (Exception $exception){
                $this->get('session')->getFlashBag()->add('error', 'Username or email already taken!');
                return  $this->render("admin/edit.html.twig", ['form' => $form->createView()]);
            }
            return $this->redirectToRoute("admin_list");
        }
        return $this->render("admin/edit.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @Route("/delete/{id}",name="admin_delete_merchandise")
     */
    public function deleteMerchandiseAction($id)
    {
        $singleMerchandise = $this->getDoctrine()->getRepository(Merchandise::class)->find($id);
        if ($singleMerchandise == null) {
            return $this->redirectToRoute("admin_list");
        }
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($singleMerchandise);
            $em->flush();
        }catch (Exception $e){
            $this->get('session')->getFlashBag()->add('error', $e->getMessage());
            return $this->redirectToRoute("admin_list");
        }
        return $this->redirectToRoute("admin_list");

    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/add",name="admin_add_item")
     */
    public function addMerchandiseAction(Request $request)
    {
        $merchandise = new Merchandise();
        $form = $this->createForm(MerchandiseFormType::class, $merchandise);
        $form->handleRequest($request);
        if ( $form->isSubmitted()and $form->isValid() ) {
            try {
                $merchandise->setUser($this->getUser());
                $em = $this->getDoctrine()->getManager();
                $em->persist($merchandise);
                $em->flush();
            }catch (Exception $e){
                $this->get('session')->getFlashBag()->add('error', $e->getMessage());
                return $this->render("/admin/add.html.twig", ['form' => $form->createView()]);
            }catch (DBALException $exception){
                $this->get('session')->getFlashBag()->add('error', $exception->getMessage());
                return $this->render("/admin/add.html.twig", ['form' => $form->createView()]);
            }

            return $this->redirectToRoute("admin_list");
        }

        return $this->render("/admin/add.html.twig", ['form' => $form->createView()]);
    }
}
