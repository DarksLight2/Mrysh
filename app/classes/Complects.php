<?php

namespace app\classes;

class Complects
{

    public static function get_complects_by_quality($quality)
    {
        if(is_numeric($quality) && $quality > 0) {
            $ActiveRecords = new ActiveRecords('complects');
            $complect_data = [];

            $complects_id = $ActiveRecords->select('`id`')->where(['quality' => $quality])->execute();

            if($complects_id->count_rows > 0) {
                foreach ($complects_id->data as $complect) {
                    $complect_data[] = self::get_data($complect->id);
                }

                return $complect_data;
            }
        }

        return null;
    }

    public static function get_data($complect_id)
    {
        if(is_numeric($complect_id) && $complect_id > 0) {
            $ActiveRecords = new ActiveRecords('complects');

            $complect = $ActiveRecords->select()->where(['id' => $complect_id])->execute();

            $return = new \stdClass();

            if($complect->count_rows > 0) {

                $items        = explode('|', $complect->data[0]->items);
                $levels_items = explode('|', $complect->data[0]->level_items);
                $params       = explode('|', $complect->data[0]->params);
                $price        = 0;
                $offhand      = 0;
                $mainhand     = 0;
                $boots        = 0;
                $pants        = 0;
                $gloves       = 0;
                $armor        = 0;
                $shoulders    = 0;
                $helmet       = 0;
                $number_item  = 0;

                foreach ($items as $item) {
                    $price += Shop::get_data($item)->price;
                }

                if($complect->data[0]->showInShop === 0)
                    $return->in_shop = 'do_not_show';
                else
                    $return->in_shop = 'show';

                foreach ($items as $item) {

                    switch ($number_item) {
                        case 0:
                            $helmet    = $item;
                            break;
                        case 1:
                            $shoulders = $item;
                            break;
                        case 2:
                            $armor     = $item;
                            break;
                        case 3:
                            $gloves    = $item;
                            break;
                        case 4:
                            $pants     = $item;
                            break;
                        case 5:
                            $boots     = $item;
                            break;
                        case 6:
                            $mainhand  = $item;
                            break;
                        case 7:
                            $offhand   = $item;
                            break;
                    }

                    $number_item++;
                }

                $return->id               = $complect->data[0]->id;
                $return->name             = $complect->data[0]->name;
                $return->quality          = $complect->data[0]->quality;
                $return->item_helmet      = $helmet;
                $return->item_shoulders   = $shoulders;
                $return->item_armor       = $armor;
                $return->item_gloves      = $gloves;
                $return->item_pants       = $pants;
                $return->item_boots       = $boots;
                $return->item_offhand     = $offhand;
                $return->item_mainhand    = $mainhand;
                $return->start_level      = $levels_items[0];
                $return->max_level        = $levels_items[1];
                $return->strength         = $params[0];
                $return->health           = $params[1];
                $return->defence          = $params[2];
                $return->arr_items        = $items;
                $return->price            = $price;

                return $return;
            }
        }

        return null;
    }
}