<?php

namespace App\Services\Https;

class DynamicUrl
{
    private $url;
    private $dynamic;

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getDyn()
    {
        return $this->dynamic;
    }

    public function SetDyn($dynamic)
    {
        $this->dynamic = $dynamic;
    }
    public function check_url(string $url)
    {
        if (str_contains($url, '{') && str_contains($url, '}')) {
            $req = explode('/', Request::query('q'));
            $default_url = explode('/', $url);
            $dyn_index = 0;
            $curr_page_url = '';
            foreach ($default_url as $key => $value) {
                if (str_contains($value, '{')) {
                    $dyn_index = $key - 1;
                } else {
                    if ($curr_page_url == '')
                        $curr_page_url .= $value;
                    else
                        $curr_page_url .= '/' . $value;
                }
            }
            $curr_dyn_part = $req[$dyn_index] ?? null;
            // echo $curr_page_url . "<br>";
            // echo $curr_dyn_part . "<br>";

            // $curr_page_url = $req[0]; //profile
            // $curr_dyn_part = $req[1] ?? null;
            // echo $page_url = explode('/', $url)[1] . "<br>";
            // $dyn_part = explode('/', $url)[2] . "<br>";
            $page_url = '';
            $dyn_part = '';
            foreach ($default_url as $key => $value) {
                if (str_contains($value, '{')) {
                    $dyn_part .= $value;
                } else {
                    if ($page_url == '')
                        $page_url .= $value;
                    else 
                        $page_url .= '/'. $value;
                }
            }
            // echo $page_url . "<br>";
            // echo $dyn_part . "<br>";

            if ($curr_page_url == $page_url && $dyn_part != '') {
                $this->SetDyn($curr_dyn_part);
                $this->setUrl("/" . $page_url . "/" . $curr_dyn_part);
                return $this;
            } else {
                $this->setUrl($url);
                return $this;
            }
        } else {
            $this->setUrl($url);
            return $this;
        }
    }
}
