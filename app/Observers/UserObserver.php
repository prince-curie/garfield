<?php

namespace App\Observers;

use App\Jobs\UploadChangesToProvider;
use App\Models\User;
use Cache;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Collection;

class UserObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $lockKey = (string) config('cache.keys.user-data-upload-provider.lock');
        
        Cache::lock($lockKey, 3)->block(2, function () use ($user) {
            $payload = array_merge(['email' => $user->email, $user->getChanges()]);
            
            $cacheKey = (string) config('cache.keys.user-data-upload-provider.changes');
            
            $data = (array) Cache::get($cacheKey, []);
            
            $currentDataSize = array_push($data, $payload);
            
            $maxSizeOfData = 1000;
            
            if ($currentDataSize >= $maxSizeOfData) {
                UploadChangesToProvider::dispatch();

                return;
            }

            Cache::add($cacheKey, $data);
        });
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
