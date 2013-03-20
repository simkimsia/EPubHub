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
    protected $image = null;

    public function __construct($image)
    {
        $this->image = $image;
    }

    /**
     * get image object
     *
     * @return EPubHub_Image_FixedLayout object
     */
    public function getImage()
    {
        return $this->image;
    }

    public function setImage(EPubHub_Image_FixedLayout $image)
    {
        $this->image = $image;
    }
}
