<?php
namespace Altair\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;

/**
 * Altair component
 * To convert special characters of variables to HTML entities
 * that is passeed to View object.
 * However, decode encoded text that will be input into a form
 */
class AltairComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'charset' => 'UTF-8',
        'double' => true,
        'escape' => true
    ];

    /**
     * Initialize properties.
     *
     * @param array $config The config data.
     * @return void
     */
    public function initialize(array $config)
    {
        Configure::write('Altair.escape', $this->config('escape'));
    }

    /*
     * start up
     * Set helpers in controller
     *
     * @param Event $event
     */
    public function startup($event)
    {
        $event->subject->helpers += [
            'Altair.Escape' => $this->config()
        ];
    }

    /**
     * escape on/off
     *
     * @return
     */
    public function escape($enabled = true)
    {
        if (!is_bool($enabled)) {
            return false;
        }
        $this->config('escape', $enabled);
        Configure::write('Altair.escape', $this->config('escape'));
        return true;
    }
}
