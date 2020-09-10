<?php


namespace app\classes;


class Shop
{
    private function __construct()
    {

    }

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

    public static function GetComplectsByQuality($Quality)
    {
        if(is_numeric($Quality))
        {

            $Return = [];

            $ComplectData = DataBase::query('SELECT * FROM `complects` WHERE `quality` = ?', [$Quality]);

            if($ComplectData->num_rows > 0)
            {
                while ($Row = $ComplectData->fetch_assoc())
                {
                    $Return[] = '
                <div class="mt8 ml10 shop_lgt">
			        <div class="fl ml5 mr10 sz0">
					    <a class="nd" href="/items?set_id='.$Row['id'].'">
					        '.self::Maneken(self::GetAllItemsFromComplect($Row['id'])).'
				        </a>
			        </div>
			        
			        <div>
				        <a class="bold tdn" href="/items?set_id='.$Row['id'].'">'.$Row['name'].'</a>
				    </div>
				    
		            <div class="clb"></div>
		        </div>
		        
		        <div class="hr_arr mt-5 mlr10"><div class="alf"><div class="art"><div class="acn"></div></div></div></div>
		        ';
                }
            }

            return $Return;
        }

        return false;
    }

    private static function GetAllItemsFromComplect($ComplectID, $ForShop = null)
    {
        if(is_numeric($ComplectID))
        {
            if($ForShop === null)
            {
                $ComplectData = DataBase::query('SELECT `items` FROM `complects` WHERE `id` = ? LIMIT 1', [$ComplectID])->fetch_assoc();
            }
            else
            {
                $ComplectData = DataBase::query('SELECT `items` FROM `complects` WHERE `id` = ? AND `showInSHop` = 1 LIMIT 1', [$ComplectID])->fetch_assoc();
            }

            return explode('|', $ComplectData['items']);

        }

        return false;
    }

    public static function GetAllItemsFromShop($ComplectID)
    {

        if(is_numeric($ComplectID))
        {

            $ItemsForReturn = [];

            $ItemsFromComplect = self::GetAllItemsFromComplect($ComplectID, true);

            if($ItemsFromComplect !== false)
            {

                foreach ($ItemsFromComplect as $key => $value)
                {

                    $Result = DataBase::query('SELECT * FROM `shop_items` WHERE `id_item` = ? LIMIT 1', [$value])->fetch_assoc();

                    $ItemsForReturn[] = $Result['id'];

                }

                return $ItemsForReturn;

            }
        }

        return false;

    }

    public static function ShowQuality($Quality)
    {
        $Result = '';

        switch ($Quality)
        {
            case 1:
                $Result = 'Обычный';
                break;
            case 2:
                $Result = '';
                break;
            case 3:
                $Result = '1';
                break;
            case 4:
                $Result = '2';
                break;

        }

        return $Result;
    }

    public static function TextTypeItem($ItemType)
    {
        $Return = '';

        $Arr = [
            0 => 'head',
            1 => 'shoulders',
            2 => 'chest',
            3 => 'gloves',
            4 => 'offhand',
            5 => 'mainhand',
            6 => 'legs',
            7 => 'boots'
        ];

        foreach ($Arr as $key => $value)
        {
            if($ItemType === $key)
            {
                $Return = $value;
            }
        }

        return $Return;
    }

    public static function CostAllInComplect($ComplectID)
    {
        $Return = 0;

        $ItemsFromComplect = self::GetAllItemsFromComplect($ComplectID);

        foreach ($ItemsFromComplect as $key => $value)
        {
            $ShopData = DataBase::query('SELECT * FROM `shop_items` WHERE `id_item` = ?', [$value])->fetch_assoc();

            $Return += $ShopData['cost'];
        }

        return $Return;
    }

    public static function ShowItemData($Item)
    {
        if(is_numeric($Item))
        {
            $ShopData = DataBase::query('SELECT * FROM `shop_items` WHERE `id_item` = ?', [$Item]);

            if($ShopData -> num_rows === 1)
            {
                $ItemData = DataBase::query('SELECT * FROM `items` WHERE `id` = ?', [$Item])->fetch_assoc();
                $ShopData = $ShopData->fetch_assoc();

                return '
                    <div class="mt8 ml10 shop_lgt">
			<div class="fl ml5 mr10 sz0">
				<a href="/item/viewItem='.$ItemData['id'].'"><img class="item_icon" src="http://144.76.127.94/view/image/item/'.$ItemData['complect'].'_'.self::TextTypeItem($ItemData['type']).'.png"></a>
			</div>
			<div class="ml58 mt5 mb5 sh small">
				<a href="/item/viewItem='.$ItemData['id'].'">'.$ItemData['name'].'</a>
			</div>
			<div class="ml58 mt5 mb5 sh small">
				<img class="icon" src="http://144.76.127.94/view/image/quality_cloth/'.$ItemData['quality'].'.png"> <span class="q'.$ItemData['quality'].'">'.self::ShowQuality($ItemData['quality']).' [1/5]</span>
							</div>
			<div class="ml58 mt5 sh small">
					<a class="buy_link" href="?buy='.$ItemData['id'].'">Купить</a><span class="buy_link"> за <img src="http://144.76.127.94/view/image/icons/gold.png" alt="" class="icon">'.$ShopData['cost'].'</span>			</div>
						<div class="clb"></div>
		</div>
                ';
            }
        }

        return false;
    }

    public static function Maneken($Items = [])
    {
        return '<img width="120" height="160" src="/maneken/'.User::userData()['gender'].'/'.$Items[0].'/'.$Items[1].'/'.$Items[2].'/'.$Items[3].'/'.$Items[4].'/'.$Items[5].'/'.$Items[6].'/'.$Items[7].'" alt="Маникен">';
    }
}

