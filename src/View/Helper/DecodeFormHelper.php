<?php
namespace Altair\View\Helper;

use Cake\View\Helper\FormHelper;

/**
 * Override FormHelper
 * To Decode encoded text that will be input into a form
 */
class DecodeFormHelper extends FormHelper
{

    public function __construct($View, array $config = [])
    {
        parent::__construct($View, $config);
    }

    /**
     * Override input method in parent (FormHelper)
     * Decode encoded text that will be input into a form
     *
     * @param String $fieldName
     * @param array $options
     * @return String $result Decode to plane text from encoded text
     */
    public function input($fieldName, array $options = [])
    {
        $result = parent::input($fieldName, $options);
        $result = htmlspecialchars_decode($result);
        return $result;
    }
}
