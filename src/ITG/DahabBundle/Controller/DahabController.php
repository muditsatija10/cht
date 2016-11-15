<?php

namespace ITG\DahabBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
use ITG\MillBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class DahabController extends BaseController
{

	/**
	 * @ApiDoc()
     * @Post("/validateCustomer")
     */

     public function postValidateCustomerAction(Request $request)
    {
    	  
    	 $postData = $request->getContent();
         $apiSecret = '222be339-9c09-45d6-be77-03c8b3e0d7c9';
         $hashString = $postData.$apiSecret;
         $hash = hash("sha256", $hashString);
        $url = "https://www.dahabonline.co.uk/talkRemit/Validate/Customer?HASH=".$hash."&API_KEY=21f3ab93-b5f9-4118-968c-6a45f551e1e6";
		$cert_file = '/var/www/html/remittance/file.crt.pem';
		$cert_key = '/var/www/html/remittance/file.key.pem';
		$cert_password = '#$SGADS9XGH927T&$%$';
		$headers = array('Content-Type: application/json');
		$data_string = $postData; 
		$ch = curl_init();
		 
		$options = array(
		    CURLOPT_CUSTOMREQUEST => "POST",
		    CURLOPT_POSTFIELDS => $data_string, 
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_FOLLOWLOCATION => true,
		    CURLOPT_SSL_VERIFYHOST => false,
		    CURLOPT_SSL_VERIFYPEER => false,
		    CURLOPT_HTTPHEADER  => $headers,
		    CURLOPT_URL => $url ,
		    CURLOPT_SSLCERT => $cert_file ,
		    CURLOPT_SSLKEY => $cert_key,
		    CURLOPT_SSLCERTPASSWD => $cert_password,
		    CURLOPT_SSLKEYPASSWD => $cert_password
		);
		 
		curl_setopt_array($ch , $options);
		 
		$output = curl_exec($ch);
		 
		if(!$output)
		{
		    echo "Curl Error : " . curl_error($ch);
		}
		else
		{
			 $retunValArray=json_decode($output);
			 if(isset($retunValArray->Customer))
			 {
			    $this->forward('app.common_controller:apiResponseAction', array('response'=>$retunValArray));
			 }
			 else
			 {
			 	 $this->forward('app.common_controller:apiResponseAction', array('response'=>'','message'=>'Invalid Customer','issuccess'=>false));
			 }	
		}
        exit();
       
    }


    /**
	 * @ApiDoc()
     * @Post("/processPayment")
     */

    public function postProcessPaymentAction(Request $request)
    {
         $postData = $request->getContent();
         $postDataArray = json_decode($postData, true);
         if(!empty($postDataArray['PayoutOption']))
         {              
              if($postDataArray['PayoutOption'] == 'cash')
              {   
              	    unset($postDataArray['PayoutOption']);
              	    $postDataArray['CustomerID'] =  $postDataArray['username'];
              	    unset($postDataArray['username']);
              	    $postDataArray['OrigReferenceNo'] =  time();
              	    $postDataArray['Comm'] = 6;
              	    $postDataArray['Option'] = 'Cash Pickup';
              	    $postData = json_encode($postDataArray);

                    $this->processDahabshiilPayment($postData);
              } 
              else
              {
              	  unset($postDataArray['PayoutOption']);
              	  unset($postDataArray['username']);
              	  $postDataArray['TransactionId'] = time();
              	  $postData = json_encode($postDataArray);
                  $this->processDahabPayment($postData);
              }	                 
         }
         else
         {
         	 $this->forward('app.common_controller:apiResponseAction', array('response'=>'','message'=>'Payout Option is missing','issuccess'=>false));
         }	
        
    }
    
    private function processDahabPayment($postData)
    {
	         $apiSecret = '222be339-9c09-45d6-be77-03c8b3e0d7c9';
	         $hashString = $postData.$apiSecret;
	         $hash = hash("sha256", $hashString);
	         $url = "https://www.dahabonline.co.uk/talkRemit/Process/Payment?HASH=".$hash."&API_KEY=21f3ab93-b5f9-4118-968c-6a45f551e1e6";
			$cert_file = '/var/www/html/remittance/file.crt.pem';
			$cert_key = '/var/www/html/remittance/file.key.pem';
			$cert_password = '#$SGADS9XGH927T&$%$';
			$headers = array('Content-Type: application/json');
			$data_string = $postData; 
			$ch = curl_init();
			 
			$options = array(
			    CURLOPT_CUSTOMREQUEST => "POST",
			    CURLOPT_POSTFIELDS => $data_string, 
			    CURLOPT_RETURNTRANSFER => true,
			    CURLOPT_FOLLOWLOCATION => true,
			    CURLOPT_SSL_VERIFYHOST => false,
			    CURLOPT_SSL_VERIFYPEER => false,
			    CURLOPT_HTTPHEADER  => $headers,
			    CURLOPT_URL => $url ,
			    CURLOPT_SSLCERT => $cert_file ,
			    CURLOPT_SSLKEY => $cert_key,
			    CURLOPT_SSLCERTPASSWD => $cert_password,
			    CURLOPT_SSLKEYPASSWD => $cert_password
			);
			 
			curl_setopt_array($ch , $options);
			 
			$output = curl_exec($ch);
			 
			if(!$output)
			{
			    echo "Curl Error : " . curl_error($ch);
			}
			else
			{
			    $retunValArray=json_decode($output);
				 if(isset($retunValArray->Transaction))
				 { 
				 	$retunValArray->TransactionId = $retunValArray->Transaction;
				 	unset($retunValArray->Transaction);
				    $this->forward('app.common_controller:apiResponseAction', array('response'=>$retunValArray));
				 }
				 else
				 {
				 	 $this->forward('app.common_controller:apiResponseAction', array('response'=>'','message'=>'Problem with request','issuccess'=>false));
				 }	
			}
	        exit();


    }

     /**
	 * @ApiDoc()
     * @Post("/sendSMS")
     */

    public function postSendSMSAction(Request $request)
    {

         $postData = $request->getContent();
         $apiSecret = '222be339-9c09-45d6-be77-03c8b3e0d7c9';
         $hashString = $postData.$apiSecret;
         $hash = hash("sha256", $hashString);
         $url = "https://www.dahabonline.co.uk/talkRemit/Send/SMS?HASH=".$hash."&API_KEY=21f3ab93-b5f9-4118-968c-6a45f551e1e6";
		$cert_file = '/var/www/html/remittance/file.crt.pem';
		$cert_key = '/var/www/html/remittance/file.key.pem';
		$cert_password = '#$SGADS9XGH927T&$%$';
		$headers = array('Content-Type: application/json');
		$data_string = $postData; 
		$ch = curl_init();
		 
		$options = array(
		    CURLOPT_CUSTOMREQUEST => "POST",
		    CURLOPT_POSTFIELDS => $data_string, 
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_FOLLOWLOCATION => true,
		    CURLOPT_SSL_VERIFYHOST => false,
		    CURLOPT_SSL_VERIFYPEER => false,
		    CURLOPT_HTTPHEADER  => $headers,
		    CURLOPT_URL => $url ,
		    CURLOPT_SSLCERT => $cert_file ,
		    CURLOPT_SSLKEY => $cert_key,
		    CURLOPT_SSLCERTPASSWD => $cert_password,
		    CURLOPT_SSLKEYPASSWD => $cert_password
		);
		 
		curl_setopt_array($ch , $options);
		 
		$output = curl_exec($ch);
		 
		if(!$output)
		{
		    echo "Curl Error : " . curl_error($ch);
		}
		else
		{
		   $retunValArray=json_decode($output);
			 if(isset($retunValArray->Message))
			 {
			    $this->forward('app.common_controller:apiResponseAction', array('response'=>$retunValArray));
			 }
			 else
			 {
			 	 $this->forward('app.common_controller:apiResponseAction', array('response'=>'','message'=>'Problem with request','issuccess'=>false));
			 }	
		}
        exit();

    }



    private function processDahabshiilPayment($postData)
    {
         $apiSecret = '222be339-9c09-45d6-be77-03c8b3e0d7c9';
         $hashString = $postData.$apiSecret;
         $hash = hash("sha256", $hashString);
         $url = "https://www.dahabonline.co.uk/talkRemit/Remittance/Send?HASH=".$hash."&API_KEY=21f3ab93-b5f9-4118-968c-6a45f551e1e6";
		$cert_file = '/var/www/html/remittance/file.crt.pem';
		$cert_key = '/var/www/html/remittance/file.key.pem';
		$cert_password = '#$SGADS9XGH927T&$%$';
		$headers = array('Content-Type: application/json');
		$data_string = $postData; 
		$ch = curl_init();
		 
		$options = array(
		    CURLOPT_CUSTOMREQUEST => "POST",
		    CURLOPT_POSTFIELDS => $data_string, 
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_FOLLOWLOCATION => true,
		    CURLOPT_SSL_VERIFYHOST => false,
		    CURLOPT_SSL_VERIFYPEER => false,
		    CURLOPT_HTTPHEADER  => $headers,
		    CURLOPT_URL => $url ,
		    CURLOPT_SSLCERT => $cert_file ,
		    CURLOPT_SSLKEY => $cert_key,
		    CURLOPT_SSLCERTPASSWD => $cert_password,
		    CURLOPT_SSLKEYPASSWD => $cert_password
		);
		 
		curl_setopt_array($ch , $options);
		$output = curl_exec($ch);
		 
		if(!$output)
		{
		    echo "Curl Error : " . curl_error($ch);
		}
		else
		{
		    $retunValArray=json_decode($output);
			 if(isset($retunValArray->TransactionId))
			 {
			    $this->forward('app.common_controller:apiResponseAction', array('response'=>$retunValArray));
			 }
			 else
			 {
			 	 $this->forward('app.common_controller:apiResponseAction', array('response'=>'','message'=>'Problem with request','issuccess'=>false));
			 }	
			 	
		}
        exit();

    }


     /**
	 * @ApiDoc()
     * @Post("/agentCodeByCountry")
     */
    
    public function postAgentCodeByCountryAction(Request $request)
    {
          $postData = $request->getContent();
          $postArray = json_decode($postData,true);
          $country = $postArray['country'];
          if(!empty($country))
          {
                $countryArray = $this->getAgentCodeByCountry($country);
                $responseArray = array("status" => "success", "message" => "Success Response", "data" => $countryArray);
                echo json_encode($responseArray);
                exit;
          }
          else
         {
         	 $this->forward('app.common_controller:apiResponseAction', array('response'=>'','message'=>'Country is missing','issuccess'=>false));
         } 	

    }


    private function getAgentCodeByCountry($country)
    {
       $country = strtolower($country);
       $agentArray = array('australia' => array('MLB' => 'MELBOURNE HEAD OFFICE', 'SYD' => 'SYDNEY-HEAD OFFICE'),
        	                'austria' => array('AUS' => 'VIENNA'),
        	                'bahrain' => array('BHR' => 'ZANJ EXCHANGE'),
        	                'belgium' => array('BLU' => 'BELGIUM'),
        	                'canada' => array('CND' => 'Canada'),
        	                'congo' => array('CNG' => 'CONGO'),
        	                'denmark' => array('CPH' => 'DENMARK', 'DKK' => 'Copenhagen Safari Shop'),
        	                'djibouti' => array('DJB' => 'DJIBOUTI'),
        	                'egypt' => array('UBE' => 'EGYPT -UBE'),
        	                'ethiopia' => array('NIB' => 'Nib International Bank', 'DDW' => 'DIRE DAWA', 'DGB' => 'DEBUB GLOBAL', 'CBE' => 'COMMERCIAL BANK OF ETHIOPIA', 'BOA' => 'Abbysinia Bank', 'BUN' => 'BUNA BANK', 'ABE' => 'ABBY BANK', 'ADB' => 'ADDIS BANK', 'ADD' => 'ADDIS ABABA', 'JJG' => 'JIGJIGA'),
        	                'finland' => array('FIN' => 'HELSENKI'),
        	                'france' => array('FCR' => 'France'),
        	                'gambia' => array('GBM' => 'BANJUL', 'GMB' => 'KAIRABA AVENUE'),
        	                'ghana' => array('GHA' => 'AFLAO,VOLTA'),
        	                'ireland' => array('IRE' => 'DUBLIN', 'IRH' => 'DUBLIN  DAHABSHIL'),
        	                'italy' => array('MPL' => 'Milano'),
        	                'kenya' => array('KMT' => 'KENYA'),
        	                'kuwait' => array('KWA' => 'KUWAIT', 'KWB' => 'KUWAIT'),
        	                'malaysia' => array('MLY' => 'Kuala Lumpur'),
        	                'malta' => array('MTA' => 'MALTA'),
        	                'morocco' => array('MEA' => 'Morocco'),
        	                'nepal' => array('KMN' => 'KATHMANDU'),
        	                'netherlands' => array('HLD' => 'HOLLAND'),
        	                'new zealand' => array('NZD' => 'WELLINGTON','NZL' => 'NEWZEALAND'),
        	                'norway' => array('NRA' => 'Norway Main Office', 'NRB' => 'OSLO - Gronland', 'NRD' => 'Oslo - Gronland T-Bane', 'NRF' => 'Stavanger', 'NRG' => 'DRAMMEN', 'NRJ' => 'OSLO NORWAY SOMALI MARKET', 'NRK' => 'Bergen'),
        	                'qatar' => array('QAT' => 'Doha - Qatar'),
        	                'rwanda' => array('RWD' => 'RWANDA DAHABSHIIL'),
        	                'senegal' => array('MXP' => 'MONEY EXPRESS SA'),
        	                'somalia' => array('WJL' => 'WAJAALE', 'SHK' => 'SHEIKH', 'HRG' => 'HARGEISA', 'GHY' =>'GARBAHAAREY', 'GLK' => 'GALKACAYO NORTH', 'GLM' =>'GALCAKIO GALMUDUG', 'GRW' => 'GAROWE', 'HDF' => 'HADDAFTIMO', 'GEB' => 'GEBILEY', 'MUQ' => 'MUQDISHO HQ - BAKAARAHA', 'MPS' => 'Mobile Payment', 'LAS' => 'LAS-ANOD HEAD QUARTER', 'LOW' => 'LOWYACADA', 'KSM' => 'Kismaayo', 'DHM' =>'DHUUSAMAREEB', 'DKH' => 'KHUDAAR','CDD' => 'CADAADO', 'CER' => 'CEERIGABO', 'CWQ' => 'CAABUDWAAQ', 'BAY' => 'BAYDHABA', 'BDN' => 'BADHAN', 'BDR' => 'BARDHERE', 'BUR' => 'Burao', 'BWY' => 'BELEDWEYNE', 'BXW' => 'BELEDXAAWA', 'CAF'=> 'CAFWEYNE', 'BOR' => 'BORAMA', 'BRB' => 'BERBERA', 'BSS' => 'BOSASO', 'BUH' => 'BUUHOODLE HEAD OFFICE'),
        	                'sauth africa' => array('ZAR' => 'SOUTH AFRICA', 'JBA' => 'JUBA'),
        	                'spain' => array('MEX' => 'MONEY EXCHANGE'),
        	                'sudan' => array('SDN' => 'KHARTOUM'),
        	                'sweden' => array('ORB' => 'OREBRO', 'STK' => 'STOCKHOLM ( STK )', 'SWA' => 'STOCKHOLM', 'SWB' => 'GOTHENBURG', 'SWC' => 'GOTHENBURG', 'SWD' => 'BORLANGE-SWEDEN', 'SWF' => 'NYKOPING', 'SWG' => 'GOTHENBURG', 'SWH' => 'MALMO-SWEDEN', 'SWJ' => 'GOTHENBURG', 'SWM' => 'KIRUNA', 'SWO' => 'VASTERAS', 'SWP' => 'GOTHENBURG- SWEDEN', 'SWQ' => 'VÄXJÖ', 'MLL'=> 'MALMO 2', 'JON' => 'Jönköping'),
        	                'switzerland' => array('ZRI' => 'ZURICH'),
        	                'tanzania' => array('DSM' => 'DAR ES SALAAM'),
        	                'turkey' => array('ATK' => 'AKTIF BANK - TURKEY'),
        	                'uae' =>array('DUB'=> 'DUBAI', 'FJR' => 'Dubai Frij Al-Murar'),
        	                'uganda' => array('KPL' => 'Kampala'),
        	                'united kingdom' => array('UK' => 'UK'),
        	                'united states' => array('MIN' => 'MINNESOTA', 'CLM' => 'COLUMBUS HQ'),
        	                'yemen' => array('KRE' => 'Al-Kuraimi Islamic Bank', 'YEM' => 'YEMEN')
         	);

       return $agentArray[$country];


    }

}
