<?php

namespace ITG\JumioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NetverifyResponse
 *
 * @ORM\Table(name="netverify_response")
 * @ORM\Entity(repositoryClass="ITG\JumioBundle\Repository\NetverifyResponseRepository")
 */
class NetverifyResponse
{
    const STATUS_PENDING = 'PENDING';
    const STATUS_SUCCESS = 'SUCCESS';
    const STATUS_ERROR = 'ERROR';

    const VERIFICATION_STATUS_APPROVED_VERIFIED = 'APPROVED_VERIFIED';
    const VERIFICATION_STATUS_DENIED_FRAUD = 'DENIED_FRAUD';
    const VERIFICATION_STATUS_DENIED_UNSUPPORTED_ID_TYPE = 'DENIED_UNSUPPORTED_ID_TYPE';
    const VERIFICATION_STATUS_DENIED_UNSUPPORTED_ID_COUNTRY = 'DENIED_UNSUPPORTED_ID_COUNTRY';
    const VERIFICATION_STATUS_ERROR_NOT_READEABLE_ID = 'ERROR_NOT_READABLE_ID';
    const VERIFICATION_STATUS_NO_ID_UPLOADED = 'NO_ID_UPLOADED';

    const TYPE_PASSPORT = 'PASSPORT';
    const TYPE_DRIVING_LICENSE = 'DRIVING_LICENSE';
    const TYPE_ID_CARD = 'ID_CARD';

    const SUBTYPE_NATIONAL_ID = 'NATIONAL_ID';
    const SUBTYPE_CONSULAR_ID = 'CONSULAR_ID';
    const SUBTYPE_ELECTORAL_ID = 'ELECTORAL_ID';
    const SUBTYPE_RESIDENT_PERMIT_ID = 'RESIDENT_PERMIT_ID';
    const SUBTYPE_TAX_ID = 'TAX_ID';
    const SUBTYPE_STUDENT_ID = 'STUDENT_ID';
    const SUBTYPE_PASSPORT_CARD_ID = 'PASSPORT_CARD_ID';
    const SUBTYPE_OTHER_ID = 'OTHER_ID';
    const SUBTYPE_VISA = 'VISA';
    const SUBTYPE_UNKNOWN = 'UNKNOWN';
    const SUBTYPE_LEARNING_DRIVING_LICENSE = 'LEARNING_DRIVING_LICENSE';
    const SUBTYPE_E_PASSPORT = 'E_PASSPORT';

    const REJECT_CODE_100 = 'MANIPULATED_DOCUMENT';
    const REJECT_CODE_105 = 'FRAUDSTER';
    const REJECT_CODE_106 = 'FAKE';
    const REJECT_CODE_107 = 'PHOTO_MISMATCH';
    const REJECT_CODE_108 = 'MRZ_CHECK_FAILED';
    const REJECT_CODE_109 = 'PUNCHED_DOCUMENT';
    const REJECT_CODE_111 = 'MISMATCH_PRINTED_BARCODE_DATA';
    const REJECT_CODE_102 = 'PHOTOCOPY_BLACK_WHITE';
    const REJECT_CODE_103 = 'PHOTOCOPY_COLOR';
    const REJECT_CODE_104 = 'DIGITAL_COPY';
    const REJECT_CODE_200 = 'NOT_READABLE_DOCUMENT';
    const REJECT_CODE_201 = 'NO_DOCUMENT';
    const REJECT_CODE_202 = 'SAMPLE_DOCUMENT';
    const REJECT_CODE_206 = 'MISSING_BACK';
    const REJECT_CODE_207 = 'WRONG_DOCUMENT_PAGE';
    const REJECT_CODE_209 = 'MISSING_SIGNATURE';
    const REJECT_CODE_210 = 'CAMERA_BLACK_WHITE';
    const REJECT_CODE_211 = 'DIFFERENT_PERSONS_SHOWN';
    const REJECT_CODE_300 = 'MANUAL_REJECTION';

