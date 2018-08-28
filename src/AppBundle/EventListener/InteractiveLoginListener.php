<?php
namespace AppBundle\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManager;

use GeoIp2\Database\Reader;

class InteractiveLoginListener{

    protected $session;
    protected $em;
    protected $geoip_db_path;

    public function __construct(EntityManager  $em,SessionInterface $session,$geoip_db_path){
        $this->session = $session;
        $this->em = $em;
        $this->geoip_db_path = $geoip_db_path;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event){
        $request = $event->getRequest();
        $user = $event->getAuthenticationToken()->getUser();
        $uToken = $event->getAuthenticationToken();

        if (null !== $user->getLocale()) {
            $_locale = $user->getLocale();
            $request->setLocale($_locale);
            $this->session->set('_locale', $_locale);
        }

        $detect = new \Mobile_Detect;
        $device = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'desktop');

        $useragent = $request->headers->get('User-Agent');
        $ip = $ip = $request->getClientIp();
        $lat = null;
        $lng = null;
        $country = null;
        $cityName = null;

        $ip = ($ip == "127.0.0.1") ? "160.120.150.87" : $ip;
        
        $reader = new Reader($this->geoip_db_path);

        try {
            $record = $reader->city($ip);
            $isoCode = $record->country->isoCode;
            $geonameId = $record->country->geonameId;
            $countryName = $record->country->name;
            $cityName = $record->city->name;
            $lat = $record->location->latitude;
            $lng = $record->location->longitude;

            $rep = $this->em->getRepository(\AppBundle\Entity\Country::class);
            $country = $rep->find($geonameId);

        } catch (\Exception $e) {

        } catch (\AddressNotFoundException $e) {

        }

        $login = new \AppBundle\Entity\Login();
        $login->setUser($user);
        $login->setCountry($country);
        $login->setAction(\AppBundle\Entity\Login::ACTION_LOGIN);
        $login->setIp($ip);
        $login->setCity($cityName);
        $login->setLat($lat);
        $login->setLon($lng);
        $login->setDevice($device);
        $login->setUserAgent($useragent);
        $login->setToken(\AppBundle\Entity\User::generateToken(32));
        $this->em->persist($login);
        $this->em->flush();

        $uToken->setAttribute("coa_user_token", $login->getToken());
    }
}