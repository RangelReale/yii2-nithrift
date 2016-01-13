# yii2-nithrift
Thrift extension for Yii2

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist RangelReale/yii2-nithrift "*"
```

or add

```
"RangelReale/yii2-nithrift": "*"
```


# Minimum Requirement

- Thrift version 0.9.3.
  To install thrift, check http://thrift.apache.org/download
- Yii 2.0.0

# Usage

The Thrift base classes must be accessible someway. I put the thrift/lib/php/lib
path in my application root, and add this to composer.json:

```
"autoload": {
    "psr-0": {
        "Thrift": "lib"
    }
}
```

Create a directory named `generated` in your application root.
Put your .thrift files into it, and generate the php wrapper on the 
default gen-php path, using command below.

```
thrift --gen php:server path/to/the/thrift/file
```

The files should be like this:

-- ROOT
    -- config
    -- controllers
    -- generated
        file1.thrift
        file2.thrift
        -- gen-php
            -- file1
            -- file2
    ...

In the component configuration add the `thrift` component, with the
Thrift definitions.

```php
return [
    'component' => [
        'thrift' => [
            'class' => 'RangelReale\nithrift\Thrift',
            'definitions' => [
                'shared',
                'tutorial',
            ],
        ]
    ]
]
```

Implement your Thrift handlers in a separate class file, following the
thrift documentation. I recommend using a `services` directory on the application 
root. There is no need to override or implement any other interface.

Your controller should extend `RangelReale\nithrift\Controller`. You can use
the custom Action class to implement the services.

```php
class ApiController extends \RangelReale\nithrift\Controller
{
    public function actions()
    {
        return [
            'calculator' => [
                'class' => 'RangelReale\nithrift\Action',
                'handler' => 'app\services\CalculatorHandler',
                'processor' => 'tutorial\CalculatorProcessor',
            ]
        ];
    }
}
```

If you prefer, you can implement an inline action, and either return a
\RangelReale\nithrift\Response object, or a processor object directly.

```php
public function actionCalculator()
{
    $handler = new \app\services\CalculatorHandler();
    $processor = new \tutorial\CalculatorProcessor($handler);

    return new ThriftResponse($processor); // could be `return $processor;`
}
```

# Author

Rangel Reale (rangelspam@gmail.com)
