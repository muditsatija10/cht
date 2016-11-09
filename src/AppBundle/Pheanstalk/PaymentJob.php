<?php

namespace AppBundle\Pheanstalk;

use \Pheanstalk\Job as BaseJob;

class PaymentJob extends BaseJob
{
    /**
     * Job constructor.
     * @param int $id
     * @param mixed $data
     */
    public function __construct($id, $data = null)
    {
        if (is_array($data)) {
            $data = \GuzzleHttp\json_encode($data);
        }

        parent::__construct($id, $data);
    }

    /**
     * The job data.
     * @return string
     */
    public function getData()
    {
        return parent::getData();
    }

    /**
     * @return mixed
     */
    public function getDataAsArray()
    {
        return \GuzzleHttp\json_decode(parent::getData(), true);
    }
}
