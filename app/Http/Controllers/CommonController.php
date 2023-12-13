<?php

namespace App\Http\Controllers;

use App\Models\CheckListItem;
use App\Models\CheckListItemsEntry;
use App\Models\FormHat;
use App\Models\FormTv;
use App\Models\FormTvSite2;
use App\Models\Section;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function generatePDF(Request $request)
    {
        // dd($request->entry_id);
        // Fetch the data with relationships and section/subsection information
        // 'checkList.person_name',
        $checklistItemsEntries = CheckListItemsEntry::with('checkListItem.section', 'checkListItem.subSection')
            ->where('entry_id', $request->entry_id)
            ->get();

        // dd($checklistItemsEntries->checkList);
        // Initialize an array to store data by sections
        $dataBySections = [];

        foreach ($checklistItemsEntries as $item) {

            $checklist = $item->checkList;
            $itemDataOverall = [
                'person_name' => $checklist->person_name ?? '',
                'time' => $checklist->time ?? '',
                'finish_time' => $checklist->finish_time ?? '',
                'date' => $checklist->date ?? '',
                'approved_by' => $checklist->approved_by ?? '',
                'inspected_by' => $checklist->inspected_by ?? '',
            ];

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
            $dataBySections['overallData'] = $itemDataOverall;
        }

        // Pass the data to the Blade view
        $data = [
            'dataBySections' => $dataBySections,
        ];

        // dd($dataBySections);

        // dd($data);

        $pdf = Pdf::loadView('pdf', ['dataBySections' => $dataBySections]);

        $pdf->setPaper('L');
        $pdf->output();
        $canvas = $pdf->getDomPDF()->getCanvas();

        $height = $canvas->get_height();
        $width = $canvas->get_width();

        // $canvas->set_opacity(.1,"Multiply");

        $canvas->set_opacity(.5);

        $canvas->page_text($width / 3, $height / 2, 'AIF.com - verified', null,
            20, [1, 0, 0], 3, 2, -30);

        // $pdf->setWatermarkImage(public_path('logo.png'));

        return $pdf->stream();
    }

    public function generatePDFATP(Request $request)
    {
        // Fetch the data with relationships and section/subsection information
        $checklistItemsEntries = CheckListItemsEntry::with('checkListItem.section', 'checkListItem.subSection')
            ->where('entry_id', $request->entry_id)
            ->get();

        // Initialize an array to store data by sections
        $dataBySections = [];

        foreach ($checklistItemsEntries as $item) {

            $checklist = $item->checkList;
            $itemDataOverall = [
                'person_name' => $checklist->person_name ?? '',
                'time' => $checklist->time ?? '',
                'finish_time' => $checklist->finish_time ?? '',
                'date' => $checklist->date ?? '',
                'approved_by' => $checklist->approved_by ?? '',
                'inspected_by' => $checklist->inspected_by ?? '',
            ];

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
                'ATP check RLU' => $item->ATP_check_RLU ?? '',
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

        $pdf = Pdf::loadView('atp', ['dataBySections' => $dataBySections]);

        $pdf->setPaper('L');
        $pdf->output();
        $canvas = $pdf->getDomPDF()->getCanvas();

        $height = $canvas->get_height();
        $width = $canvas->get_width();

        // $canvas->set_opacity(.1,"Multiply");

        $canvas->set_opacity(.5);

        $canvas->page_text($width / 3, $height / 2, 'AIF.com - verified', null,
            20, [1, 0, 0], 3, 2, -30);

        // $pdf->setWatermarkImage(public_path('logo.png'));

        return $pdf->stream();
    }

    public function generatePDFGMP(Request $request)
    {
        // Fetch the data with relationships and section/subsection information
        $checklistItemsEntries = CheckListItemsEntry::with('checkListItem.section', 'checkListItem.subSection')
            ->where('entry_id', $request->entry_id)
            ->get();

        // Initialize an array to store data by sections
        $dataBySections = [];

        //    foreach ($checklistItemsEntries as $item) {

        //     $checklist = $item->checkList;
        //     $itemDataOverall = [
        //         'person_name' =>  $checklist->person_name ?? '',
        //         'time'        =>  $checklist->time ?? '',
        //         'finish_time' => $checklist->finish_time ?? '',
        //         'date'        => $checklist->date ?? '',
        //         'approved_by' => $checklist->approved_by ?? '',
        //         'inspected_by' => $checklist->inspected_by ?? ''
        //     ];

        //     // Get the related section and subsection information from checkListItem
        //     $sectionName = $item->checkListItem->section->name ?? '';
        //     $subSectionName = $item->checkListItem->subSection->name ?? '';

        //     // Create an array for the item with keys and values
        //     $itemData = [
        //         'Sub-Section' => $subSectionName,
        //         'Checklist Item' => $item->checkListItem->name ?? '',
        //         'Visual Insp Allergen Free' => $item->visual_insp_allergen_free ?? '',
        //         'Micro SPC Swab' => $item->micro_SPC_swab ?? '',
        //         'Chemical Residue Check' => $item->chemical_residue_check ?? '',
        //         'TP Check RLU' => $item->TP_check_RLU ?? '',
        //         'Comments & Corrective Actions' => $item->comments_corrective_actions ?? '',
        //         'Action Taken' => $item->action_taken ?? '',
        //         // 'Is Approved' => $item->is_approved ?? '',
        //     ];

        //     // Append the item data to the corresponding section in the array
        //     $dataBySections[$sectionName][$subSectionName][] = $itemData;
        //     $dataBySections['overallData'] = $itemDataOverall;
        // }

        foreach ($checklistItemsEntries as $item) {

            $checklist = $item->checkList;
            $itemDataOverall = [
                'person_name' => $checklist->person_name ?? '',
                'time' => $checklist->time ?? '',
                'finish_time' => $checklist->finish_time ?? '',
                'date' => $checklist->date ?? '',
                'approved_by' => $checklist->approved_by ?? '',
                'inspected_by' => $checklist->inspected_by ?? '',
            ];

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
            $dataBySections['overallData'] = $itemDataOverall;
        }

        // Pass the data to the Blade view
        $data = [
            'dataBySections' => $dataBySections,
        ];

        // dd($data);

        $pdf = Pdf::loadView('gmp', ['dataBySections' => $dataBySections]);

        $pdf->setPaper('L');
        $pdf->output();
        $canvas = $pdf->getDomPDF()->getCanvas();

        $height = $canvas->get_height();
        $width = $canvas->get_width();

        // $canvas->set_opacity(.1,"Multiply");

        $canvas->set_opacity(.5);

        $canvas->page_text($width / 3, $height / 2, 'AIF.com - verified', null,
            20, [1, 0, 0], 3, 2, -30);

        // $pdf->setWatermarkImage(public_path('logo.png'));

        return $pdf->stream();
    }

    public function generatePDFChemical(Request $request)
    {
        // Fetch the data with relationships and section/subsection information
        $checklistItemsEntries = CheckListItemsEntry::with('checkListItem.section', 'checkListItem.subSection')
            ->where('entry_id', $request->entry_id)
            ->get();

        // Initialize an array to store data by sections
        $dataBySections = [];

        foreach ($checklistItemsEntries as $item) {

            $checklist = $item->checkList;
            $itemDataOverall = [
                'person_name' => $checklist->person_name ?? '',
                'time' => $checklist->time ?? '',
                'finish_time' => $checklist->finish_time ?? '',
                'date' => $checklist->date ?? '',
                'approved_by' => $checklist->approved_by ?? '',
                'inspected_by' => $checklist->inspected_by ?? '',
            ];

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

        $pdf = Pdf::loadView('chemical', ['dataBySections' => $dataBySections]);

        $pdf->setPaper('L');
        $pdf->output();
        $canvas = $pdf->getDomPDF()->getCanvas();

        $height = $canvas->get_height();
        $width = $canvas->get_width();

        // $canvas->set_opacity(.1,"Multiply");

        $canvas->set_opacity(.5);

        $canvas->page_text($width / 3, $height / 2, 'AIF.com - verified', null,
            20, [1, 0, 0], 3, 2, -30);

        // $pdf->setWatermarkImage(public_path('logo.png'));

        return $pdf->stream();
    }

    public function generatePDFMicro(Request $request)
    {
        // Fetch the data with relationships and section/subsection information
        $checklistItemsEntries = CheckListItemsEntry::with('checkListItem.section', 'checkListItem.subSection')
            ->where('entry_id', $request->entry_id)
            ->get();

        // Initialize an array to store data by sections
        $dataBySections = [];

        foreach ($checklistItemsEntries as $item) {

            $checklist = $item->checkList;
            $itemDataOverall = [
                'person_name' => $checklist->person_name ?? '',
                'time' => $checklist->time ?? '',
                'finish_time' => $checklist->finish_time ?? '',
                'date' => $checklist->entry_detail ?? '',
                'approved_by' => $checklist->approved_by ?? '',
                'inspected_by' => $checklist->inspected_by ?? '',
            ];

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
            $dataBySections['overallData'] = $itemDataOverall;
        }

        // Pass the data to the Blade view
        $data = [
            'dataBySections' => $dataBySections,
        ];

        // dd($data);

        $pdf = Pdf::loadView('micro', ['dataBySections' => $dataBySections]);

        $pdf->setPaper('L');
        $pdf->output();
        $canvas = $pdf->getDomPDF()->getCanvas();

        $height = $canvas->get_height();
        $width = $canvas->get_width();

        // $canvas->set_opacity(.1,"Multiply");

        $canvas->set_opacity(.5);

        $canvas->page_text($width / 3, $height / 2, 'AIF.com - verified', null,
            20, [1, 0, 0], 3, 2, -30);

        // $pdf->setWatermarkImage(public_path('logo.png'));

        return $pdf->stream();
    }

    public function generatePDFHAT(Request $request)
    {
        // dd($request->entry_id);
        $record = FormHat::find($request->entry_id);
        if ($record) {
            // Extract the date from the retrieved record.
            $date = $record->date;
            // Calculate the start and end of the month for the extracted date.
            $startOfMonth = Carbon::parse($date)->startOfMonth();
            $endOfMonth = Carbon::parse($date)->endOfMonth();

            // Query the database to get all records within the same month as the extracted date.
            $recordsWithinMonth = FormHat::whereBetween('date', [$startOfMonth, $endOfMonth])->get();

            // Now, $recordsWithinMonth contains all records within the same month as the 'id' parameter.
        }
        // dd($recordsWithinMonth);
        // $data = [
        //     'dataBySections' => $recordsWithinMonth,
        // ];

        $pdf = Pdf::loadView('hat', ['dataBySections' => $recordsWithinMonth]);

        $pdf->setPaper('L');
        $pdf->output();
        $canvas = $pdf->getDomPDF()->getCanvas();

        $height = $canvas->get_height();
        $width = $canvas->get_width();

        // $canvas->set_opacity(.1,"Multiply");

        $canvas->set_opacity(.5);

        $canvas->page_text($width / 3, $height / 2, 'AIF.com - verified', null,
            20, [1, 0, 0], 3, 2, -30);

        // $pdf->setWatermarkImage(public_path('logo.png'));

        return $pdf->stream();
    }

    public function generatePDFTVC(Request $request)
    {
        // dd($request->entry_id);
        $record = FormTvSite2::find($request->entry_id);
        if ($record) {
            // Extract the date from the retrieved record.
            $date = $record->date;
            // Calculate the start and end of the month for the extracted date.
            $startOfMonth = Carbon::parse($date)->startOfMonth();
            $endOfMonth = Carbon::parse($date)->endOfMonth();

            // Query the database to get all records within the same month as the extracted date.
            $recordsWithinMonth = FormTvSite2::whereBetween('date', [$startOfMonth, $endOfMonth])->get();

            // Now, $recordsWithinMonth contains all records within the same month as the 'id' parameter.
        }
        // dd($recordsWithinMonth);
        // $data = [
        //     'dataBySections' => $recordsWithinMonth,
        // ];

        $pdf = Pdf::loadView('tvchiller', ['dataBySections' => $recordsWithinMonth]);
        // $pdf = PDF::loadView('pdf.test_pdf')->setPaper('a4', 'landscape');
        $pdf->setPaper('L', 'landscape');
        $pdf->output();
        $canvas = $pdf->getDomPDF()->getCanvas();

        $height = $canvas->get_height();
        $width = $canvas->get_width();

        // $canvas->set_opacity(.1,"Multiply");

        $canvas->set_opacity(.5);

        $canvas->page_text($width / 3, $height / 2, 'AIF.com - verified', null,
            20, [1, 0, 0], 3, 2, -30);

        // $pdf->setWatermarkImage(public_path('logo.png'));

        return $pdf->stream();
    }

    public function generatePDFTVS(Request $request)
    {
        $record = FormTv::find($request->entry_id);
        if ($record) {
            // Extract the date from the retrieved record.
            $date = $record->date;
            // Calculate the start and end of the month for the extracted date.
            $startOfMonth = Carbon::parse($date)->startOfMonth();
            $endOfMonth = Carbon::parse($date)->endOfMonth();
            // Query the database to get all records within the same month as the extracted date.
            $recordsWithinMonth = FormTv::whereBetween('date', [$startOfMonth, $endOfMonth])->get();
            // Now, $recordsWithinMonth contains all records within the same month as the 'id' parameter.
        }
        // dd($recordsWithinMonth);
        // $data = [
        //     'dataBySections' => $recordsWithinMonth,
        // ];

        $pdf = Pdf::loadView('tvs', ['dataBySections' => $recordsWithinMonth]);
        // $pdf = PDF::loadView('pdf.test_pdf')->setPaper('a4', 'landscape');
        $pdf->setPaper('L', 'landscape');
        $pdf->output();
        $canvas = $pdf->getDomPDF()->getCanvas();

        $height = $canvas->get_height();
        $width = $canvas->get_width();

        // $canvas->set_opacity(.1,"Multiply");

        $canvas->set_opacity(.5);

        $canvas->page_text($width / 3, $height / 2, 'AIF.com - verified', null,
            20, [1, 0, 0], 3, 2, -30);

        // $pdf->setWatermarkImage(public_path('logo.png'));

        return $pdf->stream();
    }
}
