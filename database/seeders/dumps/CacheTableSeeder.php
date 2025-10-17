<?php

namespace Database\Seeders\Dumps;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CacheTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cache')->truncate();
        DB::table('cache')->insert(array (
  0 => 
  array (
    'key' => 'laravel_cache_0ade7c2cf97f75d009975f4d720d1fa6c19f4897',
    'value' => 'i:1;',
    'expiration' => 1760367636,
  ),
  1 => 
  array (
    'key' => 'laravel_cache_0ade7c2cf97f75d009975f4d720d1fa6c19f4897:timer',
    'value' => 'i:1760367636;',
    'expiration' => 1760367636,
  ),
  2 => 
  array (
    'key' => 'laravel_cache_17ba0791499db908433b80f37c5fbc89b870084b',
    'value' => 'i:1;',
    'expiration' => 1760377390,
  ),
  3 => 
  array (
    'key' => 'laravel_cache_17ba0791499db908433b80f37c5fbc89b870084b:timer',
    'value' => 'i:1760377390;',
    'expiration' => 1760377390,
  ),
  4 => 
  array (
    'key' => 'laravel_cache_5c785c036466adea360111aa28563bfd556b5fba',
    'value' => 'i:1;',
    'expiration' => 1760377922,
  ),
  5 => 
  array (
    'key' => 'laravel_cache_5c785c036466adea360111aa28563bfd556b5fba:timer',
    'value' => 'i:1760377922;',
    'expiration' => 1760377922,
  ),
  6 => 
  array (
    'key' => 'laravel_cache_angelcoleendimatulac@plv.edu.ph|127.0.0.1',
    'value' => 'i:1;',
    'expiration' => 1760397368,
  ),
  7 => 
  array (
    'key' => 'laravel_cache_angelcoleendimatulac@plv.edu.ph|127.0.0.1:timer',
    'value' => 'i:1760397368;',
    'expiration' => 1760397368,
  ),
  8 => 
  array (
    'key' => 'laravel_cache_c1dfd96eea8cc2b62785275bca38ac261256e278',
    'value' => 'i:1;',
    'expiration' => 1760366914,
  ),
  9 => 
  array (
    'key' => 'laravel_cache_c1dfd96eea8cc2b62785275bca38ac261256e278:timer',
    'value' => 'i:1760366914;',
    'expiration' => 1760366914,
  ),
  10 => 
  array (
    'key' => 'laravel_cache_fa35e192121eabf3dabf9f5ea6abdbcbc107ac3b',
    'value' => 'i:1;',
    'expiration' => 1760397620,
  ),
  11 => 
  array (
    'key' => 'laravel_cache_fa35e192121eabf3dabf9f5ea6abdbcbc107ac3b:timer',
    'value' => 'i:1760397620;',
    'expiration' => 1760397620,
  ),
  12 => 
  array (
    'key' => 'laravel_cache_janarafael.sanandres@gmail.com|127.0.0.1',
    'value' => 'i:1;',
    'expiration' => 1760376614,
  ),
  13 => 
  array (
    'key' => 'laravel_cache_janarafael.sanandres@gmail.com|127.0.0.1:timer',
    'value' => 'i:1760376614;',
    'expiration' => 1760376614,
  ),
));
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
