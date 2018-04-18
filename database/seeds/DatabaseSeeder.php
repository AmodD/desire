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
			    ['id' => 5 ,  'element' => 'Amount, settlement'],
			    ['id' => 6 ,  'element' => 'Amount, cardholder billing'],
			    ['id' => 7 ,  'element' => 'Transmission Date & Time'],
			    ['id' => 18 , 'element' => 'Merchant Category Code'],
			    ['id' => 19 , 'element' => 'Acquiring institution country code'],
		            ['id' => 22 , 'element' => 'Point of Service Entry Mode'],
		            ['id' => 25 , 'element' => 'POS Condition Code'],
			    ['id' => 26 ,  'element' => 'Point of service capture code'],
			    ['id' => 32 ,  'element' => 'Acquiring institution identification code'],
			    ['id' => 33 ,  'element' => 'Forwarding institution identification code'],
			    ['id' => 38 ,  'element' => 'Authorization identification response'],
			    ['id' => 39 ,  'element' => 'Response code'],
			    ['id' => 41 ,  'element' => 'Card acceptor terminal identification'],
			    ['id' => 42 ,  'element' => 'Card acceptor identification code'],
			    ['id' => 48 , 'element' => 'Additional data - private'],
			    ['id' => 49 , 'element' => 'Currency code, transaction'],
			    ['id' => 52 ,  'element' => 'Personal identification number data'],
		            ['id' => 55 , 'element' => 'Chip Data'],
		            ['id' => 60 , 'element' => 'Additional POS Data'],
			    ['id' => 61 ,  'element' => 'Reserved (private) 2 (e.g. CVV2/service code   transactions)'],
			    ['id' => 90 ,  'element' => 'Original data elements'],
			    ['id' => 127 ,  'element' => 'Reserved for private use 12']

		    ]);
    }
}
