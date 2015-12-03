<?php
namespace Altair\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * Altair component
 * To convert special characters of variables to HTML entities
 * that is passeed to View object.
 * However, decode encoded text that will be input into a form
 */
class AltairComponent extends Component
{

    /**
     * An optional argument defining the encoding used when converting characters
     * default UTF-8
     *
     * @var string
     */
    private $_charset;

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'charset' => 'UTF-8'
    ];

    /**
     * Initialize properties.
     *
     * @param array $config The config data.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->_charset = $this->_config['charset'];
    }

    /*
     * start up
     * Set helpers in controller
     *
     * @param Event $evet
     */
    public function startup($event)
    {
        $event->subject->helpers += [
            'Altair.Escape' => [
                'charset' => $this->_charset
            ],
            'Form' => [
                'className' => 'Altair.DecodeForm'
            ]
        ];
    }
}
