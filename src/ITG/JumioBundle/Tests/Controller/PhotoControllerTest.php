<?php

namespace ITG\JumioBundle\Tests\Controller;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use ITG\UserBundle\DataFixtures\ORM\LoadTokenData;
use ITG\UserBundle\DataFixtures\ORM\LoadUserData;
use ITG\UserBundle\Entity\Token;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PhotoControllerTest extends WebTestCase
{
    /** @var ReferenceRepository */
    private $fixtures;

    /** @var Client */
    private $client;

    private $endpoint;

    public function setUp()
    {
        $this->fixtures = $this->loadFixtures([

            LoadUserData::class,
            LoadTokenData::class,

        ], null, 'doctrine', 2)->getReferenceRepository();

        $this->endpoint = '/jumio/photo';

        /** @var Token $token */
        $token = $this->fixtures->getReference('ITGUser-token-admin');

        $this->client = static::makeClient(false, [
            'HTTP_X-AUTH-TOKEN' => $token->getToken(),
            'content-type' => 'application/json'
        ]);
    }

    public function testPhotoUpload()
    {
        $c = $this->client;
        $endpoint = $this->endpoint;

        $fs = new Filesystem();
        $fs->copy(__DIR__ . '/fake.png', __DIR__ . '/fake_tmp1.png');
        $fs->copy(__DIR__ . '/fake.png', __DIR__ . '/fake_tmp2.png');
        $fs->copy(__DIR__ . '/fake.png', __DIR__ . '/fake_tmp3.png');

        $uploadedFile1 = new UploadedFile(__DIR__ . '/fake_tmp1.png', 'fake.png');
        $uploadedFile2 = new UploadedFile(__DIR__ . '/fake_tmp2.png', 'fake.png');
        $uploadedFile3 = new UploadedFile(__DIR__ . '/fake_tmp3.png', 'fake.png');

        // Check front
        $c->request('POST', "$endpoint/front/test1", [], ['file' => $uploadedFile1]);
        $this->assertStatusCode(200, $c);

        // Check back
        $c->request('POST', "$endpoint/back/test1", [], ['file' => $uploadedFile2]);
        $this->assertStatusCode(200, $c);

        // Check face
        $c->request('POST', "$endpoint/face/test1", [], ['file' => $uploadedFile3]);
        $this->assertStatusCode(200, $c);

        // Get response
        $res = json_decode($c->getResponse()->getContent(), true);

        dump($res);
    }
}