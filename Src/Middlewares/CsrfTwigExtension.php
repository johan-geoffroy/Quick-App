<?php declare(strict_types= 1);



namespace App\Middlewares;


use Twig\Extension\AbstractExtension;
use Slim\Csrf\Guard;


class CsrfTwigExtension extends AbstractExtension
{
    protected $csrf;

    public function __construct(Guard $csrf)
    {
        $this->csrf = $csrf;
    }

    public function getFunctions()
    {
        return[
            new \Twig_SimpleFunction('csrf', array($this, 'csrfGuard'))
        ];

    }

    public function csrfGuard(){

        $csrfNameKey = $this->csrf->getTokenNameKey();
        $csrfValueKey = $this->csrf->getTokenValueKey();
        $csrfName = $this->csrf->getTokenName();
        $csrfValue = $this->csrf->getTokenValue();
    
        

        return '
            <input type="hidden" name="'.$csrfNameKey.'" value="'.$csrfName.'">
            <input type="hidden" name="'.$csrfValueKey.'" value="'.$csrfValue.'">
        ';

        
    }

}