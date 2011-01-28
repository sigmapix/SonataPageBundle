<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlockAdminController extends Controller
{

    public function returnJson($data) {
        $response = new \Symfony\Component\HttpFoundation\Response;
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }
    public function savePositionAction()
    {
        // todo : add security check
        $params = $this->get('request')->get('disposition');

        $result = $this->get('page.manager')->savePosition($params);

        return $this->returnJson(array('result' => $result ? 'ok' : 'ko'));
    }

    public function editAction($id, $form = null)
    {

        $this->get('session')->start();
        $manager  = $this->get('page.manager');

        // clean the id
        if(!is_object($id)) {
            $id       = (int) str_replace('cms-block-', '', $id);

            $block = $manager->getBlock($id);

            if(!$block) {
                throw new NotFoundHttpException(sprintf('block not found (id: %d)', $id));
            }
        } else {
            $block = $id;
        }

        $service = $manager->getBlockService($block);

        return $this->render($service->getEditTemplate(), array(
            'block'   => $block,
            'form'    => $form ?: $this->getForm($block),
            'service' => $service,
            'manager' => $manager
        ));
    }

    public function getForm($block)
    {
         $form = new \Symfony\Component\Form\Form('block', $block, $this->get('validator'), array(
            'validation_groups' => array($block->getType())
         ));

         $this->get('page.manager')->defineBlockForm($form);

         return $form;
    }

    public function updateAction()
    {

        $this->get('session')->start();

        // clean the id
        $id       = $this->get('request')->get('id');

        $block = $this->get('page.manager')->getBlock($id);

        if(!$block) {
            throw new NotFoundHttpException(sprintf('block not found (id: %d)', $id));
        }

        $form = $this->getForm($block);
        $form->bind($this->get('request')->get('block'));

        if($form->isValid()){
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->persist($block);
            $em->flush();

             return $this->redirect($this->generateUrl('sonata_page_block_edit', array('id' => $block->getId())));
        }

        return $this->forward('SonataPageBundle:BlockAdmin:edit', array(
            'id'    => $block,
            'form'  => $form
        ));
    }

    public function viewAction($id)
    {

    }

    public function createAction()
    {

    }
}