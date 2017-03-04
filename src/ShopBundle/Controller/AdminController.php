<?php

namespace ShopBundle\Controller;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\DBAL\Driver\AbstractDriverException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShopBundle\Entity\Merchandise;
use ShopBundle\Form\MerchandiseFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
        $merchandise = $this->getDoctrine()->getRepository(Merchandise::class)->findby([], ['dateAdded' => 'desc']);
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
        $oldImageSrc = $merchandise->getImage();
        $tempFile = new File($merchandise->getImage());
        $merchandise->setImage($tempFile);
        $form = $this->createForm(MerchandiseFormType::class, $merchandise);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            if ($merchandise->getImage() == null) {
                $merchandise->setImage($oldImageSrc);
            } else {
                $file = $merchandise->getImage();
                $fileName = $this->moveImage($file);
                $merchandise->setImage($fileName);
            }
            if (empty($merchandise->getName())) {
                $this->get('session')->getFlashBag()->add('error', "Продукта трябва да има Име");
                return $this->render(
                    "admin/edit.html.twig",
                    ['form' => $form->createView(), 'merchandise' => $merchandise, 'image' => $oldImageSrc]
                );
            }
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($merchandise);
                $em->flush();
            } catch (DBALException $exception) {
                $this->get('session')->getFlashBag()->add('error', "Невалидни данни");
                return $this->render(
                    "admin/edit.html.twig",
                    ['form' => $form->createView(),
                        'merchandise' => $merchandise,
                        'image' => $oldImageSrc
                    ]);
            }
            return $this->redirectToRoute("admin_list");
        }
        if($form->getErrors(true,false)->getChildren()) {
                $this->get('session')->getFlashBag()->add('error', "Невалидана инфорамцаия");

        }
        return $this->render("admin/edit.html.twig", ['form' => $form->createView(), 'merchandise' => $merchandise, 'image' => $oldImageSrc]);
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
        } catch (Exception $e) {
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
        if ($form->isSubmitted() and $form->isValid()) {
            try {
                if ($merchandise->getImage() == null) {
                    $this->get('session')->getFlashBag()->add('error', "Не е дадено изображение");
                    return $this->render("/admin/add.html.twig", ['form' => $form->createView()]);
                }
                $file = $merchandise->getImage();
                $fileName = $this->moveImage($file);
                $merchandise->setImage($fileName);
                $merchandise->setUser($this->getUser());
                $em = $this->getDoctrine()->getManager();
                $em->persist($merchandise);
                $em->flush();
            } catch (DBALException $exception) {
                $this->get('session')->getFlashBag()->add('error', "Невалидна информация");
                return $this->render("/admin/add.html.twig", ['form' => $form->createView()]);
            }
            return $this->redirectToRoute("admin_list");
        }
        if($form->getErrors(true,false)->getChildren()) {
            $this->get('session')->getFlashBag()->add('error', "Невалидана инфорамцаия");
        }
        return $this->render("/admin/add.html.twig", ['form' => $form->createView()]);
    }

    private function moveImage($file)
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $directory = '..' . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'Images';
        $file->move($directory, $fileName);
        $result = 'Resources' . DIRECTORY_SEPARATOR . 'Images' . DIRECTORY_SEPARATOR . $fileName;
        return $result;
    }
}
