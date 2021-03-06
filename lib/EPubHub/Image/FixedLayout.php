<?php

/*
 * This file is part of EPubHub.
 *
 * (c) 2013 KimSia Sim
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * A FixedLayout EPub Image class.
 *
 * @package    epubhub
 * @author     KimSia Sim<kimsia@storyzer.com>
 */
class EPubHub_Image_FixedLayout implements EPubHub_ImageInterface
{
    // the only settable field
    protected $path     = '';

    // use basename();  on $path
    protected $name     = '';
    // use filesize() on $path
    protected $filesize     = 0;

    // using getimagesize() to help define the following fields
    // see http://php.net/manual/en/function.getimagesize.php for more details
    // index 0 in getimagesize()
    protected $width    = 0;
    // index 1 in getimagesize()
    protected $height    = 0;
    // index 2 is the IMAGETYPE_XXX constant
    protected $imagetype = IMAGETYPE_JPEG;
    // index 3 is a text string with the correct height="yyy" width="xxx" string that can be used directly in an IMG tag
    protected $imgTagString = '';
    // mime is the correspondant MIME type of the image. 
    protected $mime = '';
    // channels will be 3 for RGB pictures and 4 for CMYK pictures.
    // bits is the number of bits for each color.
    protected $channels = '';
    protected $bits = '';

    // for more info on mimetypes, check http://www.php.net/manual/en/function.image-type-to-mime-type.php
    protected $approvedMimes = array(
        'image/gif',
        'image/jpeg',
        'image/png',
    );

    public function __construct($imagePath)
    {
        $this->setPath($imagePath);
    }

    public function updateImageSize()
    {
        $imageSize = getimagesize($this->path);
        list($this->width, $this->height, $this->imagetype,$this->imgTagString) = $imageSize;
        $this->mime = $imageSize['mime'];
        $this->channels = $imageSize['channels'];
        $this->bits = $imageSize['bits'];

        if (!in_array($this->mime, $this->approvedMimes))
        {
            // throw exception
        }
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getMime()
    {
        return $this->mime;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getImgTagString()
    {
        return $this->imgTagString;
    }

    public function getFileSize()
    {
        return $this->filesize;
    }

    public function getChannels()
    {
        return $this->channels;
    }

    public function getBits()
    {
        return $this->bits;
    }

    public function getImageType()
    {
        return $this->imagetype;
    }

    public function updateName()
    {
        $this->name = basename($this->path);
    }

    public function getName()
    {
        return $this->name;
    }

    /*
     *
     * Alias for getName() return filename of the image
     */
    public function name()
    {
        return $this->getName();
    }

    public function setPath($path)
    {
        if (!file_exists($path))
        {
            // throw exception
            echo "no such file!!";
        }
        $this->path = $path;
        $this->updateName();
        $this->updateImageSize();
        $this->updateFileSize();
    }

    public function updateFileSize()
    {
        $size = filesize($this->path);
        if ($size > self::MAX_SIZE)
        {
            // throw exception
        }
        $this->filesize = $size;
    }

}
