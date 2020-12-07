<?php

namespace app\classes;

class Runes
{
    public static function get($rune = null)
    {

        $return        = new \stdClass();
        $ActiveRecords = new ActiveRecords('runes');

        if($rune === null) {
            $data = $ActiveRecords->select()->execute();
        } else {
            $data = $ActiveRecords->select()->where(['id' => $rune])->execute();
        }

        $return->count_runes = $data->count_rows;

        foreach ($data->data as $rune) {
            $return->data[] = $rune;
        }

        return $return;
    }

    private static function chanse()
    {
        $rand = rand(0, 100);

        if($rand <= 10) {
            return true;
        }

        return false;
    }

    public static function update($item_id, $new_rune, $user_id = null, $shop = false)
    {
        if($user_id === null) {
            $user_data = User::userData();
        } else {
            $user_data = User::getUserDataByID($user_id);
        }

        $item_data = Items::get_item_data($item_id, true);
        $rune_data = self::get($new_rune);

        if($rune_data->count_runes === 1 && $item_data->equip === 1) {
            if($item_data->rune >= $new_rune || $user_data['gold'] < $rune_data->data[0]->price) {
                return false;
            }

            if($item_data->rune !== 0)
                $old_params = ceil(self::get($item_data->rune)->data[0]->params / 3);
            else
                $old_params = 0;

            $params     = ceil($rune_data->data[0]->params / 3);

            if($shop === true) {
                if(self::chanse() === true) {
                    $new_rune += 1;
                    $rune_data_new = self::get($new_rune);
                    $params = ceil($rune_data_new->data[0]->params / 3);
                }
            }

            User::change_data([
                'gold'     => $user_data['gold']     - $rune_data->data[0]->price,
                'strength' => $user_data['strength'] + $params - $old_params,
                'health'   => $user_data['health']   + $params - $old_params,
                'defence'  => $user_data['defence']  + $params - $old_params
            ], $user_id);

            Inventory::change_data($item_id, [
                'strength' => $item_data->inv_strength + $params - $old_params,
                'health'   => $item_data->inv_health   + $params - $old_params,
                'defence'  => $item_data->inv_defence  + $params - $old_params,
                'rune'     => $new_rune
            ], $user_id);

            return true;

        }

        return null;
    }
}