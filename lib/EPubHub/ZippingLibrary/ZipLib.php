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
 * Implementation of the ZippingLibraryInterface using ZipLib
 *
 * @package    epubhub
 * @author     KimSia Sim<kimsia@storyzer.com>
 */
class EPubHub_ZippingLibrary_ZipLib implements EPubHub_ZippingLibraryInterface
{
    protected $name         = '';
    protected $book         = null;
    protected $zipLib         = null;
    // define these three before working
    protected $zipLibLibraryPath = '';

    /**
     *
     * Constructor for the zipping library
     *
     * @param string $path Absolute path to the ZipLib library. The directory holding the Autoloader.php
     * @return new EPubHub_ZippingLibrary_ZipLib
     *
     */
    public function __construct($zipLibLibraryPath)
    {
        // set the default name of the Zipping Library
        $this->name = self::ZIPLIB;
        // set the default type of EPub Book we will be zipping
        $this->book = new EPubHub_Book_FixedLayout();

        // define the path to the ZipLib library
        $this->zipLibLibraryPath = $zipLibLibraryPath;

        // turn on the zipLib library
        $this->_turnOnZipLib();
    }

    protected function _turnOnZipLib()
    {
        // ziplib is turned on by the require_once because it is a static class with static
        require_once $this->zipLibLibraryPath . '/ZipLib.php';
    }

    /**
     *
     * Return library name
     *
     * @return string $name
     *
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * Return the book object.
     *
     * @param string $path
     * @return EPubHub_BookInterface $book
     *
     * @throws EPubHub_Error_EPub When book does not exist.
     */
    public function getBook()
    {
        // validate not null
        return $this->book;
    }

    /**
     *
     * Determine the book object.
     *
     * @param EPubHub_BookInterface $book
     * @return void
     *
     * @throws EPubHub_Error_EPub When $book fails validation
     */
    public function setBook(EPubHub_BookInterface $book)
    {
        // validate book has the standard stuff like content.opf etc
        $this->book = $book;
    }

    /**
     *
     * Render the content.opf
     *
     * @return void
     *
     * @throws EPubHub_Error_EPub When zipping content.opf fails
     */
    public function zipRendered($sourceFilesPath = '', $buildFilesPath = '')
    {
        // turn on the zipLib library
        $this->_turnOnZipLib();

        $metadata = $this->book->getMetadata();
        $title = $metadata['title'];
        $filename = $title . '.epub';

        $options = array(
            'destination' => $buildFilesPath . '/' . $filename,
            'include_dir' => false,
            'ignore_files' => array('empty')
        );

        $result = ZipLib::zipFolder($sourceFilesPath, $options);

        return $result;
    }

}