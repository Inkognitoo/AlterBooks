<?php

namespace App\Jobs;

use App\Events\BookProcessed;
use App\Models\Book\BookFormat;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessBook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $book_format;

    /**
     * Create a new job instance.
     *
     * @param BookFormat $book_format
     */
    public function __construct(BookFormat $book_format)
    {
        $this->book_format = $book_format;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->book_format->convert();

        event(new BookProcessed($this->book_format->getBook()));
    }
}
