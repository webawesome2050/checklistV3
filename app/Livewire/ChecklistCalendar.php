<?php

namespace App\Livewire;

use App\Models\CheckList;

use Filament\Widgets\Widget;
use App\Filament\Resources\ChecklistsResource;
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
            ->map(
                fn(Checklist $task) => EventData::make()
                    ->id($task->id)
                    ->title(strip_tags($task->name))
                    ->start($task->date)
                    ->end($task->date)
                    ->url(ChecklistsResource::getUrl('edit', [$task->id]))
                    ->toArray()
            )
            ->toArray();
    }
    
}
