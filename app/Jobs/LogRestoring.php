<?php

namespace App\Jobs;

use App\DTO\ConsentDTO;
use App\Models\Consent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LogRestoring implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The podcast instance.
     *
     * @var array
     */
    private array $consent;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $consent)
    {
        $this->consent = $consent;
    }

    /**
     * Execute the job.
     *
     */
    public function handle(Consent $consent): void
    {
        $consent->fill(ConsentDTO::createFromArray($this->consent));

        if ($consent->save()) {
            Log::info("Request {$this->consent['request_id']} was saved successful");
        }
    }
}
