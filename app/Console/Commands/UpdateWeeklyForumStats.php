<?php

namespace App\Console\Commands;

use App\Models\WeeklyRecapForum;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class UpdateWeeklyForumStats extends Command
{
    protected $signature = 'update:weekly-forum-stats';
    protected $description = 'Update weekly forum stats';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            // Get the start of the week (last Friday) and the end of the week (next Friday)
            $startOfWeek = Carbon::now()->previous(Carbon::FRIDAY)->startOfDay(); // Last Friday
            $endOfWeek = Carbon::now()->next(Carbon::FRIDAY)->endOfDay(); // Next Friday

            // Calculate the week number within the month
            $weekOfMonth = ceil($startOfWeek->day / 7);

            // Fetch the tags from the external database, limited to IDs 2 to 6
            $tags = DB::connection('forum')
                ->table('flw8_tags')
                ->whereBetween('id', [2, 6])
                ->get(['id', 'name']);

            foreach ($tags as $tag) {
                // Count approved discussions from last Friday to next Friday for each tag
                $totalPostings = DB::connection('forum')
                    ->table('flw8_discussion_tag')
                    ->join('flw8_discussions', 'flw8_discussion_tag.discussion_id', '=', 'flw8_discussions.id')
                    ->where('flw8_discussion_tag.tag_id', $tag->id)
                    ->where('flw8_discussions.is_approved', 1)
                    ->whereBetween('flw8_discussions.created_at', [$startOfWeek, $endOfWeek])
                    ->count();

                // Store the result in the local database with a new record each time
                WeeklyRecapForum::create([
                    'divisi' => $tag->name, // Using the tag name as the 'divisi'
                    'total_postingan' => $totalPostings,
                    'minggu' => $weekOfMonth, // Week number within the month
                    'bulan' => $startOfWeek->month,
                    'tahun' => $startOfWeek->year,
                ]);

                // Log the success of each tag's data processing
                Log::info("Weekly recap for tag '{$tag->name}' has been successfully generated.", [
                    'divisi' => $tag->name,
                    'total_postingan' => $totalPostings,
                    'minggu' => $weekOfMonth,
                    'bulan' => $startOfWeek->month,
                    'tahun' => $startOfWeek->year,
                ]);
            }

            $this->info('Weekly forum recap has been generated successfully!');
        } catch (Exception $e) {
            // Log the error if something goes wrong
            Log::error('Failed to generate weekly forum recap.', [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->error('An error occurred while generating the weekly forum recap. Check the logs for more details.');
        }
    }
}
