<?php 

namespace Modules\Game\Strategies;

class Game2Strategy extends GameStrategy
{
    const CASES = [
        0 => [0, 1, 2, 3, 4, 5],
        1 => [-1, 0, 1, 2, 3, 5],
        2 => [-2, -1, 0, 1, 2, 5],
        3 => [-3, -2, -1, 0, 1, 5],
        4 => [-4, -3, -2, -1, 0, 5],
        5 => [-5, 0, 1, 2, 3, 4],
        6 => [-5, -1, 0, 1, 2, 3],
        7 => [-5, -2, -1, 0, 1, 2],
        8 => [-5, -3, -2, -1, 0, 1],
        9 => [-5, -4, -3, -2, -1, 0]
    ];
    const CURRENT_THEME_NUMBER = [-5, 5];
}