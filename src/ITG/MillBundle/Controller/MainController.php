<?php

namespace ITG\MillBundle\Controller;

use ITG\MillBundle\Component\BundleVersionInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class MainController extends BaseController
{
    /**
     * @ApiDoc(
     *     section="System"
     * )
     */
    public function getSystemBundlesAction()
    {
        $kernel = $this->get('kernel');
        $bundles = $kernel->getBundles();

        $registered = [];

        foreach ($bundles as $bundle)
        {
            if (in_array(BundleVersionInterface::class, class_implements($bundle)))
            {
                $registered[$bundle->getName()] = $bundle->getVersion();
            }
        }

        return $this->show($registered);
    }
}
