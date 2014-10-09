<?php

namespace HOA\Bundle\SinglePageBundle\Controller;

use HOA\Bundle\SinglePageBundle\Event\ContactEvent;
use HOA\Bundle\SinglePageBundle\HOASinglePageEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FrontendController extends Controller
{

    public function singlePageAction(Request $request, $siteId = null)
    {
        $contactSent=false;

        if (!$this->get('hoa.manager.spa_config')->existSPAConfig($siteId)) {
            $siteId=$this->container->getParameter('hoa_single_page.main_theme');
        };
        $config = $this->get('hoa.manager.spa_config')->loadSPAConfig($siteId);

        $form = $this->createForm('hoa_contact_form');
        $form->handleRequest($request);
        if ($form->isValid()) {
            $event = new ContactEvent($form->getData());
            $event->setMail($config['settings']['mail']);
            $this->get('event_dispatcher')->dispatch(HOASinglePageEvents::CONTACT_FORM_FILLED, $event);
            $contactSent=true;
        }

        return $this->render('HOASinglePageBundle:Frontend:singlePage.html.twig', array(
            'config' => $config,
            'form' => $form->createView(),
            'contactSent'=>$contactSent
        ));
    }
}
