<?php

namespace App\Services\Database;

use App\Services\Helpers\Helpers;

class DBW
{
    private string $DB_HOST = 'localhost';
    private string $DB_PORT = '3306';
    private string $DB_NAME = "solesprint";
    private string $DB_USERNAME = "root";
    private string $DB_PASSWORD = "";
    private $connection;
    private $query;

    public function __construct()
    {
        try {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $this->connection = mysqli_connect(
                $this->DB_HOST,
                $this->DB_USERNAME,
                $this->DB_PASSWORD,
                $this->DB_NAME
            );
        } catch (\Exception $e) {
            die("ошибка соеднинения с бд: " . $e);
        }
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setQuery($new_query)
    {
        $this->query = $new_query;
    }

    public function get()
    {
        try {
            $query = $this->getQuery();
            if ($sql = $this->connection->query($query)) {
                $num = $sql->num_rows;
                if ($num == 1) {
                    return $sql->fetch_assoc();
                } else {
                    while ($row = $sql->fetch_all(MYSQLI_ASSOC)) {
                        return $row;
                    }
                }
            } else {
                //сделать потом через сессии
                return "Произошла ошибка при получении данных";
            }

            $this->connection->close();
        } catch (\Exception $e) {
            die("Errors: " . $e);
        }
    }

    /**
     * @param array $select
     * @param string $bd_name
     */
    public function select(array $select, string $bd_name)
    {
        $select = Helpers::implode_sql($select, "`");
        $query = "SELECT $select FROM `$bd_name`";
        $this->setQuery($query);
        return $this;
    }

    /**
     * @param string $field
     * @param string | int $value
     * @param string $operator
     */
    public function where(string $field, string | array $value, string $operator = "=", string $separator = 'AND')
    {
        /**
         * С следующих обновлениях данная функция получит обновление функционала
         */
        if ($field != '' && $value != '') {
            if (str_contains($field, '.')) {
                $field = "`" . explode('.', $field)[0] . "`.`" . explode('.', $field)[1] . "` ";
            } else {
                $field = "`$field`";
            }

            switch ($separator) {
                case "AND":
                    if (str_contains($this->query, 'WHERE')) {
                        $sql = $this->query . " AND $field $operator '$value'";
                    } else {
                        $sql = $this->query . " WHERE $field $operator '$value'";
                    }
                    break;
                case "OR":
                    if (str_contains($this->query, 'WHERE')) {
                        $sql = $this->query . " OR $field $operator '$value'";
                    } else {
                        $sql = $this->query . " WHERE $field $operator '$value'";
                    }
                case "IN":
                    if (str_contains($this->query, 'WHERE')) {
                        $sql = $this->query . " AND $field IN ($value)";
                    } else {
                        $sql = $this->query . " WHERE $field IN ($value)";
                    }
            }
            $this->setQuery($sql);
            return $this;
        } else {
            echo "Недопустимые данные";
        }
    }

    /**
     * @param string $bd_name
     * @param array $values -> принимает ассоциативный массив
     */
    public function insert(string $bd_name, array $values)
    {
        $columns = [];
        $column_values = [];


        foreach ($values as $key => $value) {
            array_push($columns, $key);
            array_push($column_values, $value);
        }

        $final_col = Helpers::implode_sql($columns, "`");

        $final_val = Helpers::implode_sql($column_values, "'");

        try {
            $this->connection->query(
                "INSERT INTO `$bd_name`($final_col) VALUES($final_val)"
            );
        } catch (\Exception $e) {
            die("Error: " . $e);
        }

        $last_id = mysqli_insert_id($this->connection);
        return $last_id;
    }

    /**
     * @param array $condition
     * @param string $db_name
     */
    public function delete(array $condition, string $db_name)
    {
        $query = "DELETE FROM `$db_name` WHERE ";
        $cond_amount = 1;
        if (isset($condition) && $db_name != '') {
            foreach ($condition as $key => $value) {
                if ($cond_amount > 1) {
                    $query .= " AND `$key` = '$value'";
                    $cond_amount++;
                } else {
                    $query .= "`$key` = '$value'";
                    $cond_amount++;
                }
            }
            try {
                $this->connection->query($query);
                return $this;
            } catch (\Exception $e) {
                die("Error: " . $e);
            }
        } else {
            echo "Данные пустые";
        }
    }

    /**
     * @param array $values
     * @param array $condition
     * @param string $db_name
     */
    public function update(array $values, array $condition, string $db_name)
    {
        if (isset($condition) && $db_name != "" && isset($values)) {
            $query = "UPDATE `$db_name` SET ";
            $cond_amount = 1;
            $value_amount = 1;
            foreach ($values as $key => $value) {
                if ($value_amount > 1) {
                    $query .= ", `$key` = '$value'";
                    $value_amount++;
                } else {
                    $query .= "`$key` = '$value'";
                    $value_amount++;
                }
            }

            foreach ($condition as $key => $value) {
                if ($cond_amount > 1) {
                    $query .= " AND `$key` = '$value'";
                    $cond_amount++;
                } else {
                    $query .= " WHERE `$key` = '$value'";
                    $cond_amount++;
                }
            }
            try {
                $this->connection->query($query);
            } catch (\Exception $e) {
                die("Error: " . $e);
            }
        } else {
            echo "недопустимые данные";
        }
    }

    /**
     * @param array $condition
     * @param string $db_name
     */
    public function join(array $condition, string $db_name)
    {
        if (isset($condition) && $db_name != "") {
            $query = " INNER JOIN `$db_name` ON";
            foreach ($condition as $key => $value) {
                $corr_key = explode('.', $key);
                $corr_val = explode('.', $value);
                $query .= " `" . $corr_key[0] . "`.`" . $corr_key[1] . "` = `" . $corr_val[0] . "`.`" . $corr_val[1] . "` ";
            }
            $query = $this->getQuery() . $query;
            $this->setQuery($query);
            return $this;
        } else {
            echo "Недопустимые данные";
        }
    }
}
