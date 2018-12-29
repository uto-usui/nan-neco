<?php
/**
 * Display data processing class
 *
 * @copyright  Copyright (c) Yoshika
 * @author     Yoshika (@rnsk)
 * @link       http://rnsk.net/
 * @license    MIT License
 */
class PluginHelper
{

/**
 * URL to base directory.
 *
 * @var string
 */
    protected $baseUrl = null;

/**
 * Html tags
 *
 * @var array
 */
    protected $tags = array(
        'link' => '<a href="%s"%s>%s</a>',
        'image' => '<img src="%s"%s/>',
        'css' => '<link rel="%s" type="text/css" href="%s"%s/>',
        'script' => '<script type="text/javascript" src="%s"%s></script>',
    );

/**
 * An array of md5sums and their contents.
 * Used when inserting links into text.
 *
 * @var array
 */
    protected $placeholders = array();

/**
 * Construct
 *
 * @return void
 */
    public function __construct($baseUrl = null)
    {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }

        $this->baseUrl = plugin_dir_url(dirname(__FILE__));
        if (!empty($baseUrl)) {
            $this->baseUrl = $baseUrl;
        }
    }

/**
 * Creates a formatted IMG element
 *
 * @param string $file Name to img file
 * @param array $options HTML attributes
 * @return string completed img tag
 */
    public function image($file, $options = array())
    {
        $imageUrl = $this->getImage($file);

        $attributes = array();
        foreach ($options as $key => $value) {
            if (!empty($value)) {
                $attributes[] = $key . '="' . $value . '"';
            }
        }

        $attributes = implode(' ', $attributes);
        if (!empty($attributes)) {
            $attributes = ' ' . $attributes;
        }

        $image = sprintf($this->tags['image'], $imageUrl, $attributes);
        return $image;
    }

/**
 * Get the image URL
 *
 * @param string $file Name to img file
 * @return string image URL
 */
    public function getImage($file = null)
    {
        if (empty($file)) return null;

        $dirUrl = $this->baseUrl . 'static' . DS . 'img';
        $imageUrl = $dirUrl . DS . $file;
        return $imageUrl;
    }

/**
 * Creates an HTML link.
 *
 * @param string $title The content to be wrapped by <a> tags.
 * @param string $url URL (starts with http://)
 * @param array $options HTML attributes
 * @return string completed a tag
 */
    public function link($title, $url = null, $options = array())
    {
        $escapeTitle = true;
        if (isset($options['escape'])) {
            $escapeTitle = $options['escape'];
        }

        $attributes = array();
        foreach ($options as $key => $value) {
            if (!empty($value)) {
                $attributes[] = $key . '="' . $value . '"';
            }
        }

        $attributes = implode(' ', $attributes);
        if (!empty($attributes)) {
            $attributes = ' ' . $attributes;
        }

        if ($escapeTitle === true) {
            $title = htmlspecialchars($title);
        }

        return sprintf($this->tags['link'], $url, $attributes, $title);
    }

/**
 * Adds links (<a href=....) to a given text.
 *
 * @param string $text Text
 * @param array $options HTML attributes
 * @return string The text with links
 */
    public function autoLinkUrls($text, $options = array())
    {
        $this->placeholders = array();
        $escapeText = true;
        if (isset($options['escape'])) {
            $escapeText = $options['escape'];
        }

        $pattern = '#(?<!href="|src="|">)((?:https?|ftp|nntp)://[\p{L}0-9.\-_:]+(?:[/?][^\s<]*)?)#ui';
        $text = preg_replace_callback(
            $pattern,
            array(&$this, '_insertPlaceHolder'),
            $text
        );
        $pattern = '#(?<!href="|">)(?<!\b[[:punct:]])(?<!http://|https://|ftp://|nntp://)www.[^\n\%\ <]+[^<\n\%\,\.\ <](?<!\))#i';
        $text = preg_replace_callback(
            $pattern,
            array(&$this, '_insertPlaceHolder'),
            $text
        );

        if ($escapeText === true) {
            $text = htmlspecialchars($text);
        }
        return $this->_linkUrls($text, $options);
    }

/**
 * Saves the placeholder for a string, for later use. This gets around double
 * escaping content in URL's.
 *
 * @param array $matches An array of regexp matches.
 * @return string Replaced values.
 */
    protected function _insertPlaceHolder($matches)
    {
        $key = md5($matches[0]);
        $this->placeholders[$key] = $matches[0];
        return $key;
    }

/**
 * Replace placeholders with links.
 *
 * @param string $text The text to operate on.
 * @param array $htmlOptions The options for the generated links.
 * @return string The text with links inserted.
 */
    protected function _linkUrls($text, $htmlOptions)
    {
        $replace = array();
        foreach ($this->placeholders as $hash => $url) {
            $link = $url;
            if (!preg_match('#^[a-z]+\://#', $url)) {
                $url = 'http://' . $url;
            }
            $replace[$hash] = $this->link($link, $url, $htmlOptions);
        }
        return strtr($text, $replace);
    }
}
