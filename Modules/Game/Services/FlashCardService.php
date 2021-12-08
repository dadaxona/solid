<?php
namespace Modules\Game\Services;

use Modules\Core\Services\ServiceContract;
use Modules\Game\Entities\Mental;

class FlashCardService extends ServiceContract {

    public function __construct(Mental $mental)
    {
        $this->model = $mental;
    }
    public function create($params)
    {
        $model = new $this->model;
        $model->numbers = $this->getRandomNumbers($params['room'], $params['limit']);
        $model->room = $params['room'];
        $model->limit = $params['limit'];
        $model->number_of_tasks = 1;
        $model->number_delay = $params['number_delay'];
        $model->interval = $params['interval'];
        $model->group_mode = $params['group_mode']??true;
        $model->type = 'game';
        $model->game_type = 'flash-card';
        $model->answers = [];
        $model->result = [];
        $model->user_id = auth()->id()??1;
        $model->save();
        return $model;
    }
    
    public function update($data)
    {
        $model = $this->model->find($data['id']);
        $numbers = $model->numbers;
        $answers = $data['answers'];
        $result = ['correct' => 0, 'incorrect' => 0];

        foreach($answers as $key => $answer){
            $number = $numbers[$key];
            if($number == $answer){
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
    public function getRandomNumbers($room, $limit)
    {
        $res = [];
        for ($i=0; $i < $limit; $i++) { 
            $res[] = $this->getRandomNumber($room);
        }
        return $res;
    }
    public function getRandomNumber($room)
    {
        switch ($room) {
            case 1:
                return random_int(0,9);
            case 2:
                return random_int(10,99);
            case 3:
                return random_int(100,999);
            case 4:
                return random_int(1000,9999);
            case 5:
                return random_int(10000,99999);
            case 6:
                return random_int(100000,999999);
        }
    }
}