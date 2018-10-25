<?php
// src/EventListener/ExceptionListener.php
namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class RequestListener{
    public function onKernelRequest(GetResponseEvent $event){
    	$request = $event->getRequest();

        if (!$event->isMasterRequest()) {
            return;
        }

    	if($request->query->has('_locale')){
    		$_locale = $request->query->get('_locale','fr');
    	}
    	else{
    		$_locale = $request->cookies->get('_locale','fr');
    	}
    	
    	$request->setLocale($_locale);
    }
}