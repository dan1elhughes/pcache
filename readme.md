# Pcache

## Installation

```
composer require xes/pcache
```

## Usage

```php
require 'vendor/autoload.php';

$predis = new Predis\Client();
$cache = new xes\Pcache($predis);

$keyname = 'someKey';
$TTL = 60;

echo $cache->get($keyname, $TTL, function() {
	// Some slow function such as a database call
	sleep(1);
	return 'hello world';
});
```
