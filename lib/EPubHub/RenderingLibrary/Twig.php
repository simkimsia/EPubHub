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
    protected $name                     = '';
    protected $book                     = null;
    protected $twig                     = null;
    // define these three before working
    protected $twigLibraryPath          = '';
    protected $twigTemplatesPath        = '';
    protected $twigBookTypeTemplatePath = '';

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

    /**
     *
     * Update the path from which we will extract the templates from
     *
     * @return void
     *
     * @throws EPubHub_Error_EPub When setting the templates path
     */
    public function updateTemplatePath()
    {
        if ($this->book instanceof EPubHub_Book_FixedLayout)
        {
            $this->twigBookTypeTemplatePath = $this->twigTemplatesPath . '/' . 'FixedLayout';
        }
    }

    /**
     *
     * Render the images
     *
     * @return void
     *
     * @throws EPubHub_Error_EPub When rendering images fails
     */
    public function renderImages($sourceFilesPath = '')
    {
        $images = $this->book->getImages();

        $imagesFolderPath = $sourceFilesPath . '/OEBPS/Images';
        if (!file_exists($imagesFolderPath))
        {
            mkdir($imagesFolderPath);
        }
        $results = array();
        foreach($images as $image)
        {
            $filename = $image->getName();

            $result = copy ( $image->getPath(), $imagesFolderPath . '/' . $filename);

            $results[$filename] = $result;
        }

        return $results;
    }

    /**
     *
     * Render the styles.css
     *
     * @return void
     *
     * @throws EPubHub_Error_EPub When rendering images fails
     */
    public function renderStyles($sourceFilesPath = '')
    {
        $stylesFilesPath = $sourceFilesPath . '/OEBPS/Styles';
        if (!file_exists($stylesFilesPath))
        {
            mkdir($stylesFilesPath);
        }
        // select file to render
        $fileToRender = 'styles.css';
        // render using Twig
        $renderedCss = $this->twig->render('OEBPS/Styles/' . $fileToRender . '.html', array('epub' => $this->book));
        // write the rendered content into a file
        $result      = file_put_contents($stylesFilesPath . '/' . $fileToRender, $renderedCss);

        return $result;
    }

    /**
     *
     * Render the pages
     *
     * @return void
     *
     * @throws EPubHub_Error_EPub When rendering images fails
     */
    public function renderPages($sourceFilesPath = '')
    {
        $pages = $this->book->getPages();

        $pagesFolderPath = $sourceFilesPath . '/OEBPS/Pages';
        if (!file_exists($pagesFolderPath))
        {
            mkdir($pagesFolderPath);
        }
        $results = array();
        foreach($pages as $page)
        {
            $pageTitle = $page->getTitle();
            $pageId    = $page->getId();
            // select file to render
            $fileToRender = 'page.xhtm.html';
            // select source file to render
            $sourceFile   = $pageId . '.xhtm';
            // render using Twig
            $renderedPage = $this->twig->render('OEBPS/Pages/' . $fileToRender, array('epub' => $this->book));
            // write the rendered content into a file
            $result      = file_put_contents($pagesFolderPath . '/' . $sourceFile, $renderedPage);
            $results[$pageId] = $result;
        }

        return $results;
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
        $oebpsFilesPath = $sourceFilesPath . '/OEBPS';
        if (!file_exists($oebpsFilesPath))
        {
            mkdir($oebpsFilesPath);
        }
        // select file to render
        $fileToRender = 'content.opf';
        // render using Twig
        $renderedOpf = $this->twig->render('OEBPS/' . $fileToRender . '.html', array('epub' => $this->book));
        // write the rendered content into a file
        $result      = file_put_contents($oebpsFilesPath . '/' . $fileToRender, $renderedOpf);

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
        $this->makeSourceFilesDir($sourceFilesPath);
        $this->renderOpf($sourceFilesPath);
        $this->renderImages($sourceFilesPath);
        $this->renderStyles($sourceFilesPath);
        $this->renderPages($sourceFilesPath);
    }

    /**
     *
     * make directories in the source files path
     *
     * @return void
     *
     * @throws EPubHub_Error_EPub When making the directories fail
     */
    public function makeSourceFilesDir($sourceFilesPath = '')
    {
        $oebpsFilesPath = $sourceFilesPath . '/OEBPS';
        if (!file_exists($oebpsFilesPath))
        {
            mkdir($oebpsFilesPath);
        }

        $stylesFilesPath = $oebpsFilesPath . '/Styles';
        if (!file_exists($stylesFilesPath))
        {
            mkdir($stylesFilesPath);
        }

        $imagesFilesPath = $oebpsFilesPath . '/Images';
        if (!file_exists($imagesFilesPath))
        {
            mkdir($imagesFilesPath);
        }

        $pagesFilesPath = $oebpsFilesPath . '/Pages';
        if (!file_exists($pagesFilesPath))
        {
            mkdir($pagesFilesPath);
        }
    }
}