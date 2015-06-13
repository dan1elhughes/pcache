<?php namespace xes;
class Pcache {
	private $predis;

	public function __construct(\Predis\Client $predis) {
		$this->predis = $predis;
	}

	public function get($name, $expiry, callable $callback) {
		if (!$this->predis->exists($name)) {
			$this->predis->set($name, $callback());
			$this->predis->expire($name, $expiry);
		}

		return $this->predis->get($name);
	}
}
?>
