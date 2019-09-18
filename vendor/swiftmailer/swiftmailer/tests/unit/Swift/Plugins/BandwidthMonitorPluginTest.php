<?php

class Swift_Transport_LoadBalancedTransportTest extends \SwiftMailerTestCase
{
    public function testEachTransportIsUsedInTurn()
    {
        $message1 = $this->getMockery('Swift_Mime_SimpleMessage');
        $message2 = $this->getMockery('Swift_Mime_SimpleMessage');
        $t1 = $this->getMockery('Swift_Transport');
        $t2 = $this->getMockery('Swift_Transport');
        $connectionState1 = false;
        $connectionState2 = false;

        $testCase = $this;
        $t1->shouldReceive('isStarted')
           ->zeroOrMoreTimes()
           ->andReturnUsing(function () use (&$connectionState1) {
               return $connectionState1;
           });
        $t1->shouldReceive('start')
           ->once()
           ->andReturnUsing(function () use (&$connectionState1) {
               if (!$connectionState1) {
                   $connectionState1 = true;
               }
           });
        $t1->shouldReceive('send')
           ->once()
           ->with($message1, \Mockery::any())
           ->andReturnUsing(function () use (&$connectionState1, $testCase) {
               if ($connectionState1) {
                   return 1;
               }
               $testCase->fail();
           });
        $t1->shouldReceive('send')
           ->never()
           ->with($message2, \Mockery::any());

        $t2->shouldReceive('isStarted')
           ->zeroOrMoreTimes()
           ->andReturnUsing(function () use (&$connectionState2) {
               return $connectionState2;
           });
        $t2->shouldReceive('start')
           ->once()
           ->andReturnUsing(function () use (&$connectionState2) {
               if (!$connectionState2) {
                   $connectionState2 = true;
               }
           });
        $t2->shouldReceive('send')
           ->once()
           ->with($message2, \Mockery::any())
           ->andReturnUsing(function () use (&$connectionState2, $testCase) {
               if ($connectionState2) {
                   return 1;
               }
               $testCase->fail();
           });
        $t2->shouldReceive('send')
           ->never()
           ->with($message1, \Mockery::any());

        $transport = $this->getTransport([$t1, $t2]);
        $transport->start();
        $this->assertEquals(1, $transport->send($message1));
        $this->assertEquals(1, $transport->send($message2));
    }

    public function testTransportsAreReusedInRotatingFashion()
    {
        $message1 = $this->getMockery('Swift_Mime_SimpleMessage');
        $message2 = $this->getMockery('Swift_Mime_SimpleMessage');
        $message3 = $this->getMockery('Swift_Mime_SimpleMessage');
        $message4 = $this->getMockery('Swift_Mime_SimpleMessage');
        $t1 = $this->getMockery('Swift_Transport');
        $t2 = $this->getMockery('Swift_Transport');
        $connectionState1 = false;
        $connectionState2 = false;

        $testCase = $this;
        $t1->shouldReceive('isStarted')
           ->zeroOrMoreTimes()
           ->andReturnUsing(function () use (&$connectionState1) {
               return $connectionState1;
           });
        $t1->shouldReceive('start')
           ->once()
           ->andReturnUsing(function () use (&$connectionState1) {
               if (!$connectionState1) {
                   $connectionState1 = true;
               }
           });
        $t1->shouldReceive('send')
           ->once()
           ->with($message1, \Mockery::any())
           ->andReturnUsing(function () use (&$connectionState1, $testCase) {
               if ($connectionState1) {
                   return 1;
               }
               $testCase->fail();
           });
        $t1->shouldReceive('send')
           ->never()
           ->with($message2, \Mockery::any());
        $t1->shouldReceive('send')
           ->once()
           ->with($message3, \Mockery::any())
           ->andReturnUsing(function () use (&$connectionState1, $testCase) {
               if ($connectionState1) {
                   return 1;
               }
               $testCase->fail();
           });
        $t1->shouldReceive('send')
           ->never()
           ->with($message4