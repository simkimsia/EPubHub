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
 * A FixedLayout EPub Book class.
 *
 * @package    epubhub
 * @author     KimSia Sim<kimsia@storyzer.com>
 */
class EPubHub_Book_FixedLayout implements EPubHub_BookInterface
{
    // contains EPubHub_Page_FixedLayout objects 
    protected $pages = null;

    protected $images = null;

    protected $metadata = array(); // hold string values only

    public $frontCover = null;
    public $backCover = null;

    protected $width = 0; // ideal width as suggested by  Liz Castro is 1200
    protected $height = 0; // ideal height as suggested by Liz Castro is 1700

    public function __construct($metadata = array(), $size = array()) {
        $defaultSize = array(
            'height' => 1700,
            'width' => 1200
        );
        $size = array_merge($defaultSize, $size);
        $this->setSize()
        $this->pages = new EPubHub_PageCollection();
        $this->images = new EPubHub_ImageCollection();
        $this->_setDefaults();
        $this->updateMetadata($metadata);
    }

    protected function _setDefaults() {
        $this->metadata = array(
            'title'     => '',
            'creator'   => '',
            'publisher' => '',
            'date'      => '',
            'language'  => 'en',
            'book_id'   => '',
        );
    }

/**
 *
 * append the new values for metadata into the current metadata
 */
    public function updateMetadata($metadata) {
        $this->metadata = array_merge($this->metadata, $metadata);
    }

/**
 *
 */
    public function getMetadata() {
        return $this->metadata;
    }

    /*
     *
     */
    public function height()
    {
        return $this->getHeight();
    }

    /*
     *
     */
    public function width()
    {
        return $this->getWidth();
    }

/**
 *
 * retrieve the pages
 */
    public function getPages()
    {
        return $this->pages;
    }

/**
 *
 * retrieve the pages
 */
    public function getImages()
    {
        return $this->images;
    }


    /*
     *
     * get height
     */
    public function getHeight()
    {
        return $this->height;
    }

    /*
     *
     * get width
     */
    public function getWidth()
    {
        return $this->width;
    }

    /*
     *
     * set height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /*
     *
     * set width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function setSize($size)
    {
        if (isset($size['width']))
        {
            $this->setWidth($size['width']);
        }
        if (isset($size['height']))
        {
            $this->setHeight($size['height']);
        }
    }

/**
 *
 * add page
 */
    public function addPage(EPubHub_Page_FixedLayout $page, $index = null)
    {
        // validate that the newly added page fits the stated width and height by within +-5 px range
        $result = $this->pages->add($page, $index);
        $this->images = $this->pages->getImages();
        return $result;
    }

/**
 *
 * delete page
 */
    public function deletePage(EPubHub_Page_FixedLayout $page)
    {
        $result = $this->pages->delete($page);
        $this->images = $this->pages->getImages();
        return $result;
    }

/**
 *
 * delete nth page
 */
    public function deleteNthPage(int $index)
    {
        $result = $this->pages->delete($index - 1);
        $this->images = $this->pages->getImages();
        return $result;
    }

/**
 *
 * get nth page
 */
    public function getNthPage(int $index)
    {
        return $this->pages->getNth($index);
    }

}
