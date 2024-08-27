<?php

namespace App\Jobs;

use App\Models\WeeklyRecapForum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class FetchWeeklyForumStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $week;
    protected $month;
    protected $year;

    public function __construct($week, $month, $year)
    {
        $this->week = $week;
        $this->month = $month;
        $this->year = $year;
    }

    public function handle()
    {
        // Connection to the external database
        $externalDbConnection = DB::connection('external_forum');

        // Query to fetch total approved postings for each tag
        $data = $externalDbConnection->table('flw8_discussion_tag')
            ->join('flw8_discussion', 'flw8_discussion_tag.discussion_id', '=', 'flw8_discussion.id')
            ->join('flw8_tags', 'flw8_discussion_tag.tag_id', '=', 'flw8_tags.id')
            ->where('flw8_discussion.is_approve', 1)
            ->whereBetween('flw8_discussion.created_at', [$this->getStartDate(), $this->getEndDate()])
            ->select('flw8_tags.name as divisi', DB::raw('COUNT(flw8_discussion.id) as total_postingan'))
            ->groupBy('flw8_tags.name')
            ->get();

        // Insert data into the local weekly_forum_stats table
        foreach ($data as $row) {
            WeeklyRecapForum::updateOrCreate(
                [
                    'divisi' => $row->divisi,
                    'minggu' => $this->week,
                    'bulan' => $this->month,
                    'tahun' => $this->year,
                ],
                [
                    'total_postingan' => $row->total_postingan
                ]
            );
        }
    }

    protected function getStartDate()
    {
        return \Carbon\Carbon::now()->setISODate($this->year, $this->week)->startOfWeek()->toDateString();
    }

    protected function getEndDate()
    {
        return \Carbon\Carbon::now()->setISODate($this->year, $this->week)->endOfWeek()->toDateString();
    }
}
