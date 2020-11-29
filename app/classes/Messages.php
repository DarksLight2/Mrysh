<?php

namespace app\classes;

class Messages
{
    private $Query = [];
    private $ValueRequest = [];

    public function all()
    {
        $query = $this->Query;

        unset($this->Query);

        $return = [];

        $request = DataBase::query($query, $this->ValueRequest);

        if($request->num_rows > 0) {
            while ($row = $request->fetch_assoc())
            {
                $return[] = (object)$row;
            }
        }

        unset($this->ValueRequest);

        return $return;
    }

    public function last()
    {
        $query = $this->Query.' ORDER BY `id` DESC LIMIT 1';

        unset($this->Query);

        $request = DataBase::query($query, $this->ValueRequest);

        if($request->num_rows === 1) {
            return (object)$request->fetch_assoc();
        }

        unset($this->ValueRequest);

        return null;
    }

    public function first()
    {
        $query = $this->Query.' ORDER BY `id` ASC LIMIT 1';

        unset($this->Query);

        $request = DataBase::query($query, $this->ValueRequest);

        unset($this->ValueRequest);

        if($request->num_rows === 1) {
            return (object)$request->fetch_assoc();
        }

        return null;
    }

    public function select($table, $dialogID = null, $fields = '*', $userID = null)
    {
        if($userID === null) {
            $userID = User::userData()['id'];
        }

        $this->ValueRequest = [$userID, $userID];

        if($table == 'messages' && is_numeric($dialogID)) {
            $this->Query = 'SELECT '.$fields.' FROM `'.$table.'` WHERE `sender` = ? OR `recipient` = ? AND `dialogID` = ?';
            $this->ValueRequest[] = $dialogID;
        } else {
            $this->Query = 'SELECT '.$fields.' FROM `'.$table.'` WHERE `userID` = ? OR `companionID` = ?';
        }

        return $this;
    }
}