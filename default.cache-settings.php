<?php
$memcache = new Memcache();
$memcache->connect('localhost', 11211);

/**
 * Can use Flourish classes here :-)
 */
$cache = new fCache('memcache', $memcache);

define('ENABLE_SCHEMA_CACHING', TRUE);
