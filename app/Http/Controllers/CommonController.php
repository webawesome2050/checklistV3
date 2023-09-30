<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use App\Models\CheckListItem;
use App\Models\SubSectionItem;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CheckListItemsEntry;

class CommonController extends Controller
{ 
    public function generatePDF(Request $request) { 
        // Fetch the data with relationships and section/subsection information
        $checklistItemsEntries = CheckListItemsEntry::with('checkListItem.section', 'checkListItem.subSection')
            ->where('entry_id', $request->entry_id)
            ->get();

        // Initialize an array to store data by sections
        $dataBySections = [];

        foreach ($checklistItemsEntries as $item) {
            // Get the related section and subsection information from checkListItem
            $sectionName = $item->checkListItem->section->name ?? '';
            $subSectionName = $item->checkListItem->subSection->name ?? '';

            // Create an array for the item with keys and values
            $itemData = [
                'Sub-Section' => $subSectionName,
                'Checklist Item' => $item->checkListItem->name ?? '',
                'Visual Insp Allergen Free' => $item->visual_insp_allergen_free ?? '',
                'Micro SPC Swab' => $item->micro_SPC_swab ?? '',
                'Chemical Residue Check' => $item->chemical_residue_check ?? '',
                'TP Check RLU' => $item->TP_check_RLU ?? '',
                'Comments & Corrective Actions' => $item->comments_corrective_actions ?? '',
                'Action Taken' => $item->action_taken ?? '',
                // 'Is Approved' => $item->is_approved ?? '',
            ];

            // Append the item data to the corresponding section in the array
            $dataBySections[$sectionName][$subSectionName][] = $itemData;
        }

        // Pass the data to the Blade view
        $data = [
            'dataBySections' => $dataBySections,
        ];

        // dd($data);

        $pdf = Pdf::loadView('pdf', ['dataBySections' => $dataBySections]);

        $pdf->setPaper('L');
        $pdf->output();
        $canvas = $pdf->getDomPDF()->getCanvas();

        $height = $canvas->get_height();
        $width = $canvas->get_width();

        // $canvas->set_opacity(.1,"Multiply");

        $canvas->set_opacity(.5);

        $canvas->page_text($width/3, $height/2, 'AIF.com - verified', null,
        20, array(1,0,0),3,2,-30);


        // $pdf->setWatermarkImage(public_path('logo.png'));
        
        return $pdf->stream();
    }
}
