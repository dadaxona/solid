<?php 

namespace Modules\Game\Strategies;

use Illuminate\Support\Arr;

class GameStrategy
{
    const CASES = [];
    const CURRENT_THEME_NUMBER = [];
    private $count_theme_numbers = 0;
    public function generate($limit, $rooms, $same_numbers, $dificulty)
    {
        $switch_room = false;
        if($rooms > 9){
            $rooms = intval($rooms / 10);
            $switch_room = true;
        }
        $array = $this->generateArray($limit, $rooms, $same_numbers, $dificulty, $switch_room);
        $res_array = [];
        foreach ($array as $number) {
            $n = 0;
            foreach($number as $value){
                $n = $n * 10 + intval($value);
            }
            $res_array[] = $n;
        }
        return $res_array;
        
    }
    public function generateArray($limit, $rooms, $same_numbers, $dificulty, $switch_room)
    {
        try {
            return $this->getGame($limit, $rooms, $same_numbers, $dificulty, $switch_room);
        } catch (\Throwable $th) {
            return $this->generateArray($limit, $rooms, $same_numbers, $dificulty, $switch_room);
        }
    }
    public function getGame($limit = 1, $rooms = 1, $same_numbers = false, $dificulty = [], $switch_room = false)
    {
        $numbers = [];
        if($same_numbers){
            $generated = $this->getGame($limit, 1);
            foreach($generated as $row){
                $number = [];
                for ($i=0; $i < $rooms; $i++) {
                    $number[] = $row[0];
                }
                $numbers[] = $number;
            }
            return $numbers;
        }
        $sum = [];
        $sum[] = $this->getSum($rooms);
        for ($row = 1; $row <= $limit; $row++) { 
            $plus = $this->getPlusAviable($sum[$row-1], $row, $rooms, $limit, $dificulty);
            $minus = $this->getMinusAviable($sum[$row-1], $row, $rooms, $limit, $dificulty);
            $row_numbers = [];
            if($plus && $minus){
                $l = ['-', '+'];
                switch ($l[array_rand($l, 1)]) {
                    case '+':
                        $row_numbers = $this->getRowNumbers($plus, $row, $switch_room);
                        break;
                    default:
                        $row_numbers = $this->getRowNumbers($minus, $row, $switch_room);
                        break;
                }
                // dd('ikkalasi ham mumkin');
            }else{
                if($minus == false){
                    $row_numbers = $this->getRowNumbers($plus, $row, $switch_room);
                    // dd('plusdan olamiz');
                }else{
                    $row_numbers = $this->getRowNumbers($minus, $row, $switch_room);
                    // dd('minusdan olamiz');
                }
            }
            $numbers[] = $row_numbers;
            for ($col=0; $col < $rooms; $col++) { 
                $sum[$row][$col] = $sum[$row-1][$col] + $row_numbers[$col];
            }
            
        }
        // dd($numbers);
        return $numbers;
    }
    public function getRowNumbers($aviableNumbers, $row, $switch_room){
        // dd('aviable', $aviableNumbers);
        $res = [];
        foreach ($aviableNumbers as $key => $aviables) {
            if($key == 0 && ($row % 2 == 0) && $switch_room){
                $r = 0;
            }else{
                $case = $aviables;
                if($key == 1 && $switch_room){
                    $case = array_diff($case, [0]);
                }
                $r = $case[array_rand($case, 1)];
            }
            $res[] = $r;
        }
        return $res;
    }
    public function getSum($rooms)
    {
        $res = [];
        for ($i=0; $i < $rooms; $i++) { 
            $res[] = 0;
        }
        return $res;
    }
    public function getPlusAviable($sum, $row, $rooms, $limit, $dificulty){
        $res = [];
        for($col = 0; $col < $rooms; $col++){
            $case = $this->getCases($sum[$col]);
            $case = array_filter($case, fn($val) => $val >= 0);
            if($row == 1 || $row == $limit){
                 $case = array_diff($case, [0]);
            }
            if($col == 0){
                 $case = array_diff($case, [0]);
            }
            if(count($dificulty) && !count(array_intersect($dificulty, [$col]))){
                $case = array_diff($case, $this::CURRENT_THEME_NUMBER);
            }
            $res[] = $case;
            if(count($case) == 0){
                return false;
            }
        }
        return $res;
    }
    
    public function getMinusAviable($sum, $row, $rooms, $limit, $dificulty){
        $res = [];
        for($col = 0; $col < $rooms; $col++){
            $case = $this->getCases($sum[$col]);
            $case = array_filter($case, fn($val) => $val <= 0);
            if($row == 1 || $row == $limit){
                 $case = array_diff($case, [0]);
            }
            if($col == 0){
                 $case = array_diff($case, [0]);
            }
            if(count($case) == 0){
                return false;
            }
            if(count($dificulty) && !count(array_intersect($dificulty, [$col]))){
                $case = array_diff($case, $this::CURRENT_THEME_NUMBER);
            }
            $res[] = $case;
        }
        return $res;
    }
    public function getCases($number)
    {
        $numbers = $this::CASES[$number];
        $theme_numbers = array_intersect($numbers, $this::CURRENT_THEME_NUMBER);
        foreach($theme_numbers as $num){
            for ($i=0; $i < 2; $i++) { 
                $numbers[] = $num;
            }
        }
        return $numbers;
    } 
}