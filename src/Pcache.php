<?php namespace xes;
class Pcache {
	private $predis;
	private $enabled = true;

	public function __construct(\Predis\Client $predis) {
		$this->predis = $predis;

		try {
			$predis->connect();
		} catch (\Predis\Connection\ConnectionException $exception) {
			$this->enabled = false;
		}
	}

	public function get($name, $expiry, callable $callback) {
		if (!$this->enabled or ($expiry == 0)) {
			return $callback();
		}

		if (!$this->predis->exists($name)) {
			$this->predis->set($name, $callback());
			$this->predis->expire($name, $this->randomise($expiry));
		}

		return $this->predis->get($name);
	}

	public function getClient() {
		return $this->predis;
	}

	public function clear() {
		$count = 0;
		foreach ($this->predis->keys('*') as $key) {
			$this->predis->del($key);
			$count++;
		}
		return $count;
	}

	private function randomise($time) {
		return $time + (mt_rand(-$time/10, $time/10));
	}
}
?>
