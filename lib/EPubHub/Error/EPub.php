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
 * Exception thrown when an error occurs during loading data into Book/Page or during 
 * generation of the source files/.epub output files
 *
 *
 * @package    epubhub
 * @author     KimSia Sim<kimsia@storyzer.com>
 */
class EPubHub_Error_EPub extends EPubHub_Error
{
    public function __construct($message, $lineno = -1, $filename = null, Exception $previous = null)
    {
        parent::__construct($message, false, false, $previous);
    }
}
