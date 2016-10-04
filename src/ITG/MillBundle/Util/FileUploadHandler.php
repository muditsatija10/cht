<?php

namespace ITG\MillBundle\Util;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class FileUploadHandler
{
    private $rootDir;
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->rootDir = $container->getParameter('kernel.root_dir') . '/../';
        $this->container = $container;
    }

    /**
     * Moves uploaded file to a specified directory with specified or original file name
     *
     * @param Request $request Actual request from which we will get the file
     * @param string $dir A directory to move file to, defaults to 'uploads'
     * @param null $name New file name. Will use uploaded file name if not set
     * @param bool $addExtension Should we add an extension to uploaded file. Will use the uploaded extension
     * @param string $fileName Form field where file is located. Defaults to 'file'
     * @return File
     */
    public function moveFile(Request $request, $dir = 'uploads', $name = null, $addExtension = true, $fileName = 'file')
    {
        /** @var UploadedFile $file */
        $file = $request->files->get($fileName);
        $ext = $addExtension ? '.' . $file->getClientOriginalExtension() : '';
        $dir = $this->rootDir . $dir;

        if($name)
        {
            $name .= $ext;
        }
        else
        {
            $name = $file->getClientOriginalName() . $ext;
        }

        return $file->move($dir, $name);
    }

    /**
     * Moves uploaded file to a specified directory with GUID as a filename and no extension.
     * Good for hiding non secured file uploads, such as user uploads, tickets, documents, etc.
     *
     * @param Request $request Actual request from which we will get the file
     * @param string $dir A directory to move file to, defaults to 'uploads'
     * @param string $fileName Form field where file is located. Defaults to 'file'
     * @return File
     */
    public function upload(Request $request, $dir = 'uploads', $fileName = 'file')
    {
        $guidService = $this->container->get('itg_mill.guid_generator');

        /** @var File $file */
        $file = $this->moveFile($request, $dir, $guidService->generate(), false, $fileName);

        return $file;
    }

    /**
     * Generate a thumbnail for set image file and save it
     *
     * @param File $file Actual image file we want to create a thumbnail for
     * @param int $x Thumbnail width. If set to 0 will try to adapt by height
     * @param int $y Thumbnail height. If set to 0 will try to adapt by width
     * @param bool $fit If set to true will try to fit the entire image to a box. If set to false will scale the image by the lowest dimension and crop it to a box
     * @param string $name Thumbnail name together with extension. If set to null will use the original file name and append '_thumb' to the end of the filename
     * @param bool $ignoreProportions Should we ignore original image proportions and just stretch the image
     * @param string $dir Save thumbnail to this directory. If not set, will use the directory of original image
     * @return string
     */
    public function generateThumbnail(File $file, $x, $y = 0, $fit = false, $name = null, $ignoreProportions = false, $dir = null)
    {
        // Check to see if arguments are valid
        // IF proportions are ignored
        // AND any dimension is less than 0
        if($ignoreProportions && !($x > 0 || $y > 0))
        {
            throw new \InvalidArgumentException('If you are ignoring proportions, then X and Y must be set');
        }
        elseif($x <= 0 && $y <= 0)
        {
            throw new \InvalidArgumentException('Both image dimensions cannot be 0 or negative');
        }

        // If name is not set, just append '_thumb' at the end of the filename
        if(!$name)
        {
            $ext = $file->getExtension();
            $noExt = $file->getBasename($ext ? ".$ext" : null);
            $name = $noExt . '_thumb';

            if($ext)
            {
                $name .= ".$ext";
            }
        }

        // If directory is not set, then keep the file in the original directory
        if(!$dir)
        {
            $dir = $file->getPath();
        }

        // Create image thumbnail
        $originalName = $file->getRealPath();
        $src = $this->imagecreatefromfile($originalName);

        // Get image scale
        $size = getimagesize($originalName);
        if($fit)
        {
            $scale = $size[0] < $size[1] ? 256 / $size[0] : 256 / $size[1];
        }
        else
        {
            // Scale by width
            if($y <= 0)
            {
                $scale = $x / $size[0];
            }
            // Scale by height
            else
            {
                $scale = $y / $size[1];
            }
        }

        $thumb = imagecreatetruecolor($size[0] * $scale, $size[1] * $scale);
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
        imagealphablending($src, true);

        // Get position of original image from where it should be copied
        $originalX = 0;
        $originalY = 0;

        if($x > 0)
        {
            $originalX = ($size[0] - ($x / $scale)) / 2;
        }

        if($y > 0)
        {
            $originalY = ($size[1] - ($y / $scale)) / 2;
        }

        if($originalX < 0 || $ignoreProportions)
        {
            $originalX = 0;
        }

        if($originalY < 0 || $ignoreProportions)
        {
            $originalY = 0;
        }

        imagecopyresampled($thumb, $src, 0, 0, $originalX, $originalY, $size[0] * $scale, $size[1] * $scale, $size[0], $size[1]);

        $this->imagefile($thumb, "$dir/$name");

        return "$dir/$name";
    }

    /**
     * Create image resource from file independent of image type
     *
     * @param $filename string Path to file
     * @return resource
     */
    public function imagecreatefromfile($filename)
    {
        if(!file_exists($filename))
        {
            throw new \InvalidArgumentException("File $filename not found");
        }

        switch (strtolower( pathinfo($filename, PATHINFO_EXTENSION) ))
        {
            case 'jpeg':
            case 'jpg':
                return imagecreatefromjpeg($filename);
                break;

            case 'png':
                return imagecreatefrompng($filename);
                break;

            case 'gif':
                return imagecreatefromgif($filename);
                break;

            default:
                throw new \InvalidArgumentException("File $filename is not valid jpg, png or gif image");
        }
    }

    /**
     * Save image resource independent of filename
     *
     * @param $image resource Image resource we want to save
     * @param $filename string Full path of the file we're saving
     */
    public function imagefile($image, $filename)
    {
        // Extract extension from filename
        $exploded = explode('.', $filename);
        $ext = end($exploded);

        switch (strtolower($ext))
        {
            case 'jpeg':
            case 'jpg':
                imagejpeg($image, $filename);
                break;

            case 'png':
                imagepng($image, $filename);
                break;

            case 'gif':
                imagegif($image, $filename);
                break;

            default:
                throw new \InvalidArgumentException("File $filename is not valid jpg, png or gif image");
        }
    }

    /**
     * Remove root directory from filename
     *
     * @param string $filename
     * @return string
     */
    public function derootFilename($filename)
    {
        return str_replace($this->rootDir, '', $filename);
    }
}