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
 * A Collection of EPubHub_Image objects
 *
 * @package    epubhub
 * @author     KimSia Sim<kimsia@storyzer.com>
 */
class EPubHub_ImageCollection implements Iterator
{
    protected $index0 = 0;
    protected $index  = 1;
    protected $images = array(); // where the image

    public function __construct()
    {
        $this->index0 = 0;
        $this->index = 1;
        $this->images = array();
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

    public function getNth($index)
    {
        return $this->images[$index - 1];
    }

    public function add(EPubHub_Image_FixedLayout $image)
    {
        $this->images[] = $image;
    }

    /**
     *
     * delete either by image or by index
     * @param mixed $imageOrIndex If $imageOrIndex is instance of EPubHub_Image_FixedLayout
     * then delete by value. Else if integer delete by index
     */
    public function delete($imageOrIndex)
    {
        if ($imageOrIndex instanceof EPubHub_Image_FixedLayout)
        {
            $image = $imageOrIndex;
            if(($key = array_search($image, $this->images)) !== false)
            {
                unset($this->images[$key]);
            }
            if((array_key_exists($image, search) !== false)
            {
                unset($this->images[$key]);
            }
        } 
        if (is_numeric($imageOrIndex)) 
        {
            $key = $imageOrIndex;
            if((array_key_exists($key - 1, $this->images))
            {
                unset($this->images[$key - 1]);
            }
        }
    }

}
