<?php

namespace RangelReale\nithrift;

use Yii;

/**
 * Base controller for Thrift handling.
 * @author Rangel Reale <rangelspam@gmail.com>
 */
class Controller extends \yii\web\Controller
{
    /**
     * Thrift uses POST, must disable csrf validation
     */
    public $enableCsrfValidation = false;
    
    /**
     * Initializes the component.
     */
    public function init()
    {
        parent::init();

        // ensure definitions are registered
        Yii::$app->get('thrift');
        
        // set Thrift response format
        Yii::$app->response->format = ResponseFormatter::FORMAT_THRIFT;
    }

    /**
     * Assume return is processor if object is returned for action.
     * @return Response
     */
    public function runAction($id, $params = [])
    {
        $result = parent::runAction($id, $params);
        // assume as Thrift processor if object
        if (is_object($result) and !($result instanceof Response)) {
          $result = new Response($result);
        }
        return $result;
    }
}
