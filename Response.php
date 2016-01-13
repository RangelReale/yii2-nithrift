<?php

namespace RangelReale\nithrift;

use Yii;
use yii\web\Response;

/**
 * Response of a Thrift request.
 * @author Rangel Reale <rangelspam@gmail.com>
 */
class Response extends Response
{
    /**
     * @var string set response format to Thrift format
     */
    public $format = ResponseFormatter::FORMAT_THRIFT;

    /**
     * @var object the Thrift processor to use
     */
    protected $_processor;

    /**
     * Constructs the object.
     * @param object $processor the Thrift processor to use
     */
    public function __construct($processor)
    {
        parent::__construct();
        $this->_processor = $processor;
    }

    /**
     * Creates the Thrift transport.
     * By default use the Thrift component to create.
     * @return \Thrift\Transport\TTransport the transport to use
     */    
    protected function createTransport()
    {
        return Yii::$app->get('thrift')->createTransport();
    }

    /**
     * Creates the Thrift protocol.
     * By default use the Thrift component to create.
     * @param \Thrift\Transport\TTransport $transport
     * @return \Thrift\Protocol\TProcotol the protocol to use
     */    
    protected function createProtocol($transport)
    {
        return Yii::$app->get('thrift')->createProtocol($transport);
    }

    /**
     * Output the processor using the transport and protocol.
     */
    protected function sendContent()
    {
        $transport = $this->createTransport();
        $protocol = $this->createProtocol($transport);

        $transport->open();
        if (!is_null($this->_processor)) {
            $this->_processor->process($protocol, $protocol);
        }
        $transport->close();
    }

    /**
     * Adds the Thrift formatter to the default formatters.
     * @return array the formatters that are supported by default
     */
    protected function defaultFormatters()
    {
        return array_merge(parent::defaultFormatters(), [
            'thrift' => 'RangelReale\nithrift\ResponseFormatter',
        ]);
    }
}
