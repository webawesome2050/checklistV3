<?php

namespace App\Livewire;

use App\Filament\Resources\ChecklistsResource;
use App\Models\CheckList;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class ChecklistCalendar extends FullCalendarWidget
{
    // protected static string $view = 'livewire.checklist-calendar';
    public function fetchEvents(array $fetchInfo): array
    {

        return CheckList::query()
            ->where('created_at', '>=', $fetchInfo['start'])
            ->where('created_at', '<=', $fetchInfo['end'])
            ->get()
            ->map(function (Checklist $task) {
                $start = $task->date ?? now();
                $end = $task->date ?? now();

                return EventData::make()
                    ->id($task->id)
                    ->title(strip_tags($task->name))
                    ->start($start)
                    ->end($end)
                    ->url(ChecklistsResource::getUrl('edit', [$task->id]))
                    ->toArray();
            })
            ->toArray();
    }
}
