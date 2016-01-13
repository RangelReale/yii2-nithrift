<?php

namespace RangelReale\nithrift;

use Yii;
use yii\base\Component;
use Thrift\ClassLoader\ThriftClassLoader;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TPhpStream;
use Thrift\Transport\TBufferedTransport;

/**
 * Thrift is the application component for configuration.
 * Must be added as a component to the root Application.
 * @author Rangel Reale <rangelspam@gmail.com>
 */
class Thrift extends Component
{
    /**
    * The path alias of the thrift generated files.
    * Default is @app/generated/gen-php
    * @var string
    */
    public $genPath = '@app/generated/gen-php';
    
    /**
     * The list of namespace definitions and the related relative path from the thrift
     * generated file directory.
     * If path is blank, it is assumed to be the root $genPath.
     * 
     * The format is like this:
     * ```
     * [
     *    'namespace' => 'path',
     *    ...
     * ]
     * ```
     * 
     * It can also be specified without a path, in this case the default path is used.
     * ```
     * [
     *    'namespace',
     *    'namespace',
     *    ...
     * ]
     * ```
     * @var array
     */    
    public $definitions;
    
    /**
     * Initialize the component.
     */    
    public function init()
    {
        parent::init();
        $this->register();
    }
    
    /**
     * Creates the Thrift transport.
     * @return \Thrift\Transport\TTransport the transport to use
     */    
    public function createTransport()
    {
        return new TBufferedTransport(new TPhpStream(TPhpStream::MODE_R | TPhpStream::MODE_W));
    }

    /**
     * Creates the Thrift protocol.
     * @param \Thrift\Transport\TTransport $transport
     * @return \Thrift\Protocol\TProcotol the protocol to use
     */    
    public function createProtocol($transport)
    {
        return new TBinaryProtocol($transport, true, true);
    }
    
    /**
     * Register the definitions with the Thrift class loader.
     */
    protected function register()
    {
        // use loader only for definitions, base class should use the Composer loader
        $loader = new ThriftClassLoader();
        
        // load Thrift definitions
        if (is_array($this->definitions)) 
        {
            foreach ($this->definitions as $dnamespace => $dpath)
            {
                if (is_numeric($dnamespace)) {
                    $dnamespace = $dpath;
                    $dpath = '';
                }
                $curPath = $this->genPath;
                if ($dpath != '') {
                    $curPath .= '/' . $dpath;
                }                   
                $loader->registerDefinition($dnamespace, Yii::getAlias($curPath));
            }
        }
        
        // Yii autoloader must be last
        spl_autoload_unregister(['Yii', 'autoload']);
        $loader->register();        
        spl_autoload_register(['Yii', 'autoload'], true, true);        
    }
}