<?php

namespace Somtel\RemitOneBundle\Payload;

use Aura\Payload_Interface\PayloadStatus;

class Status extends PayloadStatus
{
    /** Exception was thrown while handling the request. **/
    const EXCEPTION = 'EXCEPTION';
}
