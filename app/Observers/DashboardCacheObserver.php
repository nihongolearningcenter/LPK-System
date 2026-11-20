<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

/**
 * Flush cached queries whenever key models change.
 */
class DashboardCacheObserver
{
    protected function flush(): void
    {
        Cache::forget('admin.dashboard.stats');
        Cache::forget('home.programs');
        Cache::forget('home.instructors');
        Cache::forget('programs.all');
    }

    public function created($model): void  { $this->flush(); }
    public function updated($model): void  { $this->flush(); }
    public function deleted($model): void  { $this->flush(); }
    public function restored($model): void { $this->flush(); }
}
