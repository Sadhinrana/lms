<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\QuizAttempt;

class MarkQuizAttemptAsCompleteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $attemptId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($attemptId)
    {
        $this->attemptId = $attemptId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        QuizAttempt::where('id', $this->attemptId)->update(
            [
                'is_completed' => true,
                'end_time' => date('Y-m-d H:i:s')
            ]);
    }
}
