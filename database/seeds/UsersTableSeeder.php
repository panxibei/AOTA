<?php

use Illuminate\Database\Seeder;

use App\Models\Admin\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$nowtime = date("Y-m-d H:i:s",time());
		$logintime = date("Y-m-d H:i:s", 86400);
		
        //
		User::truncate();
		
		// DB::table('configs')->insert(array (
		User::insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'admin',
                'ldapname' => 'admin',
                'email' => 'admin@aota.local',
                'displayname' => 'admin',
                'password' => '$2y$10$LZyZUTTyHugBeHGiSCumi.KKb4doF5eQYoKqIBYR03J84LLcEVVZW', // 默认密码123
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'root',
                'ldapname' => 'root',
                'email' => 'root@aota.local',
                'displayname' => 'root',
                'password' => '$2y$10$ihmDQIgX4hK8CPfH3PtImeeVW8mmAeP42I4Jbx0GcLtXtLiKxLaRi',
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'user1',
                'ldapname' => 'user1',
                'email' => 'user1@aota.local',
                'displayname' => 'user1',
                'password' => '$2y$10$ihmDQIgX4hK8CPfH3PtImeeVW8mmAeP42I4Jbx0GcLtXtLiKxLaRi',
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'user2',
                'ldapname' => 'user2',
                'email' => 'user2@aota.local',
                'displayname' => 'user2',
                'password' => '$2y$10$ihmDQIgX4hK8CPfH3PtImeeVW8mmAeP42I4Jbx0GcLtXtLiKxLaRi',
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'user3',
                'ldapname' => 'user3',
                'email' => 'user3@aota.local',
                'displayname' => 'user3',
                'password' => '$2y$10$ihmDQIgX4hK8CPfH3PtImeeVW8mmAeP42I4Jbx0GcLtXtLiKxLaRi',
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'user4',
                'ldapname' => 'user4',
                'email' => 'user4@aota.local',
                'displayname' => 'user4',
                'password' => '$2y$10$ihmDQIgX4hK8CPfH3PtImeeVW8mmAeP42I4Jbx0GcLtXtLiKxLaRi',
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'user5',
                'ldapname' => 'user5',
                'email' => 'user5@aota.local',
                'displayname' => 'user5',
                'password' => '$2y$10$ihmDQIgX4hK8CPfH3PtImeeVW8mmAeP42I4Jbx0GcLtXtLiKxLaRi',
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'user6',
                'ldapname' => 'user6',
                'email' => 'user6@aota.local',
                'displayname' => 'user6',
                'password' => '$2y$10$ihmDQIgX4hK8CPfH3PtImeeVW8mmAeP42I4Jbx0GcLtXtLiKxLaRi',
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'user7',
                'ldapname' => 'user7',
                'email' => 'user7@aota.local',
                'displayname' => 'user7',
                'password' => '$2y$10$ihmDQIgX4hK8CPfH3PtImeeVW8mmAeP42I4Jbx0GcLtXtLiKxLaRi',
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'user8',
                'ldapname' => 'user8',
                'email' => 'user8@aota.local',
                'displayname' => 'user8',
                'password' => '$2y$10$ihmDQIgX4hK8CPfH3PtImeeVW8mmAeP42I4Jbx0GcLtXtLiKxLaRi',
                'login_time' => $logintime,
                'login_ip' => '255.255.255.255',
                'login_counts' => 0,
                'remember_token' => '',
                'created_at' => $nowtime,
                'updated_at' => $nowtime,
                'deleted_at' => NULL,
            ),
        ));
	}
}
