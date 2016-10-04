<?php

namespace ITG\JumioBundle\Event;

use ITG\JumioBundle\Entity\Netverify;
use Symfony\Component\EventDispatcher\Event;

class NetverifyRequestChangeEvent extends Event
{
    const NAME = 'jumio.netverify.request.changed';

    protected $netverify;

    public function __construct(Netverify $netverify)
    {
        $this->netverify = $netverify;
    }

    public function getNetverify()
    {
        return $this->netverify;
    }
}