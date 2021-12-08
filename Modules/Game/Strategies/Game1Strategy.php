<?php 

namespace Modules\Game\Strategies;

class Game1Strategy extends GameStrategy
{
    const CASES = [
        0 => [0, 1, 2, 3, 4],
        1 => [-1, 0, 1, 2, 3],
        2 => [-2, -1, 0, 1, 2],
        3 => [-3, -2, -1, 0, 1],
        4 => [-4, -3, -2, -1, 0]
    ];
    const CURRENT_THEME_NUMBER = [];
}