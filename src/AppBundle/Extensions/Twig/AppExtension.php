<?php
namespace AppBundle\Extensions\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension{
    
    public function getFilters(){
        return array(
            new TwigFilter('generateToken', array($this, 'generateTokenFilter')),
        );
    }

    public function generateTokenFilter($length = 8){
        return substr(trim(base64_encode(bin2hex(openssl_random_pseudo_bytes(64,$ok))),"="),0,$length);
    }
}