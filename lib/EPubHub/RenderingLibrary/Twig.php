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
 * Implementation of the RenderingLibraryInterface using Twig
 *
 * @package    epubhub
 * @author     KimSia Sim<kimsia@storyzer.com>
 */
class EPubHub_RenderingLibrary_Twig implements EPubHub_RenderingLibraryInterface
{
    protected $name         = '';
    protected $book         = null;
    protected $twig         = null;
    // define these three before working
    protected $twigLibraryPath = '';
    protected $twigTemplatesPath = '';
    protected $twigBookTypeTemplatePath = '';
    protected $srcFolderPath = ''; // default location of folder for rendering 

    /**
     *
     * Constructor for the rendering library
     *
     * @param string $path Absolute path to the Twig library. The directory holding the Autoloader.php
     * @return new EPubHub_RenderingLibrary_Twig
     *
     */
    public function __construct($twigLibraryPath)
    {
        // set the default name of the Rendering Library
        $this->name = self::TWIG;
        // set the default type of EPub Book we will be rendering
        $this->book = new EPubHub_Book_FixedLayout();

        $rootDir = realpath(dirname(dirname(dirname(dirname(__FILE__)))));
        // define the exact template path
        $this->twigTemplatesPath = $rootDir . '/templates/Twig';
        $this->updateTemplatePath();

        // define a default source folder path
        $this->srcFolderPath = $rootDir . '/source';

        // define the path to the Twig library
        $this->twigLibraryPath = $twigLibraryPath;

        // turn on the twig library
        $this->_turnOnTwig();
    }

    protected function _turnOnTwig()
    {
        
        require_once $this->twigLibraryPath . '/Autoloader.php';
        Twig_Autoloader::register();

        $loaderInBookType = new Twig_Loader_Filesystem($this->twigBookTypeTemplatePath);
        
        $cache = APP.DS.'cache';
        $debug = true;

        $this->twig = new Twig_Environment($loaderInBookType, array(
            'cache' => $cache,
            'debug' => $debug,
        ));
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
    public function setSrcFolderPath($path)
    {
        // validate if path exists
        // validate if path not writable
        $this->srcFolderPath = $path;
    }

    /**
     *
     * Return the absolute path to send the source files to.
     *
     * @return string $path
     *
     * @throws EPubHub_Error_EPub When path is not set.
     */
    public function getSrcFolderPath()
    {
        // validate not empty string
        return $this->srcFolderPath;
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
        $this->updateTemplatePath();
    }

    public function updateTemplatePath()
    {
        if ($this->book instanceof EPubHub_Book_FixedLayout)
        {
            $this->twigBookTypeTemplatePath = $this->twigTemplatesPath . '/' . 'FixedLayout';
        }
    }

    /**
     *
     * Render the content.opf
     *
     * @return void
     *
     * @throws EPubHub_Error_EPub When rendering content.opf fails
     */
    public function renderOpf($sourceFilesPath = '')
    {
        if ($sourceFilesPath === '')
        {
            $metadata        =  $this->book->getMetadata();
            $bookId          = $metadata['book_id'];
            $sourceFilesPath = $this->srcFolderPath . '/' . $bookId;
        }

        // ensure the directory is there before rendering
        if (!file_exists($sourceFilesPath))
        {
            mkdir($sourceFilesPath);
        }

        // select file to render
        $fileToRender = 'content.opf';
        // render using Twig
        $renderedOpf = $this->twig->render('OEBPS/' . $fileToRender . '.html', array('epub' => $this->book));
        // write the rendered content into a file
        $result      = file_put_contents($sourceFilesPath . '/' . $fileToRender, $renderedOpf);

        return $result;
    }

    /**
     *
     * Render the entire book into the source path
     *
     * @return void
     *
     * @throws EPubHub_Error_EPub When rendering content.opf fails
     */
    public function renderBook($sourceFilesPath = '')
    {
        $this->renderOpf($sourceFilesPath);
    }
}