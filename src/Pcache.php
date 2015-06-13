<?php namespace xes;
class Pcache {
	private $predis;
	private static $times;

	public function __construct(\Predis\Client $predis) {
		$this->predis = $predis;

		self::$times = [
			'short' => 0.5*60,
			'default' => 1*60,
			'long' => 5*60,
		];
	}

	public function get($name, $expiry, callable $callback) {
		if (!$this->predis->exists($name)) {
			$this->predis->set($name, $callback());
			$this->predis->expire($name, $expiry);
		}

		return $this->predis->get($name);
	}

	public static function setTimes($times) {
		self::$times = $times;
	}

	public static function expire($lengthName) {
		foreach(self::$times as $name => $mins) {
			if ($lengthName == $name) { return $mins; }
		}
		throw new Exception("Invalid expiry: $length");

	}
}
?>
