--TEST--
Simple mysqli test
--INI--
extension=/home/johannes/src/php/php-memcached/modules/memcached.so
--SKIPIF--
<?php
require('skipif.inc');
?>
--FILE--
<?php
require 'table.inc';
init_memcache_config('f1,f2,f3', true, '|');

if (!$link = my_mysql_connect($host, $user, $passwd, $db, $port, $socket)) {
	die("Connection failed");
}

$memc = my_memcache_connect($memcache_host, $memcache_port);
mysqlnd_memcache_set($link, $memc, NULL, function ($success) { echo "Went through memcache: ".($success ? 'Yes' : 'No')."\n";});

echo "Fetching key1 via memcache:\n";
var_dump($memc->get("key1"));
echo "Querying SELECT f1, f2, f3 FROM mymem_test WHERE id = 'key1':\n";
$r = mysql_query("SELECT f1, f2, f3 FROM mymem_test WHERE id = 'key1'", $link);
var_dump(mysql_fetch_row($r));
?>
--EXPECT--
Fetching key1 via memcache:
string(5) "a|b|c"
Querying SELECT f1, f2, f3 FROM mymem_test WHERE id = 'key1':
Went through memcache: Yes
array(3) {
  [0]=>
  string(1) "a"
  [1]=>
  string(1) "b"
  [2]=>
  string(1) "c"
}
