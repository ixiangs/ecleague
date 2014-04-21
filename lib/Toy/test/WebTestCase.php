<?php
use Toy\Unit\TestCase;
use Toy\Web\Configuration;
use Toy\Web\Router;
use Toy\Web\Dispatcher;
use Toy\Web\Template;
use Toy\Web\Application;
use Toy\Joy;

class WebTestCase extends TestCase {

	public function __construct() {
		Configuration::addDomain('frontend', '/', 'Frontend', 'index/index/index', TRUE);
		Configuration::addDomain('backend', '/admin/', 'Backend', 'index/index/index');
		Configuration::addDomain('member', '/member/', 'Member', 'index/index/index');
		Configuration::$componentDirectories[] = TEST_PATH.'components';
		Configuration::$templateDirectories = array(TEST_PATH.'templates');
		Configuration::$templateTheme = 'default';
		// Configuration::$language = 'zh-CN';
		Configuration::$trace = true;
	}

	public function testRouter() {
		$r = new Router();
		//test frontend index
		$s = $r -> route('/');
		$this -> assertEqual('frontend', $s -> getDomain() -> getName());
		$this -> assertEqual('Frontend', $s -> getDomain() -> getNamespace());
		$this -> assertEqual('/', $s -> getDomain() -> getUrl());
		$this -> assertEqual('index', $s -> getComponent());
		$this -> assertEqual('index', $s -> getComponent());
		$this -> assertEqual('index', $s -> getComponent());

		//test backend index
		$s = $r -> route('/admin/');
		$this -> assertEqual('backend', $s -> getDomain() -> getName());
		$this -> assertEqual('index', $s -> getComponent());
		$this -> assertEqual('index', $s -> getComponent());
		$this -> assertEqual('index', $s -> getComponent());

		//test member index
		$s = $r -> route('/member/');
		$this -> assertEqual('member', $s -> getDomain() -> getName());
		$this -> assertEqual('index', $s -> getComponent());
		$this -> assertEqual('index', $s -> getComponent());
		$this -> assertEqual('index', $s -> getComponent());

		//test query string
		$s = $r -> route('/?domain=backend&component=test&controller=first&action=second');
		$this -> assertEqual('backend', $s -> getDomain() -> getName());
		$this -> assertEqual('test', $s -> getComponent());
		$this -> assertEqual('first', $s -> getController());
		$this -> assertEqual('second', $s -> getAction());

		$s = $r -> route('/?component=test&controller=first&action=second');
		$this -> assertEqual('frontend', $s -> getDomain() -> getName());
		$this -> assertEqual('test', $s -> getComponent());
		$this -> assertEqual('first', $s -> getController());
		$this -> assertEqual('second', $s -> getAction());

		//test parameters
		$s = $r -> route('/admin/first/second/third/a/1/b/2');
		$this -> assertEqual('backend', $s -> getDomain() -> getName());
		$this -> assertEqual('first', $s -> getComponent());
		$this -> assertEqual('second', $s -> getController());
		$this -> assertEqual('third', $s -> getAction());
		$this -> assertEqual(4, count($s -> getParameters()));
		$params = $s -> getParameters();
		$this -> assertEqual('a', $params[0]);
		$this -> assertEqual(1, $params[1]);
		$this -> assertEqual('b', $params[2]);
		$this -> assertEqual(2, $params[3]);

		$s = $r -> route('/admin/first/second/third');
		$this -> assertEqual('backend', $s -> getDomain() -> getName());
		$this -> assertEqual('first', $s -> getComponent());
		$this -> assertEqual('second', $s -> getController());
		$this -> assertEqual('third', $s -> getAction());
		$this -> assertEqual(0, count($s -> getParameters()));

		$s = $r -> route('/member/one/two/three/hello/world');
		$url = $r -> buildUrl('backend/first/second/third');
		$this -> assertEqual('/admin/first/second/third', $url);

		$url = $r -> buildUrl('backend/first/second/third', array(1, 'd'));
		$this -> assertEqual('/admin/first/second/third/1/d', $url);

		$url = $r -> buildUrl();
		$this -> assertEqual('/member/one/two/three', $url);

		$url = $r -> buildUrl(null, array('ronald', 'xian'));
		$this -> assertEqual('/member/one/two/three/ronald/xian', $url);

		$url = $r -> buildUrl('four', array('ronald', 'xian'));
		$this -> assertEqual('/member/one/two/four/ronald/xian', $url);

		$url = $r -> buildUrl('five/four', array('ronald', 'xian'));
		$this -> assertEqual('/member/one/five/four/ronald/xian', $url);

		$url = $r -> buildUrl('six/five/four', array('ronald', 'xian'));
		$this -> assertEqual('/member/six/five/four/ronald/xian', $url);

		$url = $r -> buildUrl('frontend/six/five/four', array('ronald', 'xian'));
		$this -> assertEqual('/six/five/four/ronald/xian', $url);

		$url = $r -> buildUrl('six/five/four', null);
		$this -> assertEqual('/member/six/five/four', $url);

		Configuration::$urlFormat = Configuration::URL_FORMAT_NAME_PARAMETER;
		$s = $r -> route('/admin/first/second/third/a/1/b/2');
		$this -> assertEqual('backend', $s -> getDomain() -> getName());
		$this -> assertEqual('first', $s -> getComponent());
		$this -> assertEqual('second', $s -> getController());
		$this -> assertEqual('third', $s -> getAction());
		$this -> assertEqual(2, count($s -> getParameters()));
		$params = $s -> getParameters();
		$this -> assertEqual(1, $params['a']);
		$this -> assertEqual(2, $params['b']);

		$url = $r -> buildUrl('frontend/six/five/four', array('a'=>'ronald', 'b'=>'xian'));
		$this -> assertEqual('/six/five/four/a/ronald/b/xian', $url);
		
		$url = $r -> buildUrl('member/six/five/four', array('a'=>'ronald', 'b'=>'xian'));
		$this -> assertEqual('/member/six/five/four/a/ronald/b/xian', $url);		
		
		Configuration::$urlFormat = Configuration::URL_FORAMT_QUERY_STRING;
		$url = $r -> buildUrl('frontend/six/five/four', array('a'=>'ronald', 'b'=>'xian'));
		$this -> assertEqual('/?component=six&controller=five&action=four&a=ronald&b=xian', $url);		

		$url = $r -> buildUrl('member/six/five/four', array('a'=>'ronald', 'b'=>'xian'));
		$this -> assertEqual('/?domain=member&component=six&controller=five&action=four&a=ronald&b=xian', $url);		
	}

	// public function testDispatcher(){
		// $d = new Dispatcher();
		// $r = $d->call('Frontend','Index', 'Index', 'Index');
		// $this->assertEqual('\frontend\index\index\index', $r);
// 		
		// $r = $d->call('Frontend','Index', 'Index', 'two', array('1'));
		// $this->assertEqual('1|', $r);
// 		
		// $r = $d->call('Frontend','Index', 'Index', 'two', array('1', '2'));
		// $this->assertEqual('1|2', $r);					
	// }
// 
	// public function testTemplate(){
		// $r = new Router();
		// $c = Application::$context;
		// $c->setObjective($r->parseUrl('/admin/index/index/login'));
		// $t = new Template();
		// $this->assertEqual('1', $t->render('index/index/login'));
		// $this->assertEqual('1', $t->render());
		// $this->assertEqual('1', $t->render('/backend/zh-CN/default/index/index/login'));
	// }
}
