# chubbyphp-psr7-middleware-singlepass-to-multipass-adapter

[![Build Status](https://api.travis-ci.org/chubbyphp/chubbyphp-psr7-middleware-singlepass-to-multipass-adapter.png?branch=master)](https://travis-ci.org/chubbyphp/chubbyphp-psr7-middleware-singlepass-to-multipass-adapter)
[![Total Downloads](https://poser.pugx.org/chubbyphp/chubbyphp-psr7-middleware-singlepass-to-multipass-adapter/downloads.png)](https://packagist.org/packages/chubbyphp/chubbyphp-psr7-middleware-singlepass-to-multipass-adapter)
[![Latest Stable Version](https://poser.pugx.org/chubbyphp/chubbyphp-psr7-middleware-singlepass-to-multipass-adapter/v/stable.png)](https://packagist.org/packages/chubbyphp/chubbyphp-psr7-middleware-singlepass-to-multipass-adapter)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-psr7-middleware-singlepass-to-multipass-adapter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-psr7-middleware-singlepass-to-multipass-adapter/?branch=master)

## Description

This adapter can be used, if you dlike to use single pass (no response argument given) middleware within
a multipass environment as for example [slim][2] or [zend-expressive][3] are.

## Requirements

 * php: ~5.4
 * psr/http-message: ~1.0

## Installation

Through [Composer](http://getcomposer.org) as [chubbyphp/chubbyphp-psr7-middleware-singlepass-to-multipass-adapter][1].

## Usage

The variable `$next`, within the single pass middleware arguments will always be a \Closure from the adapter.

```{.php}
<?php

use Chubbyphp\Psr7SinglePassToMultiPassAdapter\Psr7SinglePassToMultiPassAdapter;

$existingSinglePassMiddleware = function (RequestInterface $request, callable $next) {
    $request = $request->withHeader('X-Custom', '1');

    $response = $next($request);

    $body = $response->getBody();
    $body->seek(0, SEEK_END);
    $body->write('<!-- provided by x-custom -->');

    return $response;
};

$adapter = new Psr7SinglePassToMultiPassAdapter($existingSinglePassMiddleware);

$response = $adapter($request, $response, $next);
```

[1]: https://packagist.org/packages/chubbyphp/chubbyphp-psr7-middleware-singlepass-to-multipass-adapter
[2]: https://github.com/slimphp/slim
[3]: https://github.com/zendframework/zend-expressive

## Copyright

Dominik Zogg 2016
