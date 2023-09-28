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

        $entry_id = $request->entry_id;
    // Fetch the data with relationships
    $checklistItems = CheckListItemsEntry::with('section', 'subSection', 'subSubSection')
    ->where('entry_id', $entry_id)
    ->get();

    // Group the checklist items by section
    $groupedItems = $checklistItems->groupBy('section_id');

    // Fetch section names for the grouped sections
    $sections = Section::whereIn('id', $groupedItems->keys())->get();

    // Pass the data to the Blade view
    $data = [
        'groupedItems' => $groupedItems,
        'sections' => $sections,
    ];

    // dd($data);

// Load the Blade view for the PDF
$pdf = PDF::loadView('pdf', $data);

// Generate and return the PDF
return $pdf->stream('pdf');



        $entryId = $request->entry_id;
        // Fetch your checklist data based on the $entry_id using the CheckListItemsEntry model
        // $checklistItems = CheckListItemsEntry::where('entry_id', $entryId)->get();

          // Fetch the data with relationships
    $checklistItems = CheckListItemsEntry::with('section', 'subSection', 'subSubSection')
    ->where('entry_id', $entryId)
    ->get();

        // Pass the checklist data to the Blade view for rendering
        $data = [
            'checklistItems' => $checklistItems,
        ];

   

        dd($data);

        // Load the Blade view for the PDF
        $pdf = PDF::loadView('pdf.checklist', $data);

        // Generate and return the PDF
        return $pdf->stream('checklist.pdf');



        $checklistItems = CheckListItem::where('check_list_id', 1)->get();
        $checklistItemsBySectionAndSubsection = $checklistItems->groupBy(['section_id', 'sub_section_id']);

        // dd($checklistItemsBySectionAndSubsection);

        foreach ($checklistItemsBySectionAndSubsection as $sectionId => $subsectionGroups) {
            $sectionName = $subsectionGroups->first()->first()->section->name;
            $itemCount = count($subsectionGroups);
            $sectionName =   $sectionName;

            $sectionComponents = [];
            $subsectionNameArray  = [];
            foreach ($subsectionGroups as $subsectionId => $checklistItemsInSubsection) {
                $subsectionName = $checklistItemsInSubsection->first()->first()->subSection->name;
                // Retrieve sub-section items for the current sub-section
                $subSectionItems = SubSectionItem::where('sub_section_id', $subsectionId)->get();
                $formFields = [];
                if ($subSectionItems->count() > 0) {
                    $radioOptions = [];
                    foreach ($subSectionItems as $subSectionItem) {
                        $radioOptions[$subSectionItem->name] = $subSectionItem->name;
                    }
                    $itemCode = Radio::make("sub_section_items_$subsectionId")
                        ->label('')
                        ->options($radioOptions)
                        ->inline();
                } else {
                    $formFields = [];
                }
                
                $matchingItem = $checklistItemsInSubsection->first(function ($item) use ($subsectionId) {
                    return $item->sub_section_id === $subsectionId;
                });
                $matchingItem = $checklistItemsInSubsection->first(function ($item) use ($subsectionId) {
                    return $item->sub_section_id === $subsectionId;
                });

                if ($matchingItem) {
                    $subsectionName = $matchingItem->subSection->name;  
                    $subsectionSection = Section::make($subsectionName)
                    ->columns(4)
                    ->compact();
                    
                } else {
                    $subsectionSection = Section::make('Section')
                    ->columns(4)
                    ->compact()
                    ->collapsed(); 
                }
                if(count($subSectionItems) > 0) {
                    // \Log::info($formFields);
                } 
                // $formFields = [];
                $stepFields = [];
                if(count($subSectionItems) > 0) {
                    $stepFields[]   =  
                    Section::make('Select Item Spec')
                    ->icon('heroicon-m-megaphone')
                    // ->aside()
                   ->schema([ 
                    $formFields[] = $itemCode
                    ]);
                }
                foreach ($checklistItemsInSubsection as $checklistItem) {
                   
                    $stepFields[]   =  
                 Section::make($checklistItem->name)
                 ->aside()
                ->schema([ 
                     $formFields[] = Select::make("visual_insp_allergen_free_{$checklistItem->id}")
                        ->label('Visual insp allergen free')
                        ->options([
                            'Accept' => 'Accept',
                            'Reject' => 'Reject',
                            'Not in Use' => 'Not in Use'
                        ])
                        ->native(false),
                        // $formFields[] = TextInput::hidden()
                        // ->label('Chemical Residue Check')->name('chemical_residue_check'),
                        $formFields[] =  Hidden::make("entry_id_$checklistItem->id"),
                        $formFields[] = TextInput::make("chemical_residue_check_$checklistItem->id")->label('Chemical Residue Check')->name('chemical_residue_check'),
                       
                        $formFields[] = Textarea::make("comments_corrective_actions_$checklistItem->id")->label('Comments & Corrective Actions')->name('comments_corrective_actions')
                        ->rows(1),        
                        $formFields[] = Radio::make("action_taken_$checklistItem->id")
                        ->label('Action Taken')
                        ->inline()
                        ->options([
                            'Yes' => 'Yes',
                            'No' => 'No'
                        ]),                 
                    ])->columns(4)->compact(); 
                } 

                $subsectionSection->schema($stepFields); 
                $sectionComponents[] = $subsectionSection; 
            }

            $sectionStep = Tab::make($sectionName)->schema($sectionComponents);
            $wizardSteps[] = $sectionStep;
        }


        $data = [
            [
                'quantity' => 1,
                'description' => '1 Year Subscription',
                'price' => '129.00'
            ]
        ];
     
        $pdf = Pdf::loadView('pdf', ['data' => $data]);
        return $pdf->stream(); 
    }
}
