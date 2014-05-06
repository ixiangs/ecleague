<?php
namespace Toy\Web;

use Toy\Event;
use Toy\Platform\FileUtil;
use Toy\Platform\PathUtil;

class Initializer
{

    public function initialize()
    {
        PathUtil::scanCurrent(Configuration::$componentDirectory, function ($dir1, $info1) {
            PathUtil::scanCurrent($dir1, function ($dir2, $info2) use($info1){
                PathUtil::scanCurrent($dir2, function ($file, $fileinfo) use ($info1, $info2) {
                    if (array_key_exists('extension', $fileinfo) && $fileinfo['extension'] == 'json') {
                        $content = FileUtil::readFile($file);
                        if (preg_match_all('/(<[@]\w+>)/i', $content, $matches)) {
                            foreach ($matches[0] as $match) {
                                $key = substr($match, 1, -1);
                                if ($key[0] == '@') {
                                    $content = str_replace($match, str_replace('\\', '\\\\', constant(substr($key, 1))), $content);
                                }
                            }
                        }

                        $conf = json_decode($content, true);
                        switch (json_last_error()) {
                            case JSON_ERROR_NONE:
                                Application::$settings[$conf['name']] = $conf;
                                break;
                            case JSON_ERROR_DEPTH:
                                echo 'Maximum stack depth exceeded';
                                break;
                            case JSON_ERROR_STATE_MISMATCH:
                                echo 'Underflow or the modes mismatch';
                                break;
                            case JSON_ERROR_CTRL_CHAR:
                                echo 'Unexpected control character found';
                                break;
                            case JSON_ERROR_SYNTAX:
                                echo 'Syntax error, malformed JSON';
                                break;
                            case JSON_ERROR_UTF8:
                                echo 'Malformed UTF-8 characters, possibly incorrectly encoded';
                                break;
                            default:
                                echo ' - Unknown error';
                                break;
                        }
                    }
                });
            });
        });

        foreach (Application::$settings as $conf) {
            if (array_key_exists('listeners', $conf)) {
                foreach ($conf['listeners'] as $en => $eh) {
                    Event\Configuration::addListener($en, $eh);
                }
            }
        }
    }
}