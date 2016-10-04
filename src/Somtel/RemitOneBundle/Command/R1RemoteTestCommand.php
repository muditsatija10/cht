<?php

namespace Somtel\RemitOneBundle\Command;

use Somtel\RemitOneBundle\Form\getBeneficiaryType;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;

class R1RemoteTestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('r1:remoteTest')
            ->setDescription('...')
            ->addArgument('method', InputArgument::REQUIRED, 'Argument description')
            ->addOption(
                'no-login',
                '',
                null,
                'Do not login with test account (need for some methods)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $method = $input->getArgument('method');
        $this->doMethod($method, $input, $output);
    }

    /**
     * @param $method
     * @param Input|InputInterface $input
     * @param Output|OutputInterface $output
     */
    public function doMethod($method, $input, $output)
    {
        $r1Service = $this->getContainer()->get('r1.remitter_service');

        $paymentMethods = [
            "Bank Transfer" => 3,
            "Card Stream" => 20,
            "Cash" => 1,
            "Cheque" => 2,
            "Mixed" => 4,
            "ORMACH" => 12,
            "ORMAuthorizeNet" => 10,
            "ORMBankTransfer" => 8,
            "ORMCheque" => 9,
            "ORMConverge" => 18,
            "ORMDummyCard" => 19,
            "ORMIveri" => 15,
            "ORMMoneyBookers" => 11,
            "ORMPayVision" => 14,
            "ORMPhone" => 13,
            "ORMPollPayment" => 17,
            "ORMSecureTrading" => 6,
            "ORMSofortBanking" => 7,
            "ORMWorldPay" => 16,
            "Wallet" => 5,
        ];
        if (!$input->getOption('no-login')) {
            $username = 'vytenis.pavalkis@gmail.com';
            $password = 'abc1q231';
           // $username = 'pheonix111114@inbox.lt';
           // $password =  '123456Abcd';


            $pin = '46937';
            $loginResponse = $r1Service->login(["username" => $username, "password" => $password]);
            if (!$loginResponse->isSuccess()) {
                $output->writeln('Login failed!');
                dump($loginResponse->getForClient());
                exit;
            }
            $session_token = $loginResponse->getOutput()["session_token"];
            $request = [
                "username" => $username,
                "session_token" => $session_token,
            ];
            $multipartRequest = [
                [
                    "name" => 'username',
                    "contents" => $username,
                ],
                [
                    "name" => 'session_token',
                    "contents" => $session_token,
                ],
            ];

            $response = null;
            switch ($method) {
                case 'login':
                    $response = $loginResponse;
                    break;
                case 'getDestinationCurrencies':
                    $response = $r1Service->getDestinationCurrencies($request);
                    break;
                case 'getRates':
                    $request += [
                        "destination_country" => 'United Arab Emirates',
                        "source_currency" => 'USD',
                        "destination_currency" => 'AED',
                    ];
                    $response = $r1Service->getRates($request);
                    break;
                case 'getConversionRate':
                    $request["from_currency"] = 'USD';
                    $request["to_currency"] = 'AED';
                    $request["amount"] = 100;
                    $response = $r1Service->getConversionRate($request);
                    break;
                case 'getCharges':
                    $request += [
                        "destination_country" => 'Somalia',
                        "trans_type" => 'Account',
                        "delivery_mechanism" => 'DEFAULT',
                        "amount_type" => 'SOURCE',
                        "amount_to_send" => '100',
                        "source_currency" => 'USD',
                        "trans_type" => 'Card Transfer',
                        "payment_method" => '8',
                        "amount_type" => 'DESTINATION',
                        "amount_to_send" => '100',
                        "service_level" => 1,
                        "collection_id" => '1',
                        "payment_method" => '5',

                        "remitter_wallet_currency" => 'USD',
                        "delivery_mechanism" => '6',
                    ];
                    $response = $r1Service->getCharges($request);
                    break;
                case 'forgotPassword':
                    $date_of_birth = '1904-09-09';
                    $request += [
                        "email_address" => 'pheonix114@inbox.lt',
                        "dob" => $date_of_birth
                    ];
                    $request2 = [
                        "email_address" => $username,
                        "dob" => $date_of_birth
                    ];
                    $response = $r1Service->forgotPassword($request2);
                    break;
                case 'changePassword':
                    $new_password = '123456Abcd';
                    $forgotPwdToken = null;
                    $response = $r1Service->changePassword(
                        $username,
                        $new_password,
                        $session_token,
                        $forgotPwdToken
                    );
                    break;
                case 'createBeneficiary':
                    $request += [
                        "name" => 'Robind Hudding',
                        "benef_fname" => 'Rodbinas Jr.',
                        "benef_lname" => 'Huddas',
                        "benef_name" => 'Rodbinas Jr Hudas',
                        "address1" => 'Bakder Streedt',
                        "city" => 'Mogadishu',
                        "country_id" => '998',
                        "dob" => '1954-09-09',
                        "telephone" => '123 023',
                        "mobile" => '094 0923',
                        "email" => 'examplfe@example11.com',
                        "id_type" => 'PASSPORT',
                        "id_details" => 'yhay1',
                        "card_number" => '',
                        "account_number" => '123123',
                        "bank" => 'Big bang',
                        "bank_branch" => 'Branch Of Big Bank',
                        "bank_branch_city" => 'London',
                        "bank_branch_state" => 'London',
                        "bank_branch_telephone" => '2131f23',
                        "bank_branch_manager" => 'Mr. Manager',
                        "benef_bank_swift_code" => 'SWX123',
                        "benef_bank_ifsc_code" => 'AA',
                        "benef_bank_account_name" => 'ABC0987676',
                    ];
                    $response = $r1Service->createBeneficiary($request);
                    break;
                case 'getBeneficiary':
                    $request += [
                        "beneficiary_id" => 200,
                    ];
                    $response = $r1Service->getBeneficiary($request);
                    break;
                case 'listBeneficiaries':
                    $request += [
                        "destination_country_id" => '123',
                    ];
                    $response = $r1Service->listBeneficiaries($request);
                    break;
                case 'getDestinationCountries':
                    $response = $r1Service->getDestinationCountries($request);
                    break;
                case 'getTransaction':
                    $request += [
                        "trans_ref" => 'RA000299000100'
                    ];
                    $response = $r1Service->getTransaction($request);
                    break;
                case 'listTransactions':
                    // Neveikia del netinkamo parametro. Kabo supporte
                    $response = $r1Service->listTransactions($request);
                    break;
                case 'getTransactionUISettings':
                    $request += [
                        "destination_country_id" => '999',
                    ];
                    $response = $r1Service->getTransactionUISettings($request);
                    break;
                case 'loginPin':
                    $request["username"] = 'pheonix114@inbox.lt';
                    $response = $r1Service->loginPin($request);
                    break;
                case 'updateRemitter':
                    $path = '/path/to/image.jpg';
                    $data = file_get_contents($path);
                    $base64 = base64_encode($data);
                    $request += [
                        "dob" => '1904-09-09',
                        "id1_type" => 'Passport',
                        "id1_start" => '2011-01-11',
                        "id1_expiry" => '2019-01-11',
                        "id1_details" => '12399008877',
                        "id1_issued_by" => 'European Union',
                        "id1_scan" => $base64,
                    ];
                    $response = $r1Service->updateRemitter($request);
                    break;
                case 'getWalletsBalance':
                    $response = $r1Service->getWalletsBalance($request);
                    break;
                case 'getWalletActivity':
                    $request["days"] = 10;
                    $response = $r1Service->getWalletActivity($request);
                    break;
                case 'createRemitter':
                    $path = '/path/to/image.jpg';
                    $data = file_get_contents($path);
                    $base64Img = base64_encode($data);
                    $remitter = [
                        "name" => 'Eerasdsd Hudinrssg',
                        "fname" => 'Robindass',
                        "lname" => 'Hudsads',
                        "address1" => 'Bakdser Street',
                        "city" => 'Mogadishu',
                        "country_id" => '999',
                        "dob" => '1954-10-09',
                        "telephone" => '003706076857',
                        "mobile" => '0037060768876',
                        "email" => 'pheonix111114@inbox.lt',
                        "password" => '123456Abcd',
                        "verify_password" => '123456Abcd',
                        "id1_type" => 'PASSPORT',
                        "id1_details" => 'yhay1',
                        "id1_expiry" => '2018-01-01',
                        "id1_scan" => $base64Img,
                        "card_number" => '',
                        "postcode" => "02222",
                        "account_number" => '123123',
                        "bank" => 'Big bang',
                        "bank_branch" => 'Branch Of Big Bank',
                        "bank_branch_city" => 'London',
                        "bank_branch_state" => 'London',
                        "bank_branch_telephone" => '213123',
                        "bank_branch_manager" => 'Mr. Manager',
                        "benef_bank_swift_code" => 'SWX123',
                        "benef_bank_ifsc_code" => 'AA',
                        "benef_bank_account_name" => 'ABC0987676',
                        "source_country_id" => '01',
                        "nationality" => 'GB',
                        "toc" => '1'
                    ];
                    $response = $r1Service->registerRemitter($remitter);
                    break;
                case 'getProfile':
                    $response = $r1Service->getprofile($request);
                    break;
                case 'getPaymentMethods':
                    $request += [
                        "destination_country_id" => '999',
                    ];
                    $response = $r1Service->getPaymentMethods($request);
                    break;
                case 'confirmRegistration':
                    $request = [
                        'email_address' => 'sometest@email.com',
                        'email_verification_code' => 'SO-ME-CO-DE',
                        'sms_verification_code' => 'SO-ME-CO-DE'
                    ];
                    $response = $r1Service->confirmRegistration($request);
                    break;
                case 'createTransaction':
                    $request += [
                        'trans_type' => 'Card Transfer',
                        'beneficiary_id' => '8',
                        'source_currency' => 'GBP',
                        'destination_currency' => 'EUR',
                        'amount_type' => 'SOURCE',
                        'amount ' => '150',
                        'payment_method' => '8',
                        'service_leve' => '1'
                    ];
                    $response = $r1Service->createTransaction($request);
                    break;
                case 'getMultipleCharges':
                    // $paymentMethods = $r1Service->getPaymentMethods([
                    //     "username" => $request["username"],
                    //     "session_token" => $request["session_token"],
                    //     "destination_country_id" => '957',
                    // ]);
                    $request += [
                        "destination_country" => '957',
                        "trans_type" => 'Account',
                        "delivery_mechanism" => 'DEFAULT',
                        "amount_type" => 'SOURCE',
                        "amount_to_send" => '1000',
                        "source_currency" => 'USD',
                        "service_level" => 1,
                        "sms_confirmation" => 0,
                        "sms_notification" => 0,
                        "sms_benef_confirmation" => 0,
                        "collection_id" => '1',
                        "payment_method" => null,

                        "remitter_wallet_currency" => 'USD',
                        "delivery_mechanism" => '6',
                    ];
                    foreach ($paymentMethods as $name => $id) {
                        $request["payment_method"] = $id;
                        $response[] = $r1Service->getCharges($request);
                    }
                    break;

                case 'quickRegister':
                    $path = '/home/robert/Desktop/fuckthat.jpg';
                    $data = file_get_contents($path);
                    $base64Img = base64_encode($data);
                    $remitter = [
                        "name" => 'NOT PROVIDED',
                        "fname" => 'NOT PROVIDED',
                        "lname" => 'NOT PROVIDED',
                        "address1" => 'NOT PROVIDED',
                        "country_id" => 'NOT PROVIDED',
                        "dob" => '1000-10-10',
                        "telephone" => '00111111111',
                        "mobile" => '0011111111',
                        "email" => 'pheonix111114@inbox.lt',
                        "password" => '123456Abcd',
                        "verify_password" => '123456Abcd',
                        "id1_type" => 'NOT PROVIDED',
                        "id1_details" => 'NOT PROVIDED',
                        "id1_expiry" => '9999-10-10',
                        "id1_scan" => $base64Img,
                        "postcode" => "NOT PROVIDED",
                        "account_number" => 'NOT PROVIDED',
                        "source_country_id" => '01',
                        "nationality" => 'GB',
                        "toc" => '1'
                    ];
                    $response = $r1Service->registerRemitter($remitter);
                    break;
                case 'updateBeneficiary':
                    $request += [
                        "name" => 'Robind Hudding',
                        "beneficiary_id" => 8,
                        "card_number" => '',
                        "account_number" => '123123',
                        "bank" => 'Big bang',
                        "bank_branch" => 'Branch Of Big Bank',
                        "bank_branch_city" => 'London',
                        "bank_branch_state" => 'London',
                        "bank_branch_telephone" => '2131f23',
                        "bank_branch_manager" => 'Mr. Manager',
                        "benef_bank_swift_code" => 'SWX123',
                        "benef_bank_ifsc_code" => 'AA',
                        "benef_bank_account_name" => 'ABC0987676',
                    ];
                    $response = $r1Service->updateBeneficiary($request);
                    break;
                case 'getWallets':
                    $response = $r1Service->getWallets($request);
                    break;
                case 'getSourceCountries':
                    $response = $r1Service->getSourceCountries($request);
                    break;
                default:
                    $response = 'Method not implemented yet.';
                    break;
            }
            dump($response);
        }

    }
}
