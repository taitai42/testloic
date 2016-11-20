<?php

use App\Todo;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class TodoTest
 */
class TodoTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * test get by status true.
     *
     * @return void
     */
    public function testGetByStatusTrue()
    {
        $status = 1;

        $this->getByStatus($status);

    }

    /**
     * test get by status false.
     *
     * @return void
     */
    public function testGetByStatusFalse()
    {
        $status = 0;

        $this->getByStatus($status);

    }

    /**
     * Test get by status with no data
     */
    public function testGetByStatusNoData()
    {
        $status = 0;

        factory(\App\Todo::class, 10)->create(['done' => true]); // create method of factory insert the created records in DB

        $this->get("/api/todo/status/$status");
        $result = json_decode($this->response->content(), true);

        $this->assertEquals($result, []);

    }

    /**
     * perform the test of the status.
     * @param $status
     */
    public function getByStatus($status, $number = 10)
    {
        $todos = factory(\App\Todo::class, $number)->create();

        $done = array_filter($todos->toArray(), function ($item) use ($status) {
            return $item['done'] == $status;
        });

        $this->get("/api/todo/status/$status");
        $result = json_decode($this->response->content(), true);
        $this->assertEquals($result, array_values($done));
    }


    /**
     * test by id with an id which exist in db
     */
    public function testGetByIdExist()
    {
        $todos = factory(\App\Todo::class, 10)->create();

        $random = $todos->random();
        $this->get("/api/todo/{$random->id}")->seeJson(['id' => $random->id]);
    }

    /**
     * test by id with an id which doesn't exist
     */
    public function testGetIdNotExist()
    {

        $todos = factory(\App\Todo::class, 10)->create();

        $max = $todos->max('id') + 1;
        $this->get("/api/todo/{$max}")->seeJsonEquals([]);
    }

    /**
     * test post with valid data
     */
    public function testPostTask()
    {
        $todo = factory(\App\Todo::class)->make(); // make method doesn't insert data in DB, it just build the array of them

        $this->post('/api/todo', $todo->toArray())->seeStatusCode('201')->seeJsonStructure([
            'id', 'name', 'created_at', 'updated_at', 'done'
        ]);
    }


    /**
     * test post missing the done parameters
     */
    public function testPostTaskMissingParameters()
    {
        $todo = ['name' => 'test'];

        $this->post('/api/todo', $todo)->seeStatusCode('422');
    }

    /**
     * test post with an invalid done parameter
     */
    public function testPostTaskInvalidParameter()
    {
        $todo = factory(\App\Todo::class)->make(['done' => 'test']);

        $this->post('/api/todo', $todo->toArray())->seeStatusCode('422');
    }

    /**
     * test edit with valid id and data
     */
    public function testEditTask()
    {
        $todo = factory(\App\Todo::class)->make();

        $this->post('/api/todo', $todo->toArray())->seeStatusCode('201');

        $todo = json_decode($this->response->content());

        $newdone = intval($todo->done == 0);

        $this->put("/api/todo/{$todo->id}", ['done' =>  $newdone ])->seeStatusCode(200)->seeJson(['done' => $newdone]);
    }

    /**
     * test edit on an id which doesn't exist
     */
    public function testEditTaskNotExist()
    {
        $todo = factory(\App\Todo::class)->make();

        $this->post('/api/todo', $todo->toArray())->seeStatusCode('201');

        $todo = json_decode($this->response->content());

        $newdone = intval($todo->done == 0);

        $todo->id += 1;

        $this->put("/api/todo/{$todo->id}", ['done' =>  $newdone ])->seeStatusCode(404);
    }

    /**
     * test edit with an invalid done parameter
     */
    public function testEditTaskInvalidData()
    {
        $todo = factory(\App\Todo::class)->make();

        $this->post('/api/todo', $todo->toArray())->seeStatusCode('201');

        $todo = json_decode($this->response->content());

        $newdone = "toto";

        $this->put("/api/todo/{$todo->id}", ['done' =>  $newdone ])->seeStatusCode(422);
    }

    /**
     * test delete on valid id
     */
    public function testDeleteTask()
    {
        $todo = factory(\App\Todo::class)->make();

        $this->post('/api/todo', $todo->toArray())->seeStatusCode('201');

        $todo = json_decode($this->response->content());

        $this->delete("/api/todo/{$todo->id}")->seeStatusCode(204);
    }

    /**
     * test delete on id which doesn't exist
     */
    public function testDeleteTaskNotExist()
    {
        $incase = Todo::max('id') + 1; // in case we have data in db

        $this->delete("/api/todo/$incase")->seeStatusCode(404);
    }
}
