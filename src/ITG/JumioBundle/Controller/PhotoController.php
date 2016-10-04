<?php

namespace ITG\JumioBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
use ITG\JumioBundle\Entity\Netverify;
use ITG\JumioBundle\Event\NetverifyRequestChangeEvent;
use ITG\JumioBundle\EventListener\NetverifyRequestChangeListener;
use ITG\MillBundle\Controller\BaseController;
use ITG\MillBundle\Form\UploadType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

class PhotoController extends BaseController
{
    protected $listGroups = ['id', 'list', 'netverify_list'];
    protected $detailGroups = ['id', 'netverify_list', 'netverify_details', 'netverify_response_list', 'netverify_response_details'];
    
    /**
     * Post a photo of the front of the id
     *
     * @ApiDoc()
     *
     * @Post("/front/{userReference}")
     *
     * @ParamConverter("userReference", class="ITGJumioBundle:Netverify", options={
     *     "repository_method" = "findOrCreate"
     * })
     */
    public function postFrontAction(Netverify $userReference, Request $request)
    {
        $form = $this->createPostForm(UploadType::class);
        $form->handleRequest($request);

        if($form->isValid())
        {
            $file = $this->handleUpload($request);
            $userReference->setPhotoFront($file);
            $this->getDoctrine()->getManager()->flush();
            $this->dispatchEvent($userReference);

            return $this->show($userReference, $this->detailGroups);
        }

        return $this->formError($form);
    }

    /**
     * Post a photo of the back of the id
     *
     * @ApiDoc()
     *
     * @Post("/back/{userReference}")
     *
     * @ParamConverter("userReference", class="ITGJumioBundle:Netverify", options={
     *     "repository_method" = "findOrCreate"
     * })
     */
    public function postBackAction(Netverify $userReference, Request $request)
    {
        $form = $this->createPostForm(UploadType::class);
        $form->handleRequest($request);

        if($form->isValid())
        {
            $file = $this->handleUpload($request);
            $userReference->setPhotoBack($file);
            $this->getDoctrine()->getManager()->flush();
            $this->dispatchEvent($userReference);

            return $this->show($userReference, $this->detailGroups);
        }

        return $this->formError($form);
    }

    /**
     * Post a photo of person's face for verification
     *
     * @ApiDoc()
     *
     * @Post("/face/{userReference}")
     *
     * @ParamConverter("userReference", class="ITGJumioBundle:Netverify", options={
     *     "repository_method" = "findOrCreate"
     * })
     */
    public function postFaceAction(Netverify $userReference, Request $request)
    {
        $form = $this->createPostForm(UploadType::class);
        $form->handleRequest($request);

        if($form->isValid())
        {
            $file = $this->handleUpload($request);
            $userReference->setPhotoFace($file);
            $this->getDoctrine()->getManager()->flush();
            $this->dispatchEvent($userReference);

            return $this->show($userReference, $this->detailGroups);
        }

        return $this->formError($form);
    }

    // ===== Private =====
    private function handleUpload(Request $request)
    {
        $dir = 'uploads/photos';
        $svc = $this->get('itg_mill.file_upload_handler');
        /** @var File $file */
        $file = $svc->upload($request, $dir);
        $filename = $file->getFilename();

        return "$dir/$filename";
    }

    private function dispatchEvent(Netverify $entity)
    {
        $event = new NetverifyRequestChangeEvent($entity);
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch($event::NAME, $event);
    }
}
