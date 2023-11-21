<?php

namespace App\Livewire;

use App\Models\CheckList;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class ChecklistCalendar extends FullCalendarWidget
{
    public function fetchEvents(array $fetchInfo): array
    {

        return CheckList::query()
            ->where('created_at', '>=', $fetchInfo['start'])
            ->where('created_at', '<=', $fetchInfo['end'])
            ->where('status', true)
            ->where('is_approved', true)
            ->get()
            ->map(function (Checklist $task) {
                $start = $task->date ?? now();
                $end = $task->date ?? now();

                return EventData::make()
                    ->id($task->id)
                    ->title(strip_tags($task->name))
                    ->start($start)
                    ->end($end)
                    ->url($this->generateEditUrl($task))
                    // ->openUrlInNewTab()
                    ->toArray();
            })
            ->toArray();
    }

    private function generateEditUrl(Checklist $task): string
    {

        if ($task->id == 1 || $task->id == 6) {
            return url(route('generate.pdf', $task->id));
        } elseif ($task->id == 3 || $task->id == 10) {
            return url(route('generate.atp', $task->id));
        } elseif ($task->id == 4 || $task->id == 9) {
            return url(route('generate.chemical', $task->id));
        } elseif ($task->id == 5 || $task->id == 8) {
            return url(route('generate.micro', $task->id));
        } elseif ($task->id == 7) {
            return url(route('generate.gmp', $task->id));
        } elseif ($task->id == 7) {
            return url(route('generate.gmp', $task->id));
        } else {
            return url(route('generate.pdf', $task->id));
        }
    }
}
