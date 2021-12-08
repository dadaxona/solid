<?php
namespace Modules\Game\Services;

use Carbon\Carbon;
use Modules\Core\Services\ServiceContract;
use Modules\Education\Entities\Lesson;
use Modules\Game\Entities\Mental;
use Modules\Game\Strategies\Game1Strategy;
use Modules\Game\Strategies\Game2Strategy;
use Modules\Game\Strategies\Game3Strategy;
use Modules\Game\Strategies\Game4Strategy;
use Modules\Game\Strategies\Game5Strategy;
use Modules\Game\Strategies\Game6Strategy;
use PhpOffice\PhpWord\TemplateProcessor;

class MentalService extends ServiceContract {

    public function __construct(Mental $mental)
    {
        $this->model = $mental;
    }
    public function create($params)
    {
        
        if(($params['type']??'game') == 'file'){
            $mental_count = $this->model->where("user_id", auth()->id()??1)->where('created_at', '>=', Carbon::today())->count();
            if ($mental_count > 4){
                return abort(444, 'Sizning kunlik fayl limitingiz tugagan!');
            }
        }
        $tasks = [];
        $lesson = Lesson::find($params['lesson_id']);
        $strategy = Game1Strategy::class;
        switch ($lesson->slug){
            case '1-game':
                $strategy = Game1Strategy::class;
                break;
            case '2-game':
                $strategy = Game2Strategy::class;
                break;
            case '3-game':
                $strategy = Game3Strategy::class;
                break;
            case '4-game':
                $strategy = Game4Strategy::class;
                break;
            case '5-game':
                $strategy = Game5Strategy::class;
                break;
            case '6-game':
                $strategy = Game6Strategy::class;
                break;
        }
        for( $i=0; $i < $params['number_of_tasks']; $i++)
        {
            $numbers = (new $strategy)->generate($params['limit'], $params['room'], $params['same_numbers']??false, $params['dificulty']??[]);
            $tasks[] = $numbers;
        }
        $model = new $this->model;
        $model->numbers = $tasks;
        $model->room = $params['room'];
        $model->limit = $params['limit'];
        $model->number_of_tasks = $params['number_of_tasks'];
        $model->number_delay = $params['number_delay'];
        $model->interval = $params['interval'];
        $model->group_mode = $params['group_mode']??true;
        $model->lesson_id = $params['lesson_id'];
        $model->type = $params['type'];
        $model->answers = [];
        $model->result = [];
        $model->user_id = auth()->id()??1;
        $model->save();
        if ($params['type'] == 'file'){
            return $this->getFile($model);
        }
        return $model;
    }
    
    public function update($data)
    {
        $model = $this->model->find($data['id']);
        $numbers = $model->numbers;
        $answers = $data['answers'];
        $result = ['correct' => 0, 'incorrect' => 0];

        foreach($answers as $key => $answer){
            $sum = 0;
            $nums = $numbers[$key];
            foreach($nums as $num){
                $sum = $sum + $num;
            }
            if($sum == $answer){
                $result['correct']++;
            }else{
                $result['incorrect']++;
            }
        }
        $model->update(['result' => $result, 'answers' => $data['answers']]);
        return $model;
    }
    public function get($id)
    {
        $model = $this->model->findOrFail($id)->load('lesson.course');
        return $model;
    }
    public function getFile($model){
        $templateProcessor = null;

        if(($model->room) < 5 || ($model->room > 9 && ($model->room / 10) < 5)){
            $templateProcessor = new TemplateProcessor(storage_path('/export-templates/game-4.docx'));
        }else{
            $templateProcessor = new TemplateProcessor(storage_path('/export-templates/game.docx'));
        }
        $numbers = array_chunk($model->numbers, 10);
        $lesson = $model->lesson;
        $replacement = [];
        $number = 0;
        foreach($numbers as $nums){
            $ch = [];
            foreach($nums as $index => $num){
                $ch['task' . strval($index+1)] = join(" <w:br/>",$num);
                $ch['tn' . strval($index+1)] = ++$number;
                $ch['ans'. strval($index+1)] = array_sum($num);
            }
            $replacement[]= $ch;
        }
        $templateProcessor->cloneBlock('block_name', 0, true, false, $replacement);
        $templateProcessor->cloneBlock('answer_block', 0, true, false, $replacement);
        $templateProcessor->setValue('theme', $lesson->name);
        $pathToSave = storage_path('/export-templates/temp/game_' . ($model->id % 4) .'.docx');
        $templateProcessor->saveAs($pathToSave);
        return $pathToSave;
    }
}