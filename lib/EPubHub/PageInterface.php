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
 * Interface all page must implement.
 *
 * @package    epubhub
 * @author     KimSia Sim<kimsia@storyzer.com>
 */
interface EPubHub_PageInterface
{
    /**
     *
     * Return the mimetype of the image
     *
     *
     * @return string Common mimetypes include image/jpeg.
     *
     * @throws EPubHub_Error_Page When Page not defined.
     */
    public function getMimeType();

}
