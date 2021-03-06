<?php 

namespace Modules\Game\Strategies;

class Game4Strategy extends GameStrategy
{
    const CASES = [
        0 => [0, 1, 2, 3, 4, 5, 6, 7],
        1 => [-1, 0, 1, 2, 3, 5, 6, 7],
        2 => [-2, -1, 0, 1, 2, 5, 6, 7],
        3 => [-3, -2, -1, 0, 1, 5, 6],
        4 => [-4, -3, -2, -1, 0, 5],
        5 => [-5, 0, 1, 2, 3, 4],
        6 => [-6, -5, -1, 0, 1, 2, 3],
        7 => [-7, -6, -5, -2, -1, 0, 1, 2],
        8 => [-7, -6, -5, -3, -2, -1, 0, 1],
        9 => [-7, -5, -4, -3, -2, -1, 0]
    ];
    const CURRENT_THEME_NUMBER = [-7, 7];

}