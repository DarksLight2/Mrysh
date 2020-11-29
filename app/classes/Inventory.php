<?php

namespace app\classes;

class Inventory
{

    private function __construct()
    {

    }

    public static function GetAmountItems($UserID = null, $Equip = null): int
    {
        if($UserID === null)
        {
            $UserID = User::userData()['id'];
        }

        if($Equip === null)
        {
            return DataBase::query('SELECT * FROM `inventory` WHERE `userID` = ?', [$UserID])->num_rows;
        }
        else
        {
            return DataBase::query('SELECT * FROM `inventory` WHERE `userID` = ? AND `equip` = ?', [$UserID, $Equip])->num_rows;
        }
    }

    public static function GetItems($UserID = null, $Equip = null)
    {
        $ActiveRecords = new ActiveRecords('inventory');

        if($UserID === null)
            $UserID = User::userData()['id'];

        if($Equip === null)
            $inventory = $ActiveRecords->select('`id`')->where(['userID' => $UserID])->execute();
        else
            $inventory = $ActiveRecords->select('`id`')->where(['userID' => $UserID, 'equip' => $Equip])->execute();

        if($inventory->count_rows > 0)
            return $inventory;

        return null;

    }

    public static function search_similar_equip_item($type_item, $user_id = null)
    {
        $ActiveRecords = new ActiveRecords('inventory');

        if($user_id === null)
            $user_id = User::userData()['id'];

        $item = $ActiveRecords->select()->where([
            'type'   => $type_item,
            'equip'  => 1,
            'userID' => $user_id
        ])->execute();

        if($item->count_rows === 1)
            return (object)[
                'result'  => true,
                'id_item' => $item->data[0]->item,
                'id_inv'  => $item->data[0]->id
            ];
        else
            return (object)['result'  => false];

    }

    public static function Comparison($NewItem)
    {
        $new_item_data   = Items::get_item_data($NewItem, true);
        $equip_item_id   = self::search_similar_equip_item($new_item_data->type_number);
        $sum_params_new  = $new_item_data->inv_strength + $new_item_data->inv_health + $new_item_data->inv_defence;

        if($equip_item_id->result === true) {
            $equip_item_data = Items::get_item_data($equip_item_id->id_inv, true);
            $sum_params_equip = $equip_item_data->inv_strength + $equip_item_data->inv_health + $equip_item_data->inv_defence;
        } else {
            $sum_params_equip = 0;
        }

        if($sum_params_new > $sum_params_equip) {
            return (object)[
                'text'   => '<span class="win">+'.($sum_params_new - $sum_params_equip).'</span>',
                'result' => 'best'
            ];
        } elseif($sum_params_new < $sum_params_equip) {
            return (object)[
                'text'   => '<span class="lose">'.($sum_params_new - $sum_params_equip).'</span>',
                'result' => 'no_best'
            ];
        } else {
            return (object)[
                'text'   => '',
                'result' => 'no_best'
            ];
        }
    }

    public static function AddItem($ItemID, $DataItem = [], $UserID = null)
    {
        if($UserID === null)
        {
            $UserID = User::userData()['id'];
        }

        if(is_numeric($ItemID))
        {
            if(self::GetAmountItems($UserID, 0) < 20)
            {
                DataBase::query('INSERT INTO `inventory` (`item`, `type`, `strength`, `health`, `defence`, `userID`, `level_items`) VALUES (?, ?, ?, ?, ?, ?, ?)', [$ItemID, $DataItem['type'], $DataItem['strength'], $DataItem['health'], $DataItem['defence'], $UserID, $DataItem['level_items']]);

                return true;

            }

            return false;

        }

        return false;

    }

    public static function DestroyItem($ItemID ,$UserID = null)
    {
        if(is_numeric($ItemID)) {

            if($UserID === null)
                $UserID = User::userData()['id'];

            $item_data = Items::get_item_data($ItemID, true, $UserID);
            $user_data = User::getUserDataByID($UserID);

            if($item_data->equip === 1) {

                $strength = $user_data['strength'] - $item_data->inv_strength;
                $health   = $user_data['health']   - $item_data->inv_health;
                $defence  = $user_data['defence']  - $item_data->inv_defence;

                User::change_data([
                    'strength' => $strength,
                    'health'   => $health,
                    'defence'  => $defence
                ]);
            }

            $ActiveRecords = new ActiveRecords('inventory');
            $ActiveRecords->delete()->where(['id' => $ItemID, 'UserID' => $UserID])->execute();
        }

        return null;
    }

    public static function change_data($item_id, $arr = [], $user_id = null)
    {
        if($user_id === null)
            $user_id = User::userData()['id'];

        $ActiveRecords = new ActiveRecords('inventory');
        $ActiveRecords->update($arr)->where(['userID' => $user_id, 'id' => $item_id])->execute();

        return true;
    }

