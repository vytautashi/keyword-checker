<?php
// This example runs script via command and
// finds website positions in search engine
// for specific keywords.

const WEBSITE = 'https://go.dev/';
const KEYWORDS = 'golang,google programming,design';

main();

function main()
{
    $command = 'php ../keyword_checker.php "' . KEYWORDS . '"';
    exec($command, $output, $retval);

    if ($retval !== 0) {
        exit('ERROR: ' . implode("\n", $output));
    }

    $json = implode("", $output);
    $rankings = json_decode($json);

    print_r(find_positions($rankings, WEBSITE));
}

function find_positions($data, $website)
{
    $keywords_pos = [];

    foreach ($data as $item) {
        $i = 1;
        $pos = [];
        foreach ($item->positions as $web) {
            if ($web === $website) {
                $pos[] = $i;
            }
            $i++;
        }
        $keywords_pos[$item->keyword] = implode(',', $pos);
    }
    return $keywords_pos;
}
