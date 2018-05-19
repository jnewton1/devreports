<?php

namespace Tests\Feature;

use App\Report;
use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->actingAs($this->user);
    }

    /**
     * @test
     * @group feature
     * @group reports
     * @group controllers
     * @return void
     */
    public function shouldReturnPaginatedListOfReports()
    {
        factory(Report::class, 20)->create([
            'user_id' => $this->user,
        ]);

        $response = $this->get(route('reports.index'));

        $response->assertSuccessful();
        $response->assertViewHas('reports');

        $data = $this->getResponseData($response,'reports');
        $this->assertEquals(LengthAwarePaginator::class, get_class($data));
        $this->assertEquals(12, $data->count());
        $this->assertEquals(20, $data->total());
    }
}
