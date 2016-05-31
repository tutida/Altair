<?php
namespace Altair\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Cake\Core\Configure;
use Cake\ORM\Entity;

/**
 * Escape helper
 * To convert special characters of variables to HTML entities
 * that is passeed to View object
 */
class EscapeHelper extends Helper
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'charset' => null,
        'double' => true,
        'escape' => true
    ];

    /**
     * beforeRender
     * Get the variables that are set in the object
     *
     * @param Eevent $event
     * @param string $vireFile
     */
    public function beforeRender($event, $viewFile)
    {
        if(Configure::read('Altair.escape')) {
            $event->subject->viewVars = $this->automate($event->subject->viewVars);
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
            $viewVars[$key] = $this->escape($var);
        }
        return $viewVars;
    }

    private function escape($value)
    {
        if (is_string($value)) {
            $value = $this->escapeString($value);
        } else if (is_array($value)) {
            $value = $this->escapeArray($value);
        } else if (is_object($value)) {
            $value = $this->escapeObject($value);
        }
        return $value;
    }

    /**
     * h
     *
     * @param string $text Text to escape
     * @return string escaped text
     */
    private function h($text) {
        return h($text, $this->_double, $this->_charset);
    }

    /**
     * Escape the String type of variable
     *
     * @param string $value
     * @return string escaped text
     */
    private function escapeString($value) {
        return $this->h($value);
    }

    /**
     * Escape recursive the Array type of variable
     *
     * @param array $value
     * @return array escaped array
     */
    private function escapeArray($value) {
        if (!is_array($value)) {
            return $this->escape($value);
        }
        foreach ($value as $key => $prop) {
            $value[$key] = $this->escape($prop);
        }
        return $value;
    }

    /**
     * Escape recursive the Object type of variable
     *
     * @param Object $value
     * @return Object escaped object
     */
    private function escapeObject($value) {
        if (!is_object($value)) {
            return $value;
        }
        if (isset($value->escape) && $value->escape === false) {
            return $value;
        }
        if ($value instanceof Entity) {
            $errors = $value->errors();
            $invalid = $value->invalid();
            $properties = $value->visibleProperties();
            foreach ($properties as $prop) {
                // To not use the entity setter
                $value->set($prop, $this->escape($value->{$prop}), ['setter' => false]);
            }
            $value->errors($errors);
            $value->invalid($invalid);
            return $value;
        }

        if ($this->hasIterator($value)) {
            foreach ($value as $key => $prop) {
                $value->{$key} = $this->escape($prop);
            }
            return $value;
        }

        return $value;
    }
    /**
     * Check that object have iterator
     *
     * @param Object $obj
     * @return bool $hasIterator
     */
    private function hasIterator($obj) {
        $hasIterator = false;
        foreach ($obj as $key => $dummy) {
            $hasIterator = true;
            break;
        }
        return $hasIterator;
    }
}
