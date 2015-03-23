<?php

namespace AppBundle\Twig;

class AssetVersionExtension extends \Twig_Extension
{
    private $appDir;

    public function __construct($appDir)
    {
        $this->appDir = $appDir;
    }

    public function getFilters()
    {
        return array(

        );
    }

    public function getName()
    {
        return 'asset_version';
    }
}
