<?php

namespace Modules\Game\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Education\Entities\Lesson;
use Modules\Game\Http\Requests\MentalRequest;
use Modules\Game\Services\FlashCardService;
use Modules\Game\Services\MentalService;
use PhpOffice\PhpWord\TemplateProcessor;

class MentalController extends Controller
{
    public function __construct(protected MentalService $service){}

    public function store(MentalRequest $request)
    {
        $params = $request->validated();
        $mental = null;
        if(($params['game_type']??'train') == 'train'){
            $mental = $this->service->create($params);
            if(is_string($mental)){
                return response()->download($mental);
            }
        }else{
            $mental = app()->make(FlashCardService::class)->create($params);
        }
        return response()->json(['item' => $mental]);
    }
    
    public function update(MentalRequest $request)
    {
        $params = $request->validated();
        if(($params['game_type']??'train') == 'train'){
            $mental = $this->service->update($params);
        }else{
            $mental = app()->make(FlashCardService::class)->update($params);
        }
        return response()->json(['item' => $mental]);
    }
    public function test()
    {
        // 'id' => 'sometimes',
        // 'answers'=> 'sometimes|array',
        // 'room' => 'sometimes|integer',
        // 'lesson_id' => 'sometimes|integer',
        // 'limit' => 'sometimes|integer',
        // 'type' => 'sometimes',
        // 'number_of_tasks' => 'sometimes',
        // 'number_delay' => 'sometimes',
        // 'interval' => 'sometimes|sometimes',
        // 'game_type' => 'sometimes|in:flash-card,train',
        // 'group_mode' => 'sometimes',
        // 'same_numbers' => 'boolean',
        // 'dificulty' => 'array'
        $lesson = Lesson::first();
        $params = [
            'room' => 6,
            'lesson_id' => $lesson->id,
            'limit' => 12,
            'game_type' => 'train',
            'group_mode' => false,
            'same_numbers' => false,
            'dificulty' => [],
            'number_delay' => 1,
            'interval' => 1,
            'number_of_tasks' => 100,
            "type" => "game"
        ];
        // check for same numbers
        // check for same room
        // check for not some room
        $mental = $this->service->create($params);
        $numbers = $mental->numbers;
        $sum_length = 0;
        $count = 0;
        foreach($numbers as $list){
            
            $oldStr = null;
            $oldLength  = null;
            foreach($list as $number){
                $strnumber = strval($number);
                $length = strlen($strnumber);
                if($strnumber[0] == '-')
                    $length--;
                $sum_length += 1;
                $count = $count + (substr_count($strnumber, '7') > 0);
                if($oldStr == null){
                    $oldStr = $strnumber;
                    $oldLength = $length;
                }else{
                    if(($oldLength + 1 == $length) || ($oldLength - 1 == $length)){
                        $oldStr = $strnumber;
                        $oldLength = $length;
                    }
                    // else{
                    //     dd('error', $oldStr, $strnumber, $list);
                    // }
                }
            }
        }   
        dd('ok', $count / $sum_length, $lesson, $numbers);
    }
    public function generateFiles()
    {
        for ($rooms = 1; $rooms <= 12; $rooms++) {
            
            for ($numbers = 1; $numbers <= 10; $numbers++) { 
                $replacement = [];
                $templateProcessor = new TemplateProcessor(storage_path('/export-templates/game.docx'));
                $number = 0;
                for ($i=1; $i <= $numbers; $i++) { 
                     $ch = [];
                    for ($j=1; $j <= 10; $j++) {
                        ++$number;
                        $number_list = '${' . $number . '1}';
                        for ($i1 = 2; $i1 <= $rooms; $i1++) { 
                            $number_list .= '<w:br/>${' . $number . $i1 .'}';
                        }
                        $ch['task' . strval($j)] = $number_list;
                        $ch['tn' . strval($j)] = $number;
                        $ch['ans'. strval($j)] = '${a' . $number .'}';
                    }
                    $replacement[]= $ch;
                }
                $templateProcessor->cloneBlock('block_name', 0, true, false, $replacement);
                $templateProcessor->cloneBlock('answer_block', 0, true, false, $replacement);
                $directory = storage_path('/export-templates/temp/' . $numbers * 10 . '-tasks');
                Storage::disk('export_templates')->makeDirectory('/temp/' . $numbers * 10 . '-tasks');
                
                $pathToSave = $directory . '/' . $rooms . '.docx';
                $templateProcessor->saveAs($pathToSave);
            }
        }
    }

}