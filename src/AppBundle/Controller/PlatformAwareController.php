<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PlatformAwareController extends Controller
{
    public function getPlatform()
    {
        return $this->get('session')->get('platform_id');
    }
}
