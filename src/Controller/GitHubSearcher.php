<?php
/**
 * Created by PhpStorm.
 * User: admin-
 * Date: 28.05.19.
 * Time: 20:03
 */

namespace App\Controller;

class GitHubSearcher implements SearchInterface
{
    public function getRocks($word): int
    {
        return $this->exec_gitHub_req($word, "rocks");
    }

    public function getSucks($word): int
    {
        return $this->exec_gitHub_req($word, "sucks");
    }

    private function exec_gitHub_req($word, $q_add): int
    {
        $url = "https://api.github.com/search/issues?q=" . $word . "+q=" . $q_add;
        $headers = ['User-Agent: ipavelic'];

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_HEADER, 0); //use this to suppress output
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);

        $data = curl_exec($handle);
        curl_close($handle);

        $res = json_decode($data, true);
        return $res["total_count"];
    }
}

