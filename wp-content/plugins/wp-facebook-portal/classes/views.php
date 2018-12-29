<?php
/**
 * Displaying data class
 *
 * @copyright  Copyright (c) Yoshika
 * @author     Yoshika (@rnsk)
 * @link       http://rnsk.net/
 * @license    MIT License
 */
class PluginView
{

/**
 * Instance of Helper class
 *
 * @var object
 */
    protected $Helper;

/**
 * Directory for view file.
 *
 * @var string
 */
    public $viewDir = 'templates';

/**
 * Name of the view file
 *
 * @var string
 */
    public $viewFile = null;

/**
 * View file extension
 *
 * @var string
 */
    public $viewExt = '.php';

/**
 * Path to base directory.
 *
 * @var string
 */
    protected $basePath = null;

/**
 * Construct
 *
 * @return void
 */
    public function __construct()
    {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }

        $this->basePath = dirname(dirname(__FILE__));

        if (!class_exists('PluginHelper')) {
            require_once dirname(__FILE__) . DS . 'helpers.php';
        }
        $this->Helper = new PluginHelper;
    }

/**
 * Display the given file
 *
 * @return void
 */
    public function render($data = array(), $view = null)
    {
        if (!is_null($view)) {
            $this->setView($view);
        }

        $viewPath = $this->basePath . DS . $this->viewDir . DS . $this->viewFile . $this->viewExt;
        try {
            if (!file_exists($viewPath)) {
                throw new Exception('File does not exist.');
            }
            extract($data);
            include $viewPath;
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

/**
 * Redirects to given $url
 *
 * @param string $url
 * @return void
 */
    public function redirect($url)
    {
        wp_redirect($url);
        exit;
    }

/**
 * Set the Session Messages
 *
 * @param array $messages
 * @return void
 */
    public function setAlert($messages = array(), $type = 'updated')
    {
        if ((is_array($messages)) && (!empty($messages))) {
            $str = '';
            foreach ($messages as $message) {
                $str .= '<div class="' . $type . '"><p><strong>' . $message . '</strong></p></div>';
            }
            if ((array_key_exists('alertMessage', $_SESSION)) && (!empty($_SESSION['alertMessage']))) {
                $str = $_SESSION['alertMessage'] . $str;
            }
            $_SESSION['alertMessage'] = $str;
        }
    }

/**
 * Get the Session Messages
 *
 * @return str
 */
    public function getAlert()
    {
        if (($_SESSION) && (array_key_exists('alertMessage', $_SESSION))) {
            $messages = $_SESSION['alertMessage'];
            unset($_SESSION['alertMessage']);
            return $messages;
        }
    }

/**
 * Display the Session Message
 *
 * @return str
 */
    public function theAlert()
    {
        $messages = $this->getAlert();
        if (!empty($messages)) {
            echo $messages;
        }
    }

/**
 * Set the view file
 *
 * @var string view
 * @return void
 */
    public function setView($view = null)
    {
        $this->viewFile = $view;
    }
}
