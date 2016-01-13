<?php

namespace RangelReale\nithrift;

use Yii;
use yii\base\Action;

/**
 * Action for a Thrift request.
 * @author Rangel Reale <rangelspam@gmail.com>
 */
class Action extends Action
{
    /**
     * Class for the Thrift handler.
     * Normally overriden from the generated files.
     * Will be created using Yii::createObject.
     * @var object
     */
    public $handler;

    /**
     * Class for the Thrift processor.
     * Normally generated by Thrift, with the "Processor" suffix.
     * Will be created using Yii::createObject.
     * @var type 
     */
    public $processor;
    
    /**
     * Initialize the component.
     */    
    public function init()
    {
        parent::init();
        
        // ensure definitions are registered
        Yii::$app->get('thrift');        
    }
    
    /**
     * Process the Thrift request;
     * @return ThriftResponse
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        if (empty($this->handler)) {
            throw new \yii\base\InvalidConfigException('Thrift handler is required');
        }
        if (empty($this->processor)) {
            throw new \yii\base\InvalidConfigException('Thrift processor is required');
        }
        
        $handler = Yii::createObject($this->handler);
        $processor = Yii::createObject($this->processor, [$handler]);
        
        return new Response($processor);
    }
}