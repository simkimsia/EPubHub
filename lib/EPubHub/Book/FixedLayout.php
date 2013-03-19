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
    // contains FixedLayoutEPubPage objects 
    protected $pages = new PageCollection();

    protected $metadata = array(); // hold string values only

    public $frontCover = null;
    public $backCover = null;

    public function __construct($metadata = array()) {
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
 * add page
 */
    public function addPage(FixedLayoutEPubPage $page, $index = null)
    {
        return $this->pages->add($page, $index);
    }

/**
 *
 * delete page
 */
    public function deletePage(FixedLayoutEPubPage $page)
    {
        return $this->pages->delete($page);
    }

/**
 *
 * delete nth page
 */
    public function deleteNthPage(int $index)
    {
        return $this->pages->delete($index - 1);
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