    public static function UnEquip($ItemID)
    {
        $item_data = Items::get_item_data($ItemID, true);

        if($item_data->equip === 1) {

            $params = $item_data->inv_strength + $item_data->inv_health + $item_data->inv_defence;

            self::change_data($ItemID, ['equip' => 0]);
            User::change_data([
                'strength' => User::userData()['strength'] - $params,
                'health'   => User::userData()['health']   - $params,
                'defence'  => User::userData()['defence']  - $params,
            ]);

            return true;
        }

        return null;
    }

    public static function Equip($id)
    {
        if(is_numeric($id)) {
            $ItemDataToEquip  = Items::get_item_data($id, true);
            $SelectEquipItems = self::search_similar_equip_item($ItemDataToEquip->type_number);

            if($SelectEquipItems->result === true) {
                self::UnEquip($SelectEquipItems->id_inv);
            }

            $params = $ItemDataToEquip->inv_strength + $ItemDataToEquip->inv_health + $ItemDataToEquip->inv_defence;

            self::change_data($id, ['equip' => 1]);
            User::change_data([
                'strength' => User::userData()['strength'] + $params,
                'health'   => User::userData()['health']   + $params,
                'defence'  => User::userData()['defence']  + $params,
            ]);

            return true;
        }

        return null;
    }

    public static function join_items($items_to_disassemble = [])
    {
        $return = [];

        foreach ($items_to_disassemble as $item_id_to_disassemble) {

            if(gettype($item_id_to_disassemble) === 'object')
                $item_id_to_disassemble = $item_id_to_disassemble->id;

            $item_data  = Items::get_item_data($item_id_to_disassemble, true);
            $equip_item = Inventory::search_similar_equip_item($item_data->type_number);

            if($equip_item->result === true) {
                $equip_data = Items::get_item_data($equip_item->id_inv, true);

                if($equip_data->current_level < $equip_data->max_level && $equip_data->sum_inv_params >= $item_data->sum_inv_params) {

                    $rune       = $equip_data->rune;
                    $sharpening = $equip_data->sharpening;
                    $exp        = $equip_data->exp + Items::get_exp_to_disassemble($item_data->current_level) + $item_data->exp;
                    $strength   = $equip_data->inv_strength;
                    $health     = $equip_data->inv_health;
                    $defence    = $equip_data->inv_defence;
                    $level_item = $equip_data->current_level;

                    if($exp >= Items::get_exp_to_disassemble($equip_data->current_level)) {
                        $exp = $exp - Items::get_exp_to_disassemble($equip_data->current_level);
                        $strength   += 2;
                        $health     += 2;
                        $defence    += 2;
                        $level_item += 1;

                        User::change_data([
                            'strength' => User::userData()['strength'] + 2,
                            'health'   => User::userData()['health']   + 2,
                            'defence'  => User::userData()['defence']  + 2,
                        ]);
                    }

                    if($rune < $item_data->rune)
                        $rune = $item_data->rune;

                    if($sharpening < $item_data->sharpening)
                        $sharpening = $item_data->sharpening;

                    Inventory::change_data($equip_data->inv_id, [
                        'rune'       => $rune,
                        'sharpening' => $sharpening,
                        'exp'        => $exp,
                        'level_items'=> $level_item.'|'.$equip_data->max_level,
                        'strength'   => $strength,
                        'health'     => $health,
                        'defence'    => $defence
                    ]);

                    Inventory::DestroyItem($item_id_to_disassemble);

                    if(count($items_to_disassemble) === 1) {
                        $return['id']          = $equip_data->inv_id;
                        $return['name']        = $equip_data->name;
                        $return['img']         = $equip_data->img;
                        $return['quality']     = '<img class="icon" src="/view/image/quality_cloth/' . $equip_data->quality_number . '.png"> <span class="q' . $equip_data->quality_number . '">' . $equip_data->quality_name . ' [' . $level_item . '/' . $equip_data->max_level . ']</span>';
                        $return['exp']         = $equip_data->exp + Items::get_exp_to_disassemble($item_data->current_level) + $item_data->exp;
                        $return['level']       = $level_item;
                        $return['current_exp'] = $exp;
                    } else {
                        if($level_item > $equip_data->current_level) {
                            $return['data']['new_level'][$equip_data->inv_id]['name']   = $equip_data->name;
                            $return['data']['new_level'][$equip_data->inv_id]['level']  = $level_item;
                        } else {
                            $return['data']['upgrade'][$equip_data->inv_id]['name']      = $equip_data->name;
                            $return['data']['upgrade'][$equip_data->inv_id]['level']     = $level_item;
                            $return['data']['upgrade'][$equip_data->inv_id]['max_level'] = $equip_data->max_level;
                        }
                    }
                }
            }
        }

        $_SESSION['disassemble_items_data'] = $return;

        return null;
    }
}