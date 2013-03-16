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
 * Interface all books must implement.
 *
 * @package    epubhub
 * @author     KimSia Sim<kimsia@storyzer.com>
 */
interface EPubHub_BookInterface
{
    /**
     *
     * Append new values for metadata into the current metadata.
     *
     * @param array $metadata An array of metadata to add to the Book.
     *
     * @return void
     *
     * @throws EPubHub_Error_Book When format of metadata is wrong.
     */
    public function updateMetadata($metadata);

    /**
     * Gets the current metadata of the Book.
     *
     *
     * @return array The metadata array
     *
     * @throws EPubHub_Error_Book When metadata not found.
     */
    public function getMetadata();

}
