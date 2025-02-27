<?php
namespace Cache;
class APC {
    private int  $expire;
    private bool $active = false;

    public function __construct(int $expire = 3600) {
        $this->expire = $expire;
        $this->active = function_exists('apc_cache_info') && ini_get('apc.enabled');
    }

    public function get(string $key): array|string|null {
        return $this->active ? apc_fetch(CACHE_PREFIX . $key) : false;
    }

    public function set(string $key, array|string|null $value, int $expire = 0): bool {
        return $this->active ? apc_store(CACHE_PREFIX . $key, $value, $this->expire) : false;
    }

    public function delete(string $key): void {
        if (!$this->active) {
            return;
        }

        $cache_info = apc_cache_info('user');
        $cache_list = $cache_info['cache_list'];

        foreach ($cache_list as $entry) {
            if (strpos($entry['info'], CACHE_PREFIX . $key) === 0) {
                apcu_delete($entry['info']);
            }
        }
    }
}