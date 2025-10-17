<?php

namespace Database\Seeders\Dumps;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SessionsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sessions')->truncate();
        DB::table('sessions')->insert(array (
  0 => 
  array (
    'id' => '9gfY3EwXQj8opnGD5wh10AmRwqq8CyUdUAOMBIsc',
    'user_id' => NULL,
    'ip_address' => '127.0.0.1',
    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0',
    'payload' => 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibkdzZmJXeVhVd2dPTEpkMU1yZnVYUjZkVGpsRFFocVdteW8zVnZzQyI7czo3OiJzdWNjZXNzIjtzOjI1OiJZb3UgaGF2ZSBiZWVuIGxvZ2dlZCBvdXQuIjtzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MTp7aTowO3M6Nzoic3VjY2VzcyI7fX19',
    'last_activity' => 1760690676,
  ),
  1 => 
  array (
    'id' => 'bUZANfcjA355ukHS3o7Ie3QUfLcrLqBYCNSsbVOZ',
    'user_id' => NULL,
    'ip_address' => '127.0.0.1',
    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0',
    'payload' => 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiQkZGRXI0SjNqN1hTMXRmbHNWNGxUTmFUMWlsN3NZOU5abnpCTjBuTSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',
    'last_activity' => 1760690703,
  ),
  2 => 
  array (
    'id' => 'COCzPwlhlGLlHXVxXed8zh8KnP2vCzrOGvZuWmoU',
    'user_id' => NULL,
    'ip_address' => '127.0.0.1',
    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0',
    'payload' => 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiTlV6YXBjekpUUDhzeE1KWDh1MGpSUnIxWXp1NXJpcHZSaHZMeG92YiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319fQ==',
    'last_activity' => 1760690686,
  ),
  3 => 
  array (
    'id' => 'D9ELoBPNJmxMLlNe1GQyHxQHBQykUt074K3qcsGS',
    'user_id' => NULL,
    'ip_address' => '127.0.0.1',
    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0',
    'payload' => 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMXpZdEFpcGlXbHl6UkVCdUhtU3RhTXY1VkxGcVBwQ0lyblp0NG94ZyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9zY21zLnRlc3QvYWRtaW4vbG9naW4iO319',
    'last_activity' => 1760690676,
  ),
  4 => 
  array (
    'id' => 'iMJ45mkVJsAHbeK0XpwUQjnSUTJXSwvXqG1Ogk5Q',
    'user_id' => NULL,
    'ip_address' => '127.0.0.1',
    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0',
    'payload' => 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRmJzVHRHZlk4dzJ3MGVOaXpOb1lORXlQN1RCNm5lSWRVc0w5UkxtTyI7czo3OiJzdWNjZXNzIjtzOjI1OiJZb3UgaGF2ZSBiZWVuIGxvZ2dlZCBvdXQuIjtzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MTp7aTowO3M6Nzoic3VjY2VzcyI7fX19',
    'last_activity' => 1760692087,
  ),
  5 => 
  array (
    'id' => 'SkTmVZAhdpOw2Oss17580gQFOC1ZcHTpW5Y0jBRc',
    'user_id' => NULL,
    'ip_address' => '127.0.0.1',
    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0',
    'payload' => 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM2xwU1piRjkxVmlSaFg2SWlYUE9yd0lrRjNVQ25ncXRzcEpTZGwyNyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9zY21zLnRlc3QvYWRtaW4vbG9naW4iO319',
    'last_activity' => 1760692155,
  ),
  6 => 
  array (
    'id' => 'T9e2QQ1pSoisHOK8UaJOePNXv0a7xAbTqKWE4ioZ',
    'user_id' => NULL,
    'ip_address' => '127.0.0.1',
    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0',
    'payload' => 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRWtQWDJENmhUYVhTSml3TzY2bldJNFN5NlhwNnhYckNIOVdBaTFqeCI7czo3OiJzdWNjZXNzIjtzOjI1OiJZb3UgaGF2ZSBiZWVuIGxvZ2dlZCBvdXQuIjtzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MTp7aTowO3M6Nzoic3VjY2VzcyI7fX19',
    'last_activity' => 1760692087,
  ),
  7 => 
  array (
    'id' => 'ZhQc6kK7O7hbyYPANhOD8hi5D4PhhluujUwP8s0f',
    'user_id' => NULL,
    'ip_address' => '127.0.0.1',
    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 OPR/122.0.0.0',
    'payload' => 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicVhxQWdFcGVxbVNBcTFzaHVYaHo4aGw3bjl0ejQyZUNFN1ZPQlRMOCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly9zY21zLnRlc3Qvc3VwZXItYWRtaW4vbG9naW4iO319',
    'last_activity' => 1760692188,
  ),
));
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
