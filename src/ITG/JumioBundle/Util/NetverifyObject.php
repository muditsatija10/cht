<?php

namespace ITG\JumioBundle\Util;

use Symfony\Component\HttpFoundation\File\File;

class NetverifyObject
{
    /** @var  */
    private $merchantIdScanReference;

    private $frontSideImage;
    private $frontSideMime;

    private $backSideImage;
    private $backSideMime;

    private $faceImage;
    private $faceMime;

    // ===== Merchant ref =====
    public function setMerchantIdScanReference($reference)
    {
        $this->merchantIdScanReference = $reference;

        return $this;
    }

    public function getMerchantIdScanReference($reference)
    {
        return $this->merchantIdScanReference;
    }

    // ===== Front =====
    public function setFrontSideImage(File $image)
    {
        $this->frontSideImage = $image->getPathname();
        $this->frontSideMime = $image->getMimeType();

        return $this;
    }

    public function getFrontSideImage()
    {
        return $this->frontSideImage;
    }

    public function getFrontSideMime()
    {
        return $this->frontSideMime;
    }

    // ===== Back =====
    public function setBackSideImage(File $image)
    {
        $this->backSideImage = $image->getPathname();
        $this->backSideMime = $image->getMimeType();

        return $this;
    }

    public function getBackSideImage()
    {
        return $this->backSideImage;
    }

    public function getBackSideMime()
    {
        return $this->backSideMime;
    }

    // ===== Face =====
    public function setFaceImage(File $image)
    {
        $this->faceImage = $image->getPathname();
        $this->faceMime = $image->getMimeType();

        return $this;
    }

    public function getFaceImage()
    {
        return $this->backSideImage;
    }

    public function getFaceMime()
    {
        return $this->backSideMime;
    }

    // ===== Util =====
    public function buildJson()
    {
        $front = base64_encode(file_get_contents($this->frontSideImage));
        $back  = base64_encode(file_get_contents($this->backSideImage));
        $face  = base64_encode(file_get_contents($this->faceImage));

        $obj = [
            'merchantIdScanReference' => $this->merchantIdScanReference,

            'frontsideImage' => $front,
            'frontsideImageMimeType' => $this->frontSideMime,

            'backsideImage' => $back,
            'backsideImageMimeType' => $this->backSideMime,

            'faceImage' => $face,
            'faceImageMimeType' => $this->faceMime,
        ];

        return json_encode($obj);
    }
}