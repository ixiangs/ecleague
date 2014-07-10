<?php
namespace Toy\Platform;

use Toy\Util\RandomUtil;

class FileUtil
{

    static public function createDirectory($path)
    {
        if (!self::isDirectory($path)) {
            return mkdir($path, 0777, true);
        }
        return true;
    }

    static public function deleteDirectory($path)
    {
        if (self::isDirectory($path)) {
            $dh = opendir($path);
            while ($file = readdir($dh)) {
                if ($file != "." && $file != "..") {
                    $fullpath = $path . "/" . $file;
                    if (!is_dir($fullpath)) {
                        unlink($fullpath);
                    } else {
                        self::deleteDirectory($fullpath);
                    }
                }
            }

            closedir($dh);
            if (rmdir($path)) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

    static public function createSubDirectory($path, $size = 6)
    {
        self::createDirectory($path);
        while (true) {
            $tmp = RandomUtil::randomCharacters($size);
            $dir = PathUtil::combines($path, $tmp);
            if (!self::isDirectory($dir)) {
                self::createDirectory($dir);
                return $tmp;
            }
        }
        return false;
    }

    static public function moveUploadFile($tmp, $target)
    {
        return move_uploaded_file($tmp, $target);
    }

    static public function writeFile($filename, $data)
    {
        file_put_contents($filename, $data);
    }

    static public function readFile($filename)
    {
        return file_get_contents($filename);
    }

    static public function readCsv($filename)
    {
        $result = array();
        if (($handle = fopen($filename, "r")) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                $result[] = $data;
            }
            fclose($handle);
        }
        return $result;
    }

    static public function readJson($filename)
    {
        $content = file_get_contents($filename);
        $result = json_decode($content, true);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return $result;
            case JSON_ERROR_DEPTH:
                echo ' - Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                echo ' - Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                echo ' - Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                echo ' - Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                echo ' - Unknown error';
                break;
        }
        return null;
    }

    static public function getDirectories($dir)
    {
        $result = array();
        if ($handle = opendir($dir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != '.' && $file != '..') {
                    $filename = PathUtil::combines($dir, $file);
                    if (is_dir($filename)) {
                        $result[] = $filename;
                    }
                }
            }
            closedir($handle);
        }
        return $result;
    }

    static public function checkExists($filename)
    {
        return file_exists($filename);
    }

    static public function isDirectory($filename)
    {
        return is_dir($filename);
    }

}
