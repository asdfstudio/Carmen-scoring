<?php

use Illuminate\Database\Seeder;

class CriterionSheetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Advanced
				$criteria = [
					1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24
				];

				$rows = [];

				foreach($criteria as $criterion)
				{
					$rows[] = ['sheet_id' => 1, 'criterion_id' => $criterion];
				}

				DB::table('criterion_sheet')->insert($rows);


				// Novice
				$criteria = [
					39,2,40,6,7,8,10,25,36,26,37,19,38,22
				];

				$rows = [];

				foreach($criteria as $criterion)
				{
					$rows[] = ['sheet_id' => 2, 'criterion_id' => $criterion];
				}

				DB::table('criterion_sheet')->insert($rows);


        // Advanced with combo
				$criteria = [
					1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,27,28
				];

				$rows = [];

				foreach($criteria as $criterion)
				{
					$rows[] = ['sheet_id' => 3, 'criterion_id' => $criterion];
				}

				DB::table('criterion_sheet')->insert($rows);


				// Novice with combo
				$criteria = [
					39,2,40,6,7,8,10,25,36,26,37,19,38,22,27,28
				];

				$rows = [];

				foreach($criteria as $criterion)
				{
					$rows[] = ['sheet_id' => 4, 'criterion_id' => $criterion];
				}

				DB::table('criterion_sheet')->insert($rows);


        // Combo only
				$criteria = [
					29,30,31,32,33,34,35
				];

				$rows = [];

				foreach($criteria as $criterion)
				{
					$rows[] = ['sheet_id' => 5, 'criterion_id' => $criterion];
				}

				DB::table('criterion_sheet')->insert($rows);
    }
}
