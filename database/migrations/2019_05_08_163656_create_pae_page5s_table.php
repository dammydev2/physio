<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOgPage8sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('og_page8s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sys_num');
            $table->string('rec');
            $table->string('spouse');
            $table->string('age');
            $table->string('occupation');
            $table->string('p_occupation');
            $table->string('apartment');
            $table->string('smoke');
            $table->string('drink');
            $table