<?php

namespace Database\Seeders;

use App\Models\ConsentOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConsentOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ConsentOption::create(
                [
                    'key'          => 'coaching-contract',
                    'version'      => 1,
                    'title'        => 'Coaching Contract',
                    'label'        => 'Click here to accept the coaching contract terms',
                    'sort_order'   => 3,
                    'enabled'      => 1,
                    'text'         => '<p>We have discussed and agreed the following:</p>
<div>The coach will provide {{ total_hours_coaching }} hours of coaching over approximately {{ expected_months }} months.</div>
<p>The purpose of workplace strategy coaching is to enable the client to explore and implement strategies to enhance their effectiveness and wellbeing at work in response to their specific challenges, strengths and situation. These strategies are designed to complement any reasonable adjustments already in place. Specific topics that may be explored include communication and presentations, time management, organisation, project planning, self-advocacy, effective reading and writing techniques, stress management and developing confidence. </p>
',
                    'is_mandatory' => true,
                    'is_current'   => true,
                    'models'       => config('filament-user-consent.models'),
                    'additional_info' => true,
                    'published_at' => now(),
                    'fields'       => [
                        [
                            'name'     => 'name',
                            'type'     => 'text',
                            'label'    => 'Emergency Contact Name',
                            'rules'    => '',
                            'options'  => '',
                            'required' => true,
                        ],
                        [
                            'name'     => 'email',
                            'type'     => 'email',
                            'label'    => 'Emergency Contact Email',
                            'rules'    => '',
                            'options'  => '',
                            'required' => true,
                        ],
                        [
                            'name'     => 'phone',
                            'type'     => 'text',
                            'label'    => 'Emergency Contact Telephone',
                            'rules'    => '',
                            'options'  => '',
                            'required' => true,
                        ],
                    ]
                ]
            );
    }
}
