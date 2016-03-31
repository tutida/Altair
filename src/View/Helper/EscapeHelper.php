<?php
namespace Altair\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Cake\Core\Configure;

/**
 * Escape helper
 * To convert special characters of variables to HTML entities
 * that is passeed to View object
 */
class EscapeHelper extends Helper
{
    /**
     * An optional argument defining the encoding used when converting characters
     * default UTF-8
     *
     * @var string
     */
    private $_charset;
    private $_double;
    private $_escape;

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * beforeRender
     *
     * @param View $view
     * @param array $config
     */
    public function __construct(View $view, $config = [])
    {
        parent::__construct($view, $config);
        $this->_charset = $this->_config['charset'];
        $this->_double = $this->_config['double'];
        $this->_escape = $this->_config['escape'];
    }


    /**
     * BeforeRender
     * Get the variables that are set in the object
     *
     * @param Eevent $event
     * @param string $vireFile
     */
    public function beforeRender($event, $viewFile)
    {
        if(Configure::read('Altair.escape')) {
            $viewVars = $event->subject->viewVars;
            $viewVars = $this->automate($viewVars);
        }
    }

    /**
     * It divides the processing by the type of variable
     *
     * @param array $viewVars variables before escape
     * @return array $viewVars variables after escape
     */
    private function automate($viewVars)
    {
        foreach ($viewVars as $key => $var) {
            if (is_string($var)) {
                $viewVars[$key] = $this->_stringEscape($var);
            } else if (is_array($var)) {
                $viewVars[$key] = $this->_arrayEscape($var);
            } else if (is_object($var)) {
                $viewVars[$key] = $this->_objectEscape($var);
            } else {
                $viewVars[$key] = $var;
            }
        }
        return $viewVars;
    }

    /**
     * _h
     *
     * @param string $text Text to escape
     * @return string escaped text
     */
    private function _h($text) {
        if (!is_string($text)) {
            return $text;
        }

        return h($text, $this->_double, $this->_charset);
    }

    /**
     * Escape the String type of variable
     *
     * @param string $value
     * @return string escaped text
     */
    private function _stringEscape($value) {
        return $this->_h($value);
    }

    /**
     * Escape recursive the Array type of variable
     *
     * @param array $value
     * @return array escaped array
     */
    private function _arrayEscape($value) {
        if (is_array($value)) {
            return array_map(array($this, '_arrayEscape'), $value);
        }

        if (!is_string($value)) {
            return $value;
        }

        return $this->_h($value);
    }

    /**
     * Escape recursive the Object type of variable
     *
     * @param Object $value
     * @return Object escaped object
     */
    private function _objectEscape($value) {
        if (!is_object($value)) {
            return $value;
        }
        if (isset($value->escape) && $value->escape === false) {
            return $value;
        }

        if ($this->_hasIterator($value)) {
            foreach ($value as $entityObj) {
                $entityObj = $this->_objectEscape($entityObj);
            }
        } else {
            $entityArray = $value->toArray();
            foreach ($entityArray as $key => $prop) {
                $value->$key = $this->_h($prop);
            }
        }

        return $value;
    }
    /**
     * Check that object have iterator
     *
     * @param Object $obj
     * @return bool $hasIterator
     */
    private function _hasIterator($obj) {
        $hasIterator = false;
        foreach ($obj as $key => $dummy) {
            $hasIterator = true;
            break;
        }
        return $hasIterator;
    }
}
