<?php

namespace PopUpMakerForCF7\basic\storage;

use Exception;

class storageDB
{
    private $data = [];

    function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->data[$key] = $value;
        }
    }

    function one_call_init()
    {
        if (!isset($this->data['table_name'])) {
            throw new Exception('При створенні об\'єкту "storageDB" не вказано "table_name"');
        }
        global $wpdb;
        $result = $wpdb->query("CREATE TABLE IF NOT EXISTS `" . DB_NAME . "`.`" . $wpdb->base_prefix . $this->data['table_name'] . "` ( `id` INT NOT NULL AUTO_INCREMENT, `key` VARCHAR(255) NOT NULL, `value` TEXT NOT NULL, `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`)) ENGINE = InnoDB");
        if ($result == false) {
            trigger_error(var_export($wpdb->last_error, true));
        }
    }

    function destroy()
    {
        global $wpdb;
        $result = $wpdb->query("DROP TABLE `" . DB_NAME . "`.`" . $wpdb->base_prefix . $this->data['table_name'] . "`");
        if ($result == false) {
            trigger_error(var_export($wpdb->last_error, true));
        }
    }

    function get($key)
    {
        global $wpdb;
        $results = $wpdb->get_results(
            $wpdb->prepare("SELECT `value` FROM " . $wpdb->base_prefix . $this->data['table_name'] . " WHERE `key` = %s LIMIT 1",$key)
        , ARRAY_A);
        if ($results) {
            return $results[0]['value'];
        } else {
            return false;
        }
    }

    function del($key)
    {
        global $wpdb;
        $wpdb->query(
            $wpdb->prepare("DELETE FROM " . $wpdb->base_prefix . $this->data['table_name'] . " WHERE `key` = %s ",$key)
        );
        if (empty($wpdb->last_error)) {
            return true;
        } else {
            trigger_error(var_export($wpdb->last_error, true));
            return false;
        }
    }

    function set($key, $val)
    {
        global $wpdb;


        if ($this->get($key)) {
            $result = $wpdb->update(
                $wpdb->base_prefix . $this->data['table_name'],
                array(
                    'value' => $val,
                ),
                array('key' => $key)
            );
        } else {
            $result = $wpdb->insert($wpdb->base_prefix . $this->data['table_name'], [
                'key' => $key,
                'value' => $val
            ]);
            if ($result) {
            } else {
                trigger_error($wpdb->last_error);
            }
        }

        if ($result === false) {
            trigger_error($wpdb->last_error);
        }
        return $result;
    }
}
