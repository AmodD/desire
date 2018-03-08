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
	    DB::table('mlmodels')->insert([
		    ['id' => 1 ,  'name' => 'Test Data', 'relationship_id' => 0 , 'algorithm_id' => 0]
	    ]);

	    DB::table('algorithms')->insert([
		            ['id' => 1 ,  'name' => 'Apriori' , 'type' => 'Association'],
			    ['id' => 2 ,  'name' => 'SVC' , 'type' => 'Classification'],
			    ['id' => 3 ,  'name' => 'K-Nearest Neighbours' , 'type' => 'Classification'],
			    ['id' => 4 ,  'name' => 'Naive Bayes' , 'type' => 'Classification'],
			    ['id' => 5 ,  'name' => 'Least Squares' , 'type' => 'Regression'],
			    ['id' => 6 ,  'name' => 'SVR' , 'type' => 'Regression'],
			    ['id' => 7 ,  'name' => 'K-Means' , 'type' => 'Clustering'],
			    ['id' => 8 ,  'name' => 'DBSCAN' , 'type' => 'Clustering'],
			    ['id' => 9 ,  'name' => 'MLP Classifier Backpropagation' , 'type' => 'ANN']
	    ]);

	    DB::table('fields')->insert([
		            ['id' => 0 ,  'element' => 'MTI'],
		            ['id' => 1 ,  'element' => 'BitMap'],
		            ['id' => 2 ,  'element' => 'Primary account number (PAN)'],
		            ['id' => 3 ,  'element' => 'Processing Code'],
			    ['id' => 4 ,  'element' => 'Amount, transaction'],
			    ['id' => 18 , 'element' => 'Merchant Category Code'],
			    ['id' => 19 , 'element' => 'Acquiring institution country code'],
		            ['id' => 22 , 'element' => 'Point of Service Entry Mode'],
		            ['id' => 25 , 'element' => 'POS Condition Code'],
			    ['id' => 48 , 'element' => 'Additional data - private'],
			    ['id' => 49 , 'element' => 'Currency code, transaction'],
		            ['id' => 55 , 'element' => 'Chip Data'],
		            ['id' => 60 , 'element' => 'Additional POS Data']

		    ]);
    }
}
