<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	// $this->call(UsersTableSeeder::class);
	    DB::table('fields')->insert([
		            ['id' => 0 , 'element' => 'MTI'],
		            ['id' => 1 , 'element' => 'BitMap'],
		            ['id' => 2 , 'element' => 'Primary account number (PAN)'],
			    ['id' => 4 ,  'element' => 'Amount, transaction'],
			    ['id' => 19 , 'element' => 'Acquiring institution country code'],
			    ['id' => 48 , 'element' => 'Additional data - private'],
			    ['id' => 49 , 'element' => 'Currency code, transaction']

		    ]);
    }
}
