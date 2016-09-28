<?php

namespace Somtel\PipBundle\Service;

class Util
{

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function prepareOrder($orderData)
    {
        $defaults = [
            "vendorName" => $this->getMerchantName(),
            "vendorOrderReference" => '',
            "orderValue" => '',
            "orderCurrencyCode" => 'EUR',
            "customerEmail" => '',
            "customerName" => '',
        ];
        $mergedOrder =  $orderData + $defaults;

        $signedOrder = $this->signOrder($mergedOrder, $this->getMerchantSecret());

        return $signedOrder;
    }
    /*
     * Sign order.
     *
     * @param $order array Order array. Must have vendorName, orderValue, customerEmail, vendorOrderReference getters.
     * @param $secret string Secret key from merchants.pip-it.net 'Merchant Profile'

     * @return array Order array with addional `signature` key which contains signature.
     */
    public function signOrder($order, $secret)
    {
        $signature = $this->getOrderSignature(
            $order["vendorName"],
            $order["orderValue"],
            $order["customerEmail"],
            $order["vendorOrderReference"],
            $secret
        );

        $order["signature"] = $signature;
        return $order;
    }

    public function getOrderSignature($vendorName, $orderValue, $customerEmail, $orderReference, $secret)
    {
        $orderString = $vendorName.$orderValue.$customerEmail.$orderReference.$secret;
        return sha1($orderString);
        /*
         * Code below is taken from PiP's own magento module.
         * Leaving it here as a reference.
         *
         * $vendor_name - merchant login name
         * $order_value - order value (sum)
         * $email - receivers email
         * $order_id - order's vendor reference (?)
         * $secret - secret key from merchants.pip-it.net 'Merchant Profile'
         *
         // Generate signature
         $sign = sha1($vendor_name.$order_value.$email.$order_id.$secret);

        */
    }

    public function getMerchantSecret()
    {
        return $this->container->getParameter('pip_merchant_secret');
    }

    public function getMerchantName()
    {
        return $this->container->getParameter('pip_merchant_username');
    }

    public function getOrderDocumentUrl($barcode)
    {
        $baseUrl = $this->container->getParameter('pip_api_endpoint');
        $fullUrl = $baseUrl.'/os/mgr/order/{orderBarcode}.html';
        return str_replace('{orderBarcode}', $barcode, $fullUrl);
    }
}
