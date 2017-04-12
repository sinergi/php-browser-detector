<?php

class Wikipedia
{
    const URL = 'https://en.wikipedia.org/w/api.php?action=query&titles=Microsoft_Edge&prop=revisions&rvprop=content&rvsection=4&format=xml';

    private static $errors = array(
        'fetch_error' => 'Unable to fetch content',
        'parse_error' => 'Unable to parse content',
    );

    public static function fetch()
    {
        $content = file_get_contents(self::URL);
        if (!$content) {
            throw new Exception(self::$errors['fetch_error']);
        }
        $content = explode('===Release history===', $content);
        if (!isset($content[1])) {
            throw new Exception(self::$errors['parse_error']);
        }
        $table = explode('|-', $content[1]);
        if (!isset($table[1])) {
            throw new Exception(self::$errors['parse_error']);
        }
        $table = array_slice($table, 1);
        $versions = array_map(array('Wikipedia', 'extractVersion'), $table);
        self::writeEdgeVersions($versions);
    }

    private static function extractVersion($content)
    {
        $lines = array_slice(array_filter(
            explode(PHP_EOL, $content),
            function ($val) {
                return trim($val) && strpos($val, '|') === 0;
            }
        ), 0, 2);

        preg_match("/{[^}{]*Version[^}{]*\| ?([\d\.]+)}/", $lines[0], $edgeVersion);
        preg_match("/\| *(\d*\.\d*)/", $lines[1], $edgeHtmlVersion);

        if (!isset($edgeVersion[1])) {
            throw new Exception(self::$errors['parse_error']);
        }
        if (!isset($edgeHtmlVersion[1])) {
            throw new Exception(self::$errors['parse_error']);
        }

        return array($edgeHtmlVersion[1], $edgeVersion[1]);
    }

    private static function writeEdgeVersions($versions)
    {
        $file = __DIR__ . '/../../src/edgeVersionMap.php';
        $currentVersions = require $file;

        foreach ($versions as $version) {
            $currentVersions[$version[0]] = $version[1];
        }
        ksort($currentVersions);

        $content = '';
        foreach ($currentVersions as $edgeHtml => $edge) {
            $content .= "    '{$edgeHtml}' => '{$edge}'," . PHP_EOL;
        }
        $data = <<<PHP
<?php

return array(
    %s
);

PHP;
        file_put_contents($file, sprintf($data, trim($content)));
    }
}
