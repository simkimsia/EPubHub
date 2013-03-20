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
    protected $title = '';
    protected $id    = '';

    /**
     * Constructor for EPubHub_Page_FixedLayout
     *
     * @return EPubHub_Page_FixedLayout object
     */
    public function __construct($image, $options = array())
    {
        $this->title = isset($options['title']) ? $options['title'] : '';
        $this->id    = isset($options['id']) ? $options['id'] : '';
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

    /**
     *
     * Alias for getImage() 
     * @return EPubHub_Image_FixedLayout object
     */
    public function image()
    {
        return $this->getImage();
    }

    /**
     * Set image object
     *
     * @param EPubHub_Image_FixedLayout $image
     */
    public function setImage(EPubHub_Image_FixedLayout $image)
    {
        $this->image = $image;
    }

    /**
     * Get title 
     *
     * @return string title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     *
     * Alias for getTitle() 
     * @return string
     */
    public function title()
    {
        return $this->getTitle();
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get id 
     *
     * @return string id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * Alias for getId() 
     * @return string
     */
    public function id()
    {
        return $this->getId();
    }

    /**
     * Set id
     *
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}
