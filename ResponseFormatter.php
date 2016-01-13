<?php

namespace RangelReale\nithrift;

use yii\base\Component;
use yii\web\ResponseFormatterInterface;

/**
 * Thrift reponse format.
 * @author Rangel Reale <rangelspam@gmail.com>
 */
class ResponseFormatter extends Component implements ResponseFormatterInterface
{
    /**
     * Format name.
     */
    const FORMAT_THRIFT = 'thrift';

    /**
     * @var string content type
     */
    public $contentType = 'application/x-thrift';

    /**
     * Adds the Thrift content type.
     * @param ThriftResponse $response
     */
    public function format($response)
    {
        $response->getHeaders()->set('Content-Type', $this->contentType);
    }
}
