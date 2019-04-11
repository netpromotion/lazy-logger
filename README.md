# Lazy logger

`Psr\Log\LoggerInterface` implementation which creates real logger only if it is needed.

Ideal for entry points which needs to be quick, but should use robust logger like Monolog for error logging.

```php
<?php // entry-point.php

$logger = new Netpromotion\LazyLogger\LazyLogger(function () {
    return Symfony::getInstance()->getContainer()->get('logger');
});

try {
    doSomething();
    exit(0);
} catch (Exception $e) {
    $logger->error('Something went wrong.', [
        'message' => $e->getMessage(),
    ]);
    exit(-1);
}
```
