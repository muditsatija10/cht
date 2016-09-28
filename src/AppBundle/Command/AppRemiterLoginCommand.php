<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Time;

class AppRemiterLoginCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:remiter-login')
            ->setDescription('...')
            
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //$array=array('seed' => '667639034101739','password'=>'BEazbGJx');
        //$json = json_encode($array);
        //$json =   $encrypted = base64_encode($json);

        /**
         * Paimamas public ssl raktas ir naudojamas koduojant siunciama json.
         */
        /*$publickey=fopen("/home/robert/Projects/somtel/public.txt","r");
        $pub_key_string=fread($publickey,8192);
        fclose($publickey);
        $key_resource = openssl_get_publickey($pub_key_string);*/

        //$pwd = openssl_encrypt($json,openssl_get_cipher_methods()[0],$str);

        /*openssl_public_encrypt($json, $res, $key_resource);
        $encrypted = base64_encode($res);*/

        $path_to_server_public_key = "/home/robert/Projects/somtel/key_public.txt";
        $array=array('seed' => '667639034101739','password'=>'BEazbGJx');

        $key = file_get_contents($path_to_server_public_key);
        $key = openssl_get_publickey($key);
       // $crypted = '';
        openssl_public_encrypt(json_encode($array), $crypted , $key);

        $encrypted = base64_encode($crypted);
        $ch = curl_init();
        $data =[
            'username' => 'vytenis.pavalkis@gmail.com',
            'encrypted_data' => $encrypted,
        ];

        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_URL,"https://test.remit.by/talkremittest/remitterws/auth/login");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));



        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);

        curl_close ($ch);
        Die(print_r($server_output));


    }

}