    const DETAILS_CODE_1001 = 'PHOTO';
    const DETAILS_CODE_1002 = 'DOCUMENT_NUMBER';
    const DETAILS_CODE_1003 = 'EXPIRY';
    const DETAILS_CODE_1004 = 'DOB';
    const DETAILS_CODE_1005 = 'NAME';
    const DETAILS_CODE_1006 = 'ADDRESS';
    const DETAILS_CODE_1007 = 'SECURITY_CHECKS';
    const DETAILS_CODE_1008 = 'SIGNATURE';
    const DETAILS_CODE_1009 = 'PERSONAL_NUMBER';
    const DETAILS_CODE_2001 = 'BLURRED';
    const DETAILS_CODE_2002 = 'BAD_QUALITY';
    const DETAILS_CODE_2003 = 'MISSING_PART_DOCUMENT';
    const DETAILS_CODE_2004 = 'HIDDEN_PART_DOCUMENT';
    const DETAILS_CODE_2005 = 'DAMAGED_DOCUMENT';
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="jumioScanReference", type="string", length=255)
     */
    private $jumioScanReference;

    /**
     * @var string
     *
     * @ORM\Column(name="merchantScanReference", type="string", length=255)
     */
    private $merchantScanReference;

    /**
     * @var string
     *
     * @ORM\Column(name="verificationStatus", type="string", length=255)
     */
    private $verificationStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="idScanStatus", type="string", length=255)
     */
    private $idScanStatus;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="transactionDate", type="datetime")
     */
    private $transactionDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="callbackDate", type="datetime")
     */
    private $callbackDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="idType", type="string", length=255, nullable=true)
     */
    private $idType;

    /**
     * @var string
     *
     * @ORM\Column(name="idSubtype", type="string", length=255, nullable=true)
     */
    private $idSubtype;

    /**
     * @var string
     *
     * @ORM\Column(name="idCountry", type="string", length=255, nullable=true)
     */
    private $idCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="rejectReasonCode", type="string", length=255, nullable=true)
     */
    private $rejectReasonCode;

    /**
     * @var string
     *
     * @ORM\Column(name="rejectDetailsCode", type="string", length=255, nullable=true)
     */
    private $rejectDetailsCode;

    /**
     * @var int
     *
     * @ORM\Column(name="idFaceMatch", type="integer", nullable=true)
     */
    private $idFaceMatch;

    /**
     * @var string
     *
     * @ORM\Column(name="clientIp", type="string", length=255, nullable=true)
     */
    private $clientIp;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set jumioScanReference
     *
     * @param string $jumioScanReference
     *
     * @return NetverifyResponse
     */
    public function setJumioScanReference($jumioScanReference)
    {
        $this->jumioScanReference = $jumioScanReference;

        return $this;
    }

    /**
     * Get jumioScanReference
     *
     * @return string
     */
    public function getJumioScanReference()
    {
        return $this->jumioScanReference;
    }

    /**
     * Set merchantScanReference
     *
     * @param string $merchantScanReference
     *
     * @return NetverifyResponse
     */
    public function setMerchantScanReference($merchantScanReference)
    {
        $this->merchantScanReference = $merchantScanReference;

        return $this;
    }

    /**
     * Get merchantScanReference
     *
     * @return string
     */
    public function getMerchantScanReference()
    {
        return $this->merchantScanReference;
    }

    /**
     * Set verificationStatus
     *
     * @param string $verificationStatus
     *
     * @return NetverifyResponse
     */
    public function setVerificationStatus($verificationStatus)
    {
        $this->verificationStatus = $verificationStatus;

        return $this;
    }

    /**
     * Get verificationStatus
     *
     * @return string
     */
    public function getVerificationStatus()
    {
        return $this->verificationStatus;
    }

    /**
     * Set idScanStatus
     *
     * @param string $idScanStatus
     *
     * @return NetverifyResponse
     */
    public function setIdScanStatus($idScanStatus)
    {
        $this->idScanStatus = $idScanStatus;

        return $this;
    }

    /**
     * Get idScanStatus
     *
     * @return string
     */
    public function getIdScanStatus()
    {
        return $this->idScanStatus;
    }

    /**
     * Set transactionDate
     *
     * @param \DateTime $transactionDate
     *
     * @return NetverifyResponse
     */
    public function setTransactionDate($transactionDate)
    {
        $this->transactionDate = $transactionDate;

        return $this;
    }

    /**
     * Get transactionDate
     *
     * @return \DateTime
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * Set callbackDate
     *
     * @param \DateTime $callbackDate
     *
     * @return NetverifyResponse
     */
    public function setCallbackDate($callbackDate)
    {
        $this->callbackDate = $callbackDate;

        return $this;
    }

    /**
     * Get callbackDate
     *
     * @return \DateTime
     */
    public function getCallbackDate()
    {
        return $this->callbackDate;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return NetverifyResponse
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set idType
     *
     * @param string $idType
     *
     * @return NetverifyResponse
     */
    public function setIdType($idType)
    {
        $this->idType = $idType;

        return $this;
    }

    /**
     * Get idType
     *
     * @return string
     */
    public function getIdType()
    {
        return $this->idType;
    }

    /**
     * Set idSubtype
     *
     * @param string $idSubtype
     *
     * @return NetverifyResponse
     */
    public function setIdSubtype($idSubtype)
    {
        $this->idSubtype = $idSubtype;

        return $this;
    }

    /**
     * Get idSubtype
     *
     * @return string
     */
    public function getIdSubtype()
    {
        return $this->idSubtype;
    }

    /**
     * Set idCountry
     *
     * @param string $idCountry
     *
     * @return NetverifyResponse
     */
    public function setIdCountry($idCountry)
    {
        $this->idCountry = $idCountry;

        return $this;
    }

    /**
     * Get idCountry
     *
     * @return string
     */
    public function getIdCountry()
    {
        return $this->idCountry;
    }

    /**
     * Set rejectReasonCode
     *
     * @param string $rejectReasonCode
     *
     * @return NetverifyResponse
     */
    public function setRejectReasonCode($rejectReasonCode)
    {
        $this->rejectReasonCode = $rejectReasonCode;

        return $this;
    }

    /**
     * Get rejectReasonCode
     *
     * @return string
     */
    public function getRejectReasonCode()
    {
        return $this->rejectReasonCode;
    }

    /**
     * Set rejectDetailsCode
     *
     * @param string $rejectDetailsCode
     *
     * @return NetverifyResponse
     */
    public function setRejectDetailsCode($rejectDetailsCode)
    {
        $this->rejectDetailsCode = $rejectDetailsCode;

        return $this;
    }

    /**
     * Get rejectDetailsCode
     *
     * @return string
     */
    public function getRejectDetailsCode()
    {
        return $this->rejectDetailsCode;
    }

    /**
     * Set idFaceMatch
     *
     * @param integer $idFaceMatch
     *
     * @return NetverifyResponse
     */
    public function setIdFaceMatch($idFaceMatch)
    {
        $this->idFaceMatch = $idFaceMatch;

        return $this;
    }

    /**
     * Get idFaceMatch
     *
     * @return int
     */
    public function getIdFaceMatch()
    {
        return $this->idFaceMatch;
    }

    /**
     * Set clientIp
     *
     * @param string $clientIp
     *
     * @return NetverifyResponse
     */
    public function setClientIp($clientIp)
    {
        $this->clientIp = $clientIp;

        return $this;
    }

    /**
     * Get clientIp
     *
     * @return string
     */
    public function getClientIp()
    {
        return $this->clientIp;
    }
}

