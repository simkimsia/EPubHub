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
    protected $image      = '';
    protected $folderPath = '';
    protected $mimeType   = '';

    public function __construct($image, $mimeType = 'image/jpeg')
    {
        $this->image    = $image;
        $this->mimeType = $mimeType;
    }

    public function getMIMEType()
    {
        $file       = $this->folderPath . DIRECTORY_SEPARATOR . $this->image;
        $file_info  = new finfo(FILEINFO_MIME);  // object oriented approach!
        $mime_type  = $file_info->buffer(file_get_contents($file));  // e.g. gives "image/jpeg"

        return $mime_type;
    }
}
