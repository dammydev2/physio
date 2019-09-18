<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOgPage1sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('og_page1s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sys_num');
            $table->string('rec');
            $table->string('name');
            $table->string('physio_num');
            $table->string('dob');
            $table->string('dt');
            $table->string('marital');
            $table->string('diagnosis');
            $table->string('duration');
            $table->string('address');
            $table->string('physio');
         