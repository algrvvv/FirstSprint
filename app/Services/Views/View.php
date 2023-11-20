<?php

namespace App\Services\Views;
use App\Services\Session\Session;

class View
{
    public static string $title;
    /**
     * @param string $layout
     * @param string $page_title
     */
    public static function render_header($layout, $page_title = "Page title",)
    {
        self::$title = $page_title;
        $filename = 'views/layout/' . $layout . '.php';
        if (file_exists($filename)) {
            require_once  $filename;
        } else {
            die("Ошибка загрузки элемента");
        }
    }

    public static function render($layout, array $values = [])
    {
        $filename = 'views/' . $layout . '.php';
        $s = new Session();
        if (file_exists($filename)) {
            if(count($values) > 0) {
                foreach ($values as $key => $value) {
                    $s->create_session($key, $value);
                }
            }
            require_once  $filename;
        } else {
            die("Ошибка загрузки элемента");
        }
    }

    public static function get_title()
    {
        return self::$title;
    }
}
