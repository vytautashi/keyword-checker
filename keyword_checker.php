<?php
const VERSION = '1.0.0v';
const DEFAULT_SEARCH_URL = 'https://www.google.com/search?num=10&q=';
const DEFAULT_USERAGENT = '';

main();

function main()
{
    $cmd_argv = $_SERVER['argv'] ?? null;

    $keywords_raw = $_GET['keywords'] ?? $cmd_argv[1] ?? '';
    $search_url = $_GET['search_url'] ?? $cmd_argv[2] ?? DEFAULT_SEARCH_URL;
    $useragent = $_GET['useragent'] ?? $cmd_argv[3] ?? DEFAULT_USERAGENT;
    $keywords = explode(',', $keywords_raw);

    if ($keywords_raw === '') {
        print_help();
    }

    fetch_and_parse_rankings($keywords, $search_url, $useragent);
}

function fetch_and_parse_rankings($keywords, $search_url, $useragent)
{
    $res = array_map(function ($k) use ($useragent, $search_url) {

        $url = $search_url . rawurlencode($k);
        $res = fetch($url, $useragent);
        $positions = '""';

        if ($res[1] === null) {
            $positions = json_encode(parse_rankings($res[0]), JSON_UNESCAPED_SLASHES);
        }

        return '{'
            . '"fetch_url": "' . $url . '",'
            . '"keyword": "' . $k . '",'
            . '"positions": ' . $positions . ','
            . '"error": "' . $res[1] . '"'
            . '}';
    }, $keywords);

    echo "[" . implode(",", $res) . "]";
}


function parse_rankings($html)
{
    $dom = new DOMDocument;
    @$dom->loadHTML($html);
    $xpath = new DOMXpath($dom);
    $links = $xpath->query("//a[./h3 and starts-with(@href, '/url?q=')]");

    $urls_raw = [];
    foreach ($links as $link) {
        $urls_raw[] = $link->getAttribute('href');
    }

    $urls = array_map(function ($e) {
        preg_match('/^\/url\?q=([^&]+)&.+$/', $e, $match);
        $url_decoded = rawurldecode(rawurldecode($match[1]));
        return $url_decoded;
    }, $urls_raw);

    return $urls;
}

function fetch($url = "", $useragent = "")
{
    $ch = curl_init("");
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $output = curl_exec($ch);
    curl_close($ch);

    if ($output === false) {
        return [null, "can not fetch content from url: " . $url];
    }

    return [$output, null];
}

function print_help()
{
    if (isset($_SERVER['argv'])) {
        echo "  version: \n    " . VERSION;
        echo "\n  command example:\n";
        echo '    php ' . $_SERVER['SCRIPT_FILENAME'] . ' "web design,online shopping"';
    } else {
        echo '<pre>';
        echo "  version: \n    " . VERSION;
        echo "\n  url example:\n";
        echo '    ' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'] . '?keywords=web+design,online+shopping';
        echo '</pre>';
    }

    exit();
}
