<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOgPage6sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('og_page6s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sys_num');
            $table->string('rec');
            $table->string('moverment')->nullable();
            $table->string('list')->nullable();
            $table->string('breathing')->nullable();
            $table->string('cough')->nullable();
            $table->string('sputum')->nullable();
            $table->string('describe')->nullable();
            $table->string('