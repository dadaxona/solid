<?php
namespace Modules\Telegram\Strategies;

class StrategyContract {

    protected $actions;
    protected $telegram;
    protected $telegramBot;
    
    public function setActions($actions)
    {
        $this->actions = $this->getActionsList($actions);
    }
    public static function getActionsList($actions)
    {
        $list = [];
        foreach($actions as $action){
            $list[$action::$ROUTE_NAME] = $action;
        }
        return $list;
    }
}