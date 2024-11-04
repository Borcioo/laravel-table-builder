<?php

namespace Borek\LaravelTableBuilder\Tables\Concerns;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

trait HasCache
{
    protected bool $shouldCache = false;
    protected ?string $cacheKey = null;
    protected ?int $cacheTTL = null;
    protected array $invalidationEvents = [];

    public function cache(string $key = null, int $ttl = 3600): self
    {
        $this->shouldCache = true;
        $this->cacheKey = $key;
        $this->cacheTTL = $ttl;
        return $this;
    }

    public function invalidateOn(string|array $events): self
    {
        $this->invalidationEvents = array_merge(
            $this->invalidationEvents,
            (array) $events
        );
        return $this;
    }

    protected function getCacheKey(array $params): string
    {
        $baseKey = $this->cacheKey ?? class_basename($this->query->getModel());
        $paramsKey = md5(serialize($params));
        return "table_builder:{$baseKey}:{$paramsKey}";
    }

    protected function getDataFromCache(array $params): ?array
    {
        if (!$this->shouldCache) {
            return null;
        }

        return Cache::get($this->getCacheKey($params));
    }

    protected function storeInCache(array $params, array $data): void
    {
        if (!$this->shouldCache) {
            return;
        }

        Cache::put(
            $this->getCacheKey($params),
            $data,
            $this->cacheTTL
        );

        $this->registerInvalidationEvents();
    }

    protected function registerInvalidationEvents(): void
    {
        $model = $this->query->getModel();

        foreach ($this->invalidationEvents as $event) {
            $model::created(function () {
                $this->invalidateCache();
            });

            $model::updated(function () {
                $this->invalidateCache();
            });

            $model::deleted(function () {
                $this->invalidateCache();
            });
        }
    }

    protected function invalidateCache(): void
    {
        if (!$this->cacheKey) {
            return;
        }

        Cache::tags("table_builder:{$this->cacheKey}")->flush();
    }
}
