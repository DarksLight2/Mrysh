<?php

namespace app\classes;

class Items
{
    private function __construct() {}

    private static $ItemsData = null;

    public static function GetItemsData($Items = [])
    {
        if( ! empty($Items))
        {
            if(self::$ItemsData === null)
            {
                foreach ($Items as $Key => $Value)
                {
                    self::$ItemsData[] = DataBase::query('SELECT * FROM `items` WHERE `id` = ? LIMIT 1', [$Value['item']])->fetch_assoc();
                }
            }

            return self::$ItemsData;

        }

        return false;
    }

    public static function get_item_data($item, $inventory = false, $user_id = null)
    {
            if($user_id === null)
                $user_id = User::userData()['id'];

            $ActiveRecordsItems     = new ActiveRecords('items');
            $ActiveRecordsInventory = new ActiveRecords('inventory');
            $ActiveRecordsShop      = new ActiveRecords('shop_items');
            $ActiveRecordsComplects = new ActiveRecords('complects');

            $return = new \stdClass();

            if($inventory === false) {
                $item_data      = $ActiveRecordsItems->select()    ->where(['id'      => $item])->execute()->data[0];
                $shop_data      = $ActiveRecordsShop->select()     ->where(['id_item' => $item])->execute();
                $complect_data  = $ActiveRecordsComplects->select()->where(['id'      => $item_data->complect])->execute()->data[0];
                $inventory_data = $ActiveRecordsInventory->select()->where(['item'    => $item,
                    'userID'  => $user_id])->execute();
            } else {
                $inventory_data = $ActiveRecordsInventory->select()->where(['id'      => $item,
                    'userID'  => $user_id])->execute();
                $item_data      = $ActiveRecordsItems->select()    ->where(['id'      => $inventory_data->data[0]->item])->execute()->data[0];
                $shop_data      = $ActiveRecordsShop->select()     ->where(['id_item' => $inventory_data->data[0]->item])->execute();
                $complect_data  = $ActiveRecordsComplects->select()->where(['id'      => $item_data->complect])->execute()->data[0];
            }

            $item_params     = explode('|', $complect_data->params);
            $sum_item_params = (int)$item_params[0] + $item_params[1] + $item_params[2];
            $item_strength   = $item_params[0];
            $item_health     = $item_params[1];
            $item_defence    = $item_params[2];
            $sum_inv_params  = 0;
            $inv_strength    = 0;
            $inv_health      = 0;
            $inv_defence     = 0;
            $price           = 0;
            $sharpening      = 0;
            $rune            = 0;
            $inv_id          = 0;
            $type_item       = '';
            $quality_name    = '';
            $equip           = null;
            $exp             = null;

            switch ($item_data->quality) {
                case 1:
                    $quality_name = 'Обычный';
                    break;
                case 2:
                    $quality_name = 'Необычный';
                    break;
                case 3:
                    $quality_name = 'Редкий';
                    break;
                case 4:
                    $quality_name = 'Эпический';
                    break;
                case 5:
                    $quality_name = 'Легендарный';
                    break;
                case 6:
                    $quality_name = 'Мифический';
                    break;
            }

            switch ($item_data->type) {
                case 0:
                    $type_item = 'head';
                    break;
                case 1:
                    $type_item = 'shoulders';
                    break;
                case 2:
                    $type_item = 'chest';
                    break;
                case 3:
                    $type_item = 'gloves';
                    break;
                case 4:
                    $type_item = 'offhand';
                    break;
                case 5:
                    $type_item = 'mainhand';
                    break;
                case 6:
                    $type_item = 'legs';
                    break;
                case 7:
                    $type_item = 'boots';
                    break;
            }

            if($inventory_data->count_rows === 1) {
                $levels_item = explode('|', $inventory_data->data[0]->level_items);

                $inv_strength  = $inventory_data->data[0]->strength;
                $inv_health    = $inventory_data->data[0]->health;
                $inv_defence   = $inventory_data->data[0]->defence;
                $equip         = $inventory_data->data[0]->equip;
                $exp           = $inventory_data->data[0]->exp;
                $sharpening    = $inventory_data->data[0]->sharpening;
                $rune          = $inventory_data->data[0]->rune;
                $inv_id        = $inventory_data->data[0]->id;
                $sum_inv_params= $inventory_data->data[0]->strength + $inventory_data->data[0]->health + $inventory_data->data[0]->defence;

            } else {
                $complect = Complects::get_data($complect_data->id);
                $levels_item = [
                    0 => $complect->start_level,
                    1 => $complect->max_level
                ];
            }

            if($shop_data->count_rows === 1)
                $price = $shop_data->data[0]->cost;

            $return->item_id         = $item_data->id;
            $return->inv_id          = $inv_id;
            $return->name            = $item_data->name;
            $return->type_name       = $type_item;
            $return->type_number     = $item_data->type;
            $return->img             = '<img class="item_icon" height="48" src="/view/image/item/'.$complect_data->id.'_'.$type_item.'.png">';
            $return->quality_name    = $quality_name;
            $return->quality_number  = $item_data->quality;
            $return->complect_name   = $complect_data->name;
            $return->complect_number = $complect_data->id;
            $return->price           = $price;
            $return->item_strength   = $item_strength;
            $return->item_health     = $item_health;
            $return->item_defence    = $item_defence;
            $return->inv_strength    = $inv_strength;
            $return->inv_health      = $inv_health;
            $return->inv_defence     = $inv_defence;
            $return->equip           = $equip;
            $return->exp             = $exp;
            $return->rune            = $rune;
            $return->sharpening      = $sharpening;
            $return->current_level   = $levels_item[0];
            $return->max_level       = $levels_item[1];
            $return->sum_inv_params  = $sum_inv_params;
            $return->sum_item_params = $sum_item_params;

            return $return;
        }

