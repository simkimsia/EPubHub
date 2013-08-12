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
    protected $pages    = null;
    protected $images   = null;
    protected $metadata = array(); // hold string values only

    public $frontCover = null;
    public $backCover  = null;

    protected $width  = 0; // ideal width as suggested by  Liz Castro is 1200
    protected $height = 0; // ideal height as suggested by Liz Castro is 1700

    public function __construct($metadata = array(), $size = array()) {
        $defaultSize = array(
            'height' => 1700,
            'width' => 1200
        );
        $size = array_merge($defaultSize, $size);
        $this->setSize($size);
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
    public function getMetadata($key = '') {
        if (isset($this->metadata[$key]))
        {
            return $this->metadata[$key];
        }
        return $this->metadata;
    }

    /**
     *
     */
    public function height()
    {
        return $this->getHeight();
    }

    /**
     *
     */
    public function width()
    {
        return $this->getWidth();
    }

    /*
     *
     */
    public function title()
    {
        return $this->getMetadata('title');
    }

    /**
     *
     * retrieve the pages
     *
     * @return EPubHub_PageCollection Collection of EPubHub_PageInterface
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     *
     * Alias for getPages
     * retrieve the pages
     *
     * @return EPubHub_PageCollection Collection of EPubHub_PageInterface
     */
    public function pages()
    {
        // for some reason the PageCollection is not working in the foreach loop
        // inside Twig
        $pageCollection =  $this->getPages();
        return $pageCollection->getPages();
    }

/**
 *
 * retrieve the images
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

    /**
     *
     * set width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     *
     * Set size 
     * 
     * @param array $size Size options
     * 
     * #options
     * - height: integer representing height in pixels
     * - width: integer representing width in pixels
     */
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
    public function addPage(EPubHub_Page_FixedLayout &$page, $index = null)
    {
        // validate that the newly added page fits the stated width and height by within +-5 px range
        if ($page->getTitle() === '')
        {
            $title = $this->makeDefaultPageTitle();
            $page->setTitle($title);
        }
        if ($page->getId() === '')
        {
            $id = $this->makeDefaultPageId();
            $page->setId($id);
        }
        $page->setWidth($this->width);
        $page->setHeight($this->height);
        $result       = $this->pages->add($page, $index);
        $this->updateImages();
        return $result;
    }

    /**
     *
     * Make a default page title for the next incoming page
     *
     * @return string 
     *
     */
    public function makeDefaultPageTitle()
    {
        // get page id for incoming page
        $id        = $this->makeDefaultPageId();
        // prefix the page id with the book title and ' '
        $pageTitle = $this->title() . ' ' .  $id;
        return $pageTitle;
    }

    /**
     *
     * Make a default page id for the next incoming page
     *
     * @return string 
     *
     */
    public function makeDefaultPageId()
    {
        // get current page count
        $count  = $this->pages->length();
        // make the page title as page00x
        $pageId = 'page' . $this->pages->prefixZero($count + 1);
        return $pageId;
    }

    /**
     *
     * delete page
     */
    public function deletePage(EPubHub_Page_FixedLayout $page)
    {
        $result = $this->pages->delete($page);
        $this->updateImages();
        return $result;
    }

    /**
     *
     * delete nth page
     */
    public function deleteNthPage(int $index)
    {
        $result = $this->pages->delete($index - 1);
        $this->updateImages();
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

    /**
     *
     * Get the front cover as EPubHub_ImageInterface object
     * @return EPubHub_ImageInterface the front cover 
     */
    public function getFrontCover()
    {
        return $this->frontCover;
    }

    /**
     *
     * Alias for getFrontCover
     * @return EPubHub_ImageInterface the front cover 
     */
    public function front_cover()
    {
        return $this->getFrontCover();
    }

    /**
     *
     * Set the front cover as EPubHub_ImageInterface object
     * @param EPubHub_ImageInterface $image
     */
    public function setFrontCover($image)
    {
        $this->frontCover = $image;
        $this->updateImages();
    }

    /**
     *
     * Get the back cover as EPubHub_ImageInterface object
     * @return EPubHub_ImageInterface the back cover 
     */
    public function getBackCover()
    {
        return $this->backCover;
    }

    /**
     *
     * Alias for getBackCover
     * @return EPubHub_ImageInterface the back cover 
     */
    public function back_cover()
    {
        return $this->getBackCover();
    }

    /**
     *
     * Set the back cover as EPubHub_ImageInterface object
     * @param EPubHub_ImageInterface $image
     */
    public function setBackCover($image)
    {
        $this->backCover = $image;
        $this->updateImages();
    }

    /**
     *
     * Update the Images this book is supposed to have
     * @return void
     */
    public function updateImages()
    {
        $imagesInPage = $this->pages->getImages();
        $this->images = $imagesInPage;
        if ($this->frontCover instanceof EPubHub_ImageInterface)
        {
            $this->images->addByKey('front_cover', $this->frontCover);
        }

        if ($this->backCover instanceof EPubHub_ImageInterface)
        {
            $this->images->addByKey('back_cover', $this->backCover);
        }
    }
}
