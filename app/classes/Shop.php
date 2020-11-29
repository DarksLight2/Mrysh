<?php


namespace app\classes;


class Shop
{

    public static function ShowMainCategories($Name, $Description, $Image, $Url, $Access = [])
    {

        $Return = '
            <div class="bdr bg_main mb2"><div class="wr1"><div class="wr2"><div class="wr3"><div class="wr4"><div class="wr5"><div class="wr6"><div class="wr7"><div class="wr8">
            
                <div class="mt8 ml5 shop_lgt">
                    <div class="fl ml5 sz0">
                        <a href="' . $Url . '"><img alt="" height="48" width="48" src="/view/image/shop/' . $Image . '.png"></a>
                    </div>
                    
                    <div class="ml68">
                        <a class="bold tdn" href="' . $Url . '"><span class="button_cont_cat">' . $Name . '</span></a>
                    </div>
                    
                    <div class="ml68 mt5 small">' . $Description . '</div>
                    
                    <div class="clb"></div>
                
                </div>
    
            </div></div></div></div></div></div></div></div></div>
            ';

        if(is_numeric($Access['value']))
        {
            if(User::userData()[$Access['type']] >= $Access['value']) {

                return $Return;

            }
        }
        else
        {
            if(User::userData()[$Access['type']] == $Access['value']) {

                return $Return;

            }
        }

        return false;
    }



    public static function maneken($Items = [])
    {
        return '<img width="120" height="160" src="/maneken/'.User::userData()['gender'].'/'.$Items[0].'/'.$Items[1].'/'.$Items[2].'/'.$Items[3].'/'.$Items[4].'/'.$Items[5].'/'.$Items[6].'/'.$Items[7].'" alt="Маникен">';
    }

    public static function BuyItem($ItemID)
    {
        if(is_numeric($ItemID) && $ItemID > 0) {

            $price = Shop::get_data($ItemID)->price;

            if(User::userData()['gold'] >= $price) {

                $item_data = Items::get_item_data($ItemID);

                User::change_data(['gold' => User::userData()['gold'] - $price]);

                if (Inventory::AddItem($ItemID, [
                        'strength'    => $item_data->item_strength,
                        'health'      => $item_data->item_health,
                        'defence'     => $item_data->item_defence,
                        'level_items' => $item_data->current_level . '|' . $item_data->max_level,
                        'type'        => $item_data->type_number
                    ]) === true) {

                    return true;

                }
            }
        }

        return null;
    }

    public static function get_data($item_id)
    {
        if(is_numeric($item_id) && $item_id > 0) {

            $return        = new \stdClass();
            $ActiveRecords = new ActiveRecords('shop_items');
            $item_data     = $ActiveRecords->select()->where(['id_item' => $item_id])->execute();

            if($item_data->count_rows === 1) {

                $return->id    = $item_data->data[0]->id;
                $return->price = $item_data->data[0]->cost;

                return $return;
            }
        }

        return null;
    }
}

