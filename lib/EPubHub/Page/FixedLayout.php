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
 * A FixedLayout EPub Page class.
 *
 * @package    epubhub
 * @author     KimSia Sim<kimsia@storyzer.com>
 */
class EPubHub_Page_FixedLayout implements EPubHub_PageInterface
{
    protected $name     = '';
    protected $mimeType = '';
    protected $path     = '';
    protected $size     = 0;

    // for more info on mimetypes, check http://www.php.net/manual/en/function.image-type-to-mime-type.php
    protected $approvedMimeTypes = array(
        'image/gif',
        'image/jpeg',
        'image/png',
    );

    public function __construct($path)
    {
        $this->path = $path;
        $this->updateMIMEType();
        $this->updateName();
    }

    public function updateMIMEType()
    {
        $file       = $this->path;
        $file_info  = new finfo(FILEINFO_MIME);  // object oriented approach!
        $mime_type  = $file_info->buffer(file_get_contents($file));  // e.g. gives "image/jpeg"

        if (!in_array($mime_type, $this->approvedMimeTypes))
        {
            // throw exception
        }
        $this->mimeType = $mime_type;
    }

    public function getMIMEType()
    {
        return $this->mimeType;
    }

    public function updateName()
    {
        $this->name = basename($this->path);
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPath($path)
    {
        if (!file_exists($path))
        {
            // throw exception
        }
        $this->path = $path;
        $this->updateName();
        $this->updateMIMEType();
    }
}
