<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'company_id' => 0,
            'name' => "super-amdin",
            'display_name' => 'Super Admin',
            'description' => 'Super Admin'
        ]);
        DB::table('roles')->insert([
        	'company_id' => 0,
            'name' => "admin",
            'display_name' => 'Admin',
            'description' => 'Admin'
        ]);
        DB::table('roles')->insert([
        	'company_id' => 0,
            'name' => "process-owner",
            'display_name' => 'Process Owner / Quality Manager',
            'description' => 'Process Owner / Quality Manager'
        ]);
        DB::table('roles')->insert([
        	'company_id' => 0,
            'name' => "qtl",
            'display_name' => 'Quality Team Leader(QTL)',
            'description' => 'Quality Team Leader'
        ]);
        DB::table('roles')->insert([
        	'company_id' => 0,
            'name' => "qa",
            'display_name' => 'Quality Analyst(QA)',
            'description' => 'Quality Analyst'
        ]);
        DB::table('roles')->insert([
        	'company_id' => 0,
            'name' => "qc",
            'display_name' => 'Quality Control',
            'description' => 'Quality Control'
        ]);
        DB::table('roles')->insert([
        	'company_id' => 0,
            'name' => "agent",
            'display_name' => 'Agent',
            'description' => 'Agent'
        ]);
        DB::table('roles')->insert([
        	'company_id' => 0,
            'name' => "client",
            'display_name' => 'Client',
            'description' => 'Client'
        ]);
        DB::table('roles')->insert([
        	'company_id' => 0,
            'name' => "partner-training-head",
            'display_name' => 'Partner Training Head',
            'description' => 'Partner Training Head'
        ]);
        DB::table('roles')->insert([
        	'company_id' => 0,
            'name' => "partner-operation-head",
            'display_name' => 'Partner Operation Head',
            'description' => 'Partner Operation Head'
        ]);
        DB::table('roles')->insert([
        	'company_id' => 0,
            'name' => "partner-quality-head",
            'display_name' => 'Partner Quality Head',
            'description' => 'Partner Quality Head'
        ]);
    }
}
