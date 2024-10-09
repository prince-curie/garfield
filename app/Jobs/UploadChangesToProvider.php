<?php

namespace App\Jobs;

use Cache;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class UploadChangesToProvider implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $lockKey = (string) config('cache.keys.user-data-upload-provider.lock');

        Cache::lock($lockKey, 3)->block(2, function () {
            $cacheKey = (string) config('cache.keys.user-data-upload-provider.changes');

            $data = (array) Cache::get($cacheKey, []);

            if (empty($data)) {
                return;
            }

            $maxSizeOfData = 1000;
            $dataForUpload = array_splice($data, 0, $maxSizeOfData);

            $uploadPayload = $this->preparePayload($dataForUpload);

            try {
                Log::info((string) json_encode($uploadPayload));
            } catch (Exception $exception) {
                report($exception);

                array_push($data, $dataForUpload);
            }

            Cache::add($cacheKey, $data);

        });
    }

    /**
     * @param  array<int, array<string, string>>  $data
     * @return array{'batches': array<array{'subscribers': array<int, array<string, string>>}>}
     */
    private function preparePayload(array $data): array
    {
        return [
            'batches' => [[
                'subscribers' => $data,
            ]],
        ];
    }
}
