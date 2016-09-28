<?php

namespace Somtel\PipBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * PipCashinOrder
 *
 * @ORM\Table(name="pip_cashin_order")
 * @ORM\Entity(repositoryClass="Somtel\PipBundle\Repository\PipCashinOrderRepository")
 */
class PipCashinOrder
{

    /*
     *  Possible order statuses.
     */
    const STATUS_PENDING = 'PENDING'; // Order is received/created.
    const STATUS_EXPIRED = 'EXPIRED'; // Order was not paid and is expired. Final state.
    const STATUS_PAID = 'PAID'; // Order is paid. Final state.


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Groups({"id"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="barcode", type="string", length=255, unique=true)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_barcode"})
     */
    private $barcode;

    /**
     * @var string
     *
     * @ORM\Column(name="vendor_name", type="string", length=500)
     *
     * @JMS\Groups({"pip_cashin_order_vendor_name"})
     */
    private $vendorName;

    /**
     * @var string
     *
     * @ORM\Column(name="vendor_order_reference", type="string", length=500)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_vendor_order_reference"})
     */
    private $vendorOrderReference;

    /**
     * @JMS\Expose
     *
     * @ORM\Column(name="order_value", type="decimal", precision=19, scale=4)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_order_value"})
     */
    private $orderValue;

    /**
     * @var string
     *
     * @ORM\Column(name="order_currency_code", type="string", length=64)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_order_currency_code"})
     */
    private $orderCurrencyCode;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_email", type="string", length=500)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_customer_email"})
     */
    private $customerEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="signature", type="string", length=500, nullable=true)
     *
     * @JMS\Groups({"pip_cashin_order_signature"})
     */
    private $signature;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_name", type="string", length=500, nullable=true)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_customer_name"})
     */
    private $customerName;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_mobile_number", type="string", length=500, nullable=true)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_customer_mobile_number"})
     */
    private $customerMobileNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_language", type="string", length=500, nullable=true)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_customer_language"})
     */
    private $customerLanguage;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_address_line1", type="string", length=500, nullable=true)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_customer_address_line_1"})
     */
    private $customerAddressLine1;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_address_line2", type="string", length=500, nullable=true)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_customer_address_line_2"})
     */
    private $customerAddressLine2;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_address_line3", type="string", length=500, nullable=true)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_customer_address_line_3"})
     */
    private $customerAddressLine3;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_address_line4", type="string", length=500, nullable=true)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_customer_address_line_4"})
     */
    private $customerAddressLine4;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_address_line5", type="string", length=500, nullable=true)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_customer_address_line_5"})
     */
    private $customerAddressLine5;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_address_line6", type="string", length=500, nullable=true)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_customer_address_line_6"})
     */
    private $customerAddressLine6;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_country", type="string", length=500, nullable=true)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_customer_country"})
     */
    private $customerCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_timezone", type="string", length=500, nullable=true)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_customer_timezone"})
     */
    private $customerTimezone;

    /**
     * @var string
     *
     * @ORM\Column(name="extra_info", type="string", length=500, nullable=true)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_extra_info"})
     */
    private $extraInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="reply_url", type="string", length=500, nullable=true)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_reply_url"})
     */
    private $replyUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=true)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_created_date"})
     */
    private $createdDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiry_date", type="datetime", nullable=true)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_expiry_date"})
     */
    private $expiryDate;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=500, nullable=true)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_reference"})
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_status"})
     */
    private $status;

    /**
     * @ORM\Column(name="total_value", type="decimal", precision=19, scale=4, nullable=true)
     *
     * @JMS\Groups({"pip_cashin_order_list", "pip_cashin_order_total_value"})
     */
    private $totalValue;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @JMS\Groups({"pip_cashin_order_user"})
     */
    private $user;


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
     * Set vendorName
     *
     * @param string $vendorName
     *
     * @return PipCashinOrder
     */
    public function setVendorName($vendorName)
    {
        $this->vendorName = $vendorName;

        return $this;
    }

    /**
     * Get vendorName
     *
     * @return string
     */
    public function getVendorName()
    {
        return $this->vendorName;
    }

    /**
     * Set vendorOrderReference
     *
     * @param string $vendorOrderReference
     *
     * @return PipCashinOrder
     */
    public function setVendorOrderReference($vendorOrderReference)
    {
        $this->vendorOrderReference = $vendorOrderReference;

        return $this;
    }

    /**
     * Get vendorOrderReference
     *
     * @return string
     */
    public function getVendorOrderReference()
    {
        return $this->vendorOrderReference;
    }

    /**
     * Set orderValue
     *
     * @return PipCashinOrder
     */
    public function setOrderValue($orderValue)
    {
        $this->orderValue = $orderValue;

        return $this;
    }

    /**
     * Get orderValue
     */
    public function getOrderValue()
    {
        return $this->orderValue;
    }

    /**
     * Set orderCurrencyCode
     *
     * @param string $orderCurrencyCode
     *
     * @return PipCashinOrder
     */
    public function setOrderCurrencyCode($orderCurrencyCode)
    {
        $this->orderCurrencyCode = $orderCurrencyCode;

        return $this;
    }

    /**
     * Get orderCurrencyCode
     *
     * @return string
     */
    public function getOrderCurrencyCode()
    {
        return $this->orderCurrencyCode;
    }

    /**
     * Set customerEmail
     *
     * @param string $customerEmail
     *
     * @return PipCashinOrder
     */
    public function setCustomerEmail($customerEmail)
    {
        $this->customerEmail = $customerEmail;

        return $this;
    }

    /**
     * Get customerEmail
     *
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->customerEmail;
    }

    /**
     * Set signature
     *
     * @param string $signature
     *
     * @return PipCashinOrder
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;

        return $this;
    }

    /**
     * Get signature
     *
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Set customerName
     *
     * @param string $customerName
     *
     * @return PipCashinOrder
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;

        return $this;
    }

    /**
     * Get customerName
     *
     * @return string
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * Set customerMobileNumber
     *
     * @param string $customerMobileNumber
     *
     * @return PipCashinOrder
     */
    public function setCustomerMobileNumber($customerMobileNumber)
    {
        $this->customerMobileNumber = $customerMobileNumber;

        return $this;
    }

    /**
     * Get customerMobileNumber
     *
     * @return string
     */
    public function getCustomerMobileNumber()
    {
        return $this->customerMobileNumber;
    }

    /**
     * Set customerLanguage
     *
     * @param string $customerLanguage
     *
     * @return PipCashinOrder
     */
    public function setCustomerLanguage($customerLanguage)
    {
        $this->customerLanguage = $customerLanguage;

        return $this;
    }

    /**
     * Get customerLanguage
     *
     * @return string
     */
    public function getCustomerLanguage()
    {
        return $this->customerLanguage;
    }

    /**
     * Set customerAddressLine1
     *
     * @param string $customerAddressLine1
     *
     * @return PipCashinOrder
     */
    public function setCustomerAddressLine1($customerAddressLine1)
    {
        $this->customerAddressLine1 = $customerAddressLine1;

        return $this;
    }

    /**
     * Get customerAddressLine1
     *
     * @return string
     */
    public function getCustomerAddressLine1()
    {
        return $this->customerAddressLine1;
    }

    /**
     * Set customerAddressLine2
     *
     * @param string $customerAddressLine2
     *
     * @return PipCashinOrder
     */
    public function setCustomerAddressLine2($customerAddressLine2)
    {
        $this->customerAddressLine2 = $customerAddressLine2;

        return $this;
    }

    /**
     * Get customerAddressLine2
     *
     * @return string
     */
    public function getCustomerAddressLine2()
    {
        return $this->customerAddressLine2;
    }

    /**
     * Set customerAddressLine3
     *
     * @param string $customerAddressLine3
     *
     * @return PipCashinOrder
     */
    public function setCustomerAddressLine3($customerAddressLine3)
    {
        $this->customerAddressLine3 = $customerAddressLine3;

        return $this;
    }

    /**
     * Get customerAddressLine3
     *
     * @return string
     */
    public function getCustomerAddressLine3()
    {
        return $this->customerAddressLine3;
    }

    /**
     * Set customerAddressLine4
     *
     * @param string $customerAddressLine4
     *
     * @return PipCashinOrder
     */
    public function setCustomerAddressLine4($customerAddressLine4)
    {
        $this->customerAddressLine4 = $customerAddressLine4;

        return $this;
    }

    /**
     * Get customerAddressLine4
     *
     * @return string
     */
    public function getCustomerAddressLine4()
    {
        return $this->customerAddressLine4;
    }

    /**
     * Set customerAddressLine5
     *
     * @param string $customerAddressLine5
     *
     * @return PipCashinOrder
     */
    public function setCustomerAddressLine5($customerAddressLine5)
    {
        $this->customerAddressLine5 = $customerAddressLine5;

        return $this;
    }

    /**
     * Get customerAddressLine5
     *
     * @return string
     */
    public function getCustomerAddressLine5()
    {
        return $this->customerAddressLine5;
    }

    /**
     * Set customerAddressLine6
     *
     * @param string $customerAddressLine6
     *
     * @return PipCashinOrder
     */
    public function setCustomerAddressLine6($customerAddressLine6)
    {
        $this->customerAddressLine6 = $customerAddressLine6;

        return $this;
    }

    /**
     * Get customerAddressLine6
     *
     * @return string
     */
    public function getCustomerAddressLine6()
    {
        return $this->customerAddressLine6;
    }

    /**
     * Set customerCountry
     *
     * @param string $customerCountry
     *
     * @return PipCashinOrder
     */
    public function setCustomerCountry($customerCountry)
    {
        $this->customerCountry = $customerCountry;

        return $this;
    }

    /**
     * Get customerCountry
     *
     * @return string
     */
    public function getCustomerCountry()
    {
        return $this->customerCountry;
    }

    /**
     * Set customerTimezone
     *
     * @param string $customerTimezone
     *
     * @return PipCashinOrder
     */
    public function setCustomerTimezone($customerTimezone)
    {
        $this->customerTimezone = $customerTimezone;

        return $this;
    }

    /**
     * Get customerTimezone
     *
     * @return string
     */
    public function getCustomerTimezone()
    {
        return $this->customerTimezone;
    }

    /**
     * Set extraInfo
     *
     * @param string $extraInfo
     *
     * @return PipCashinOrder
     */
    public function setExtraInfo($extraInfo)
    {
        $this->extraInfo = $extraInfo;

        return $this;
    }

    /**
     * Get extraInfo
     *
     * @return string
     */
    public function getExtraInfo()
    {
        return $this->extraInfo;
    }

    /**
     * Set replyUrl
     *
     * @param string $replyUrl
     *
     * @return PipCashinOrder
     */
    public function setReplyUrl($replyUrl)
    {
        $this->replyUrl = $replyUrl;

        return $this;
    }

    /**
     * Get replyUrl
     *
     * @return string
     */
    public function getReplyUrl()
    {
        return $this->replyUrl;
    }

    /**
     * Set barcode
     *
     * @param string $barcode
     *
     * @return PipCashinOrder
     */
    public function setBarcode($barcode)
    {
        $this->barcode = $barcode;

        return $this;
    }

    /**
     * Get barcode
     *
     * @return string
     */
    public function getBarcode()
    {
        return $this->barcode;
    }

    /**
     * Set totalValue
     *
     * @return PipCashinOrder
     */
    public function setTotalValue($totalValue)
    {
        $this->totalValue = $totalValue;

        return $this;
    }

    /**
     * Get totalValue
     */
    public function getTotalValue()
    {
        return $this->totalValue;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return PipCashinOrder
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = new \DateTime($createdDate);

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate ? clone $this->createdDate : null;
    }

    /**
     * Set expiryDate
     *
     * @param \DateTime $expiryDate
     *
     * @return PipCashinOrder
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = new \DateTime($expiryDate);

        return $this;
    }

    /**
     * Get expiryDate
     *
     * @return \DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiryDate ? clone $this->expiryDate : null;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return PipCashinOrder
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return PipCashinOrder
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return PipCashinOrder
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
