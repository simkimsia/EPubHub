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
 * Stores the EPubHub configuration.
 *
 * @package    epubhub
 * @author     KimSia Sim<kimsia@storyzer.com>
 */
class EPubHub_Environment
{
    const VERSION = '0.1.1';

    protected $debug;
    protected $sourceFilesPath;
    protected $buildFilesPath;
    protected $tests;
    protected $renderingLibrary = null;
    protected $zippingLibrary = null;
    protected $book = null;

    /**
     * Constructor.
     *
     * Available options:
     *
     *  * debug: When set to true, will turn on debug mode(default to false).
     *
     *  * src_files_path: An absolute path where to store the EPub source files. This directory 
     *  will hold the book source files in separate folders. 1 folder for each book.
     *
     *  * build_files_path: An absolute path where to store the final .epub files.
     *
     * 
     * @param array                $options An array of options
     */
    public function __construct($renderingLibrary = null, $zippingLibrary, $options = array())
    {
        $rootDir = realpath(dirname(dirname(dirname(__FILE__))));
        $defaultSourcePath = $rootDir . '/' . 'source';
        $defaultBuildPath = $rootDir. '/' . 'build';

        $options = array_merge(array(
            'debug'             => false,
            'src_files_path'    => $defaultSourcePath,
            'build_files_path'  => $defaultBuildPath,
        ), $options);

        $this->debug          = (bool) $options['debug'];
        $this->sourceFilesPath   = $options['src_files_path'];
        $this->buildFilesPath = $options['build_files_path'];

        $this->renderingLibrary = $renderingLibrary;
        $this->zippingLibrary   = $zippingLibrary;
    }

    /**
     * Gets the library responsible for rendering the source files
     *
     * @return RenderingLibraryInterface 
     */
    public function getRenderingLibrary()
    {
        return $this->renderingLibrary;
    }

    /**
     * Sets the library for rendering
     *
     * @param mixed $class/$options Either supply the library object or
     * supply the options as an array.
     *
     * #options
     * name: Use RenderingLibraryInterface::TWIG if you want to use Twig library
     * path: Absolute path to the rendering library
     *
     * @throws EPubHub_Error_EPub When $class is not valid
     */
    public function setRenderingLibrary($class)
    {
        if ($class instanceof RenderingLibraryInterface)
        {
            $this->renderingLibrary = $class;
        }
        if (is_array($class))
        {
            if (isset($class['name']) && $class['path'])
            {
                switch($class['name'])
                {
                    case RenderingLibraryInterface::TWIG :
                        $this->renderingLibrary = new EPubHub_RenderingLibrary_Twig($class['path']);
                }
            }
        }
    }

    /**
     *
     * Determine the absolute path to send the source files to.
     *
     * @param string $path
     * @return void
     *
     * @throws EPubHub_Error_EPub When path does not exist or is not writable.
     */
    public function setSourceFilesPath($path)
    {
        // validate if path exists
        // validate if path not writable
        $this->sourceFilesPath = $path;
    }

    /**
     *
     * Return the absolute path to send the source files to.
     *
     * @return string $path
     *
     * @throws EPubHub_Error_EPub When path is not set.
     */
    public function getSourceFilesPath()
    {
        // validate not empty string
        return $this->sourceFilesPath;
    }

    /**
     *
     * Determine the absolute path to send the .epub files to.
     *
     * @param string $path
     * @return void
     *
     * @throws EPubHub_Error_EPub When path does not exist or is not writable.
     */
    public function setBuildFilesPath($path)
    {
        // validate if path exists
        // validate if path not writable
        $this->buildFilesPath = $path;
    }

    /**
     *
     * Return the absolute path to send the .epub files to.
     *
     * @return string $path
     *
     * @throws EPubHub_Error_EPub When path is not set.
     */
    public function getBuildFilesPath()
    {
        // validate not empty string
        return $this->buildFilesPath;
    }

    /**
     * Enables debugging mode.
     */
    public function enableDebug()
    {
        $this->debug = true;
    }

    /**
     * Disables debugging mode.
     */
    public function disableDebug()
    {
        $this->debug = false;
    }

    /**
     * Checks if debug mode is enabled.
     *
     * @return Boolean true if debug mode is enabled, false otherwise
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * Enables the auto_reload option.
     */
    public function enableAutoReload()
    {
        $this->autoReload = true;
    }

    /**
     * Disables the auto_reload option.
     */
    public function disableAutoReload()
    {
        $this->autoReload = false;
    }

    /**
     * Checks if the auto_reload option is enabled.
     *
     * @return Boolean true if auto_reload is enabled, false otherwise
     */
    public function isAutoReload()
    {
        return $this->autoReload;
    }

    public function setBook(EPubHub_BookInterface $book = null)
    {
        $this->book = $book;

        // set the rendering library to use the book
        if ($this->renderingLibrary !== null)
        {
            $this->renderingLibrary->setBook($book);
        }

        // get the path to the source files
        $sourceFilesPath = $this->getBookSourceFilesPath();

        // ensure the directory is there before rendering
        if (!file_exists($sourceFilesPath))
        {
            mkdir($sourceFilesPath);
        }

        // set the zipping library to use the book
        if ($this->zippingLibrary !== null)
        {
            $this->zippingLibrary->setBook($book);
        }

        // get the path to the build file
        $buildFilesPath = $this->getBookBuildFilesPath();

        // ensure the directory is there before packing the source files
        if (!file_exists($buildFilesPath))
        {
            mkdir($buildFilesPath);
        }
    }

    public function getBookSourceFilesPath(EPubHub_BookInterface $book = null)
    {
        if ($book instanceof EPubHub_BookInterface)
        {
            $this->book = $book;
        }
        $metadata        = $this->book->getMetadata();
        $bookId          = $metadata['book_id'];
        return $this->sourceFilesPath . '/' . $bookId;
    }

    public function getBookBuildFilesPath(EPubHub_BookInterface $book = null)
    {
        if ($book instanceof EPubHub_BookInterface)
        {
            $this->book = $book;
        }
        $metadata        = $this->book->getMetadata();
        $bookId          = $metadata['book_id'];
        return $this->buildFilesPath . '/' . $bookId;
    }

    public function renderBook(EPubHub_BookInterface $book = null, $sourceFilesPath = '')
    {
        if ($book !== null)
        {
            $this->setBook($book);
        }

        if ($sourceFilesPath === '')
        {
            $sourceFilesPath  = $this->getBookSourceFilesPath();
        }
        $this->renderingLibrary->renderBook($sourceFilesPath);
    }

    public function zipRendered($sourceFilesPath = '', $buildFilesPath = '')
    {
        if ($sourceFilesPath === '' && file_exists($this->sourceFilesPath) && $this->book != null)
        {
            $sourceFilesPath  = $this->getBookSourceFilesPath();
        }

        if ($buildFilesPath === '' && file_exists($this->buildFilesPath) && $this->book != null)
        {
            $buildFilesPath  = $this->getBookBuildFilesPath();
        }
        $this->zippingLibrary->zipRendered($sourceFilesPath, $buildFilesPath);
    }

    public function makeEPub(EPubHub_BookInterface $book = null, $buildFilesPath = '')
    {
        $this->renderBook($book);
        $this->zipRendered('', $buildFilesPath);
    }

}
