<?php

use App\Service\Error\DisplayableExceptionInterface;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
    private $service;

    protected function setUp(): void
    {
        $this->service = container()->service;
        $this->service->reset();
    }

    public function testStart()
    {
        $this->service->start("team1", "team2");
        $games = $this->service->summary();
        $this->assertCount(1, $games);
        $this->assertEquals(0, $games[0]->homeScore);
        $this->assertEquals(0, $games[0]->awayScore);
        $this->assertEquals("team1", $games[0]->homeTeam->name);
        $this->assertEquals("team2", $games[0]->awayTeam->name);
    }

    public function testStartSame()
    {
        $this->expectException(DisplayableExceptionInterface::class);
        $this->service->start('team1', 'team1');
    }

    public function testStartUsed()
    {
        $this->expectException(DisplayableExceptionInterface::class);
        $this->service->start('team1', 'team2');
        $this->service->start('team1', 'team3');
    }

    public function testNormalUpdate()
    {
        $this->service->start('1', '2');
        $this->service->update('1', '2', 3, 4);
        [$game] = $this->service->summary();
        $this->assertEquals(3, $game->homeScore);
        $this->assertEquals(4, $game->awayScore);
    }

    public function testSorted()
    {
        $this->service->start('1', '2');
        $this->service->start('3', '4');
        $this->service->start('5', '6');
        $this->service->start('7', '8');
        $this->service->update('1', '2', 1,2);
        $this->service->update('3', '4', 3,44);
        $this->service->update('5', '6', 4,5);
        $games = $this->service->summary();
        $this->assertEquals(3, $games[0]->homeScore);
    }

    public function testEmpty()
    {
        $this->assertEmpty($this->service->summary());
    }

    public function testFinish()
    {
        $this->service->start(1,2);
        $this->service->start(3,4);
        $this->service->finish(1,2);
        $this->assertEquals('3', $this->service->summary()[0]->homeTeam->name);
    }
}