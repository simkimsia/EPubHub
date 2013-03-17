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
    protected $srcFilesPath;
    protected $buildFilesPath;
    protected $tests;
    protected $renderingLibrary = null;
    protected $zippingLibrary = null;
    protected $book;

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
        $defaultSrcPath = $rootDir . '/' . 'source';
        $defaultBuildPath = $rootDir. '/' . 'build';

        $options = array_merge(array(
            'debug'             => false,
            'src_files_path'    => $defaultSrcPath,
            'build_files_path'  => $defaultBuildPath,
        ), $options);

        $this->debug          = (bool) $options['debug'];
        $this->srcFilesPath   = $options['src_files_path'];
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
        if ($this->renderingLibrary !== null)
        {
            $this->renderingLibrary->setBook($book);
        }
    }

    public function renderBook(EPubHub_BookInterface $book = null, $sourceFilesPath = '')
    {
        if ($book !== null)
        {
            $this->setBook($book);
        }

        $this->renderingLibrary->renderBook($sourceFilesPath);

    }

}
