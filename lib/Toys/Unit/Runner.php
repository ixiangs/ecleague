<?php
namespace Toys\Unit;

class Runner
{

    private $_settings = null;
    private $_testCaseFiles = array();
    private $_testFailures = array();
    private $_testSuccess = array();

    private function __construct($settings)
    {
        $this->_settings = $settings;
    }

    private function renderHtml()
    {
        include_once 'tmpl.php';
    }

    private function renderConsole()
    {
        print 'Total:' . (count($this->_testFailures) + count($this->_testSuccess)) . ' Success:' . count($this->_testSuccess) . ' Failues:' . count($this->_testFailures);
        print "\n";
        foreach ($this->_testFailures as $i => $f) {
            print $f['message'];
            printf("\n  file:%s$%s", $f['file'], $f['line']);
            printf("\n  method:%s$%s", $f['class'], $f['method']);
            print "\n";
        }
    }

    private function render()
    {
        if ($this->_settings['output'] == 'console') {
            $this->renderConsole();
        } else {
            $this->renderHtml();
        }
    }

    private function runAllTestCases()
    {
        foreach ($this->_testCaseFiles as $file) {
            $this->runTestCase($file);
        }
    }

    private function runTestCase($filename)
    {
        $parts = pathinfo($filename);
        $className = $parts['filename'];
        include_once $filename;
        $inst = new $className();
        $ref = new \ReflectionClass($className);
        $methods = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method) {
            if (substr($method->getName(), 0, 4) == 'test') {
                try {
                    $method->invoke($inst);
                    $this->_testSuccess[] = array('file' => $filename, 'class' => $className, 'method' => $method->getName());
                } catch (AssertException $ex) {
                    $tex = $ex->getTrace();
                    $this->_testFailures[] = array('file' => $filename, 'class' => $className, 'method' => $method->getName(), 'line' => $tex[0]['line'], 'message' => $ex->getMessage());
                }
            }
        }
    }

    private function findAllTestCases()
    {
        $this->scanDirectory($this->_settings['directory']);
    }

    private function scanDirectory($path)
    {
        if ($dh = opendir($path)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' && $file != '..') {
                    if (substr($path, -1) != DIRECTORY_SEPARATOR) {
                        $path .= DIRECTORY_SEPARATOR;
                    }
                    $filename = $path . $file;
                    if (is_dir($filename)) {
                        $this->scanDirectory($filename);
                    } else {
                        $parts = pathinfo($filename);
                        if (array_key_exists('extension', $parts) && $parts['extension'] == 'php' && substr($file, -12) == 'TestCase.php') {
                            $this->_testCaseFiles[] = $filename;
                        }
                    }
                }
            }
            closedir($dh);
        }
    }

    public static function runFile(){

    }

    public static function runDirectory(){

    }

    public static function run(array $settings)
    {
        $me = new self($settings);
        $me->findAllTestCases();
        $me->runAllTestCases();
        $me->render();
    }
}
