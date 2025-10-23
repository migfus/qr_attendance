<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

use App\Models\Task;


class TaskExport implements FromView {
    public $date_start;
    public $date_end;
    public $template_ids;

    public function __construct($date_start, $date_end, $template_ids) {
        $this->date_start = $date_start;
        $this->date_end = $date_end;
        $this->template_ids = $template_ids;
    }

    public function view(): View {
        $tasks =  Task::query()
            ->when($this->date_start, function ($q) {
                $q->where('created_at', '>=', Carbon::parse($this->date_start)->format('Y-m-d 00:00:01'));
            })
            ->when($this->date_end, function ($q) {
                $q->where('created_at', '<=', Carbon::parse($this->date_end)->format('Y-m-d 23:59:59'));
            })
            ->when($this->template_ids, function ($q) {
                $q->whereNotIn('task_template_id', $this->template_ids);
            })
            ->where('user_assigned_id', auth()->user()->id)
            ->with(['task_template.group', 'task_priority.hero_icon', 'task_status.hero_icon'])
            ->get();



        return view('exports.tasks', [
            'tasks' => $tasks,
            'date_start' => $this->date_start ?? "Start",
            'date_end' => $this->date_end ?? Carbon::now()->format('m/d/Y'),
            'user_name' => auth()->user()->name,
            'now' => Carbon::now()->format('m/d/Y h:m A'),
            'count' => $tasks->count()
        ]);
    }
}
