<?php

namespace app\classes;

class ActiveRecords
{
    private $tableName;
    private $query;
    private $values;
    private $where = 0;
    private $archive_query;
    private $archive_values;

    public function __construct($tableName)
    {
        $this->tableName = $tableName;
    }

    public function select($fields = '*')
    {
        $this->query = 'SELECT '.$fields.' FROM `'.$this->tableName.'` ';

        return $this;
    }

    public function insert($fields = [])
    {
        $count_types = 0;
        $value_query = '';

        $this->query = 'INSERT INTO `'.$this->tableName.'` (';

        foreach ($fields as $field => $value) {
            $this->values[] = $value;
            $this->query .= '`'.$field.'`, ';
            $count_types++;
        }

        $this->query = substr($this->query,0,-2);

        for ($i = 0; $i < $count_types; $i++) {
            $value_query .= '? ,';
        }

        $value_query = substr($value_query,0,-2);

        $this->query .= ') VALUES ('.$value_query.')';

        DataBase::query($this->query, $this->values);

        return null;
    }

    public function update($fields = [])
    {
        if( ! empty($fields)) {

            $this->query = 'UPDATE `'.$this->tableName.'` SET ';

            foreach ($fields as $field => $value) {
                $this->values[] = $value;
                $this->query .= '`'.$field.'` = ? ,';
            }

            $this->query = substr($this->query,0,-1);
        }

        return $this;
    }

    public function limit($count = null)
    {
        if( ! empty($this->query)) {

            if($count === null) {
                $count = 1;
            }

            $this->query .= 'ORDER BY `id` DESC LIMIT '.$count;
        }

        return $this;
    }

    public function where($condition = [])
    {
        foreach ($condition as $key => $value) {

            if($this->where === 0)
                $this->query .= 'WHERE `' . $key . '` = ? ';
            else
                $this->query .= 'AND `' . $key . '` = ? ';

            $this->values[] = $value;
            $this->where = 1;
        }

        return $this;
    }

    public function orWhere($condition = [])
    {
        if($this->where === 0) {
            return 'ERROR';
        }

        foreach ($condition as $key => $value) {
            $this->query .= 'OR `' . $key . '` = ? ';
            $this->values[] = $value;
        }

        return $this;
    }

    public function orderBy($param)
    {
        if( ! empty($this->query)) {
            $this->query .= 'ORDER BY '.$param;
        }

        return $this;
    }

    public function delete()
    {
        $this->query = 'DELETE FROM `'.$this->tableName.'`';

        return $this;
    }

    public function execute($params = null)
    {
        if( ! empty($this->query)) {

            $return = new \stdClass();

            $query = DataBase::query($this->query, $this->values);

            if( ! $query) {
                $this->clear_data();
                return null;
            }

            if($params !== null) {
                if($params === false) {
                    return true;
                }

                $this->clear_data();

                return $query->$params;

            } else {
                while($row = $query->fetch_assoc()) {
                    $return->data[] = (object)$row;
                }

                if(empty($return->data)) {
                    $return->data[0] = null;
                }

                $return->count_rows = $query->num_rows;

                $this->clear_data();

                return $return;
            }

        } else {
            echo '<b>Fatal Error:</b> Check query in DataBase!!! [QUERY: '.$this->query.']';
            return null;
        }
    }

    public function get_query(): void
    {
        echo '<br>QUERY: '.$this->archive_query.'<br>';

        if( ! empty($this->archive_values)) {

            echo 'VALUES: [';

            foreach ($this->archive_values as $value) {
                echo '"'.$value.'"';
            }

            echo ']<br>';

        }
    }

    public function clear_data()
    {
        $this->archive_query = $this->query;
        $this->archive_values = $this->values;

        unset($this->query);
        unset($this->values);
        $this->where = 0;
    }
}