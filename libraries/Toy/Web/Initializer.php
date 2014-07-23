<?php
namespace Toy\Web;

use Toy\Event;
use Toy\Platform\FileUtil;
use Toy\Platform\PathUtil;

class Initializer
{

    public function initialize()
    {
        Template::addHelper('html', \Toy\Html\Helper::singleton());
        PathUtil::scanCurrent(Configuration::$componentDirectory, function ($dir1, $info) {

            PathUtil::scanCurrent($dir1, function ($file1, $fileinfo2) {
                PathUtil::scanCurrent($file1, function ($file, $fileinfo) {
                    if ($fileinfo['basename'] == 'conf.json') {
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
                                Application::$components[strtolower($conf['id'])] = new Component($conf);
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

        foreach (Application::$components as $component) {
            $listeners = $component->getListeners();
            if ($listeners) {
                foreach ($listeners as $en => $eh) {
                    Event::attach($en, $eh);
                }
            }
        }
    }
}