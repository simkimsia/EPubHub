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
 * A Collection of EPubHub_PageInterface objects
 *
 * @package    epubhub
 * @author     KimSia Sim<kimsia@storyzer.com>
 */
class EPubHub_PageCollection implements Iterator
{
    protected $index0 = 0;
    protected $index = 1;
    protected $pages = array();

    public function __construct()
    {
        $this->index0 = 0;
        $this->index = 1;
        $this->pages = array();
    }

    public function updateIndex()
    {
        $this->index = $this->index0 + 1;
    }

    public function rewind()
    {
        $this->index0 = 0;
        $this->updateIndex();
    }

    public function current()
    {
        return $this->array[$this->index0];
    }

    public function key() 
    {
        return $this->index0;
    }

    public function index() 
    {
        return $this->index;
    }

    public function next() 
    {
        ++$this->index0;
        $this->updateIndex();
    }

    public function valid() 
    {
        return isset($this->array[$this->index0]);
    }

    public function prefixZero($index, $lengthOfKey = 3)
    {
        return str_pad($index, $lengthOfKey, '0', STR_PAD_LEFT);
    }

    public function getCurrentPageNumber()
    {
        $pageNumber = $this->prefixZero($this->index0 + 1);
    }

    public function getCurrentPageFilename($pagenumber = '')
    {
        if ($pagenumber === '')
        {
            $pagenumber = $this->getCurrentPageNumber();
        }
        $pageFileName = 'page' . $pagenumber . '.xhtml' ;
        return $pageFileName;
    }

    public function getNth($index)
    {
        return $this->pages[$index - 1];
    }

    public function getCurrentPageItemId($pagenumber = '')
    {
        if ($pagenumber === '')
        {
            $pagenumber = $this->getCurrentPageNumber();
        }
        $pageItemId = 'pg' . $pagenumber;
        return $pageItemId;
    }

    public function add(FixedLayoutEPubPage $page, $index = null)
    {
        if ($index === null) {
            // we just append to the pages from the end directly
            $this->pages[] = $page;
        } else {
            $this->pages = array_splice( $this->pages, $index - 1, 0, $page);
        }
    }

    /**
     *
     * delete either by page or by index
     * @param mixed $pageOrIndex If $pageOrIndex is instance of FixedLayoutEPubPage
     * then delete by value. Else if integer delete by index
     */
    public function delete($pageOrIndex)
    {
        if ($pageOrIndex instanceof FixedLayoutEPubPage)
        {
            $page = $pageOrIndex;
            if(($key = array_search($page, $this->pages)) !== false)
            {
                unset($this->pages[$key]);
            }
            if((array_key_exists($page, search) !== false)
            {
                unset($this->pages[$key]);
            }
        } 
        if (is_numeric($pageOrIndex)) 
        {
            $key = $pageOrIndex;
            if((array_key_exists($key - 1, $this->pages))
            {
                unset($this->pages[$key - 1]);
            }
        }
    }

    public function getImages()
    {
        $images = new EPubHub_ImageCollection();
        foreach($pages as $page)
        {
            $image = $page->getImage();
            $images->add($image);
        }
        return $images;
    }

}