        public static function get_exp_to_disassemble($level)
        {
            $list = [
                1 => 10,        2 => 20,       3 => 30,       4 => 40,       5 => 80,       6 => 100,     7 => 200,       8 => 220,      9 => 240,     10 => 280,
                11 => 300,     12 => 360,     13 => 400,     14 => 480,     15 => 540,     16 => 580,     17 => 620,     18 => 660,     19 => 700,     20 => 750,
                21 => 800,     22 => 1100,    23 => 1400,    24 => 1700,    25 => 2000,    26 => 2500,    27 => 3000,    28 => 3500,    29 => 4000,    30 => 4500,
                31 => 5000,    32 => 5500,    33 => 6000,    34 => 6500,    35 => 6790,    36 => 9000,    37 => 15000,   38 => 20000,   39 => 25000,   40 => 40000,
                41 => 40000,   42 => 40000,   43 => 40000,   44 => 40000,   45 => 50000,   46 => 54600,   47 => 54600,   48 => 54600,   49 => 54600,   50 => 54600,
                51 => 80000,   52 => 80000,   53 => 80000,   54 => 80000,   55 => 80000,   56 => 160000,  57 => 160000,  58 => 160000,  59 => 160000,  60 => 160000,
                61 => 240000,  62 => 280000,  63 => 320000,  64 => 390000,  65 => 400000,  66 => 400000,  67 => 500000,  68 => 500000,  69 => 600000,  70 => 600000,
                71 => 600000,  72 => 700000,  73 => 700000,  74 => 800000,  75 => 1000000, 76 => 1200000, 77 => 2000000, 78 => 2000000, 79 => 2000000, 80 => 2000000,
                81 => 2000000, 82 => 2000000, 83 => 2000000, 84 => 2500000, 85 => 3000000
            ];

            foreach ($list as $level_in_list => $exp) {
                if($level == $level_in_list)
                    return $exp;
            }

            return null;
        }
}