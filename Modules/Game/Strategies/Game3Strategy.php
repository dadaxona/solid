<?php 

namespace Modules\Game\Strategies;

class Game3Strategy extends GameStrategy
{
    const CASES = [
        0 => [0, 1, 2, 3, 4, 5, 6],
        1 => [-1, 0, 1, 2, 3, 5, 6 ],
        2 => [-2, -1, 0, 1, 2, 5, 6],
        3 => [-3, -2, -1, 0, 1, 5, 6],
        4 => [-4, -3, -2, -1, 0, 5],
        5 => [-5, 0, 1, 2, 3, 4],
        6 => [-6, -5, -1, 0, 1, 2, 3],
        7 => [-6, -5, -2, -1, 0, 1, 2],
        8 => [-6, -5, -3, -2, -1, 0, 1],
        9 => [-6, -5, -4, -3, -2, -1, 0]
    ];
    const CURRENT_THEME_NUMBER = [-6, 6];
}