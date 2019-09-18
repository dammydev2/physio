<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOgPage4sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('og_page4s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sys_num');
            $table->string('rec');
            $table->string('parity')->nullable();
            $table->string('boys')->nullable();
            $table->string('girls')->nullable();
            $table->string('twins')->nullable();
            $table->string('multiple')->nullable();
            $table->string('duration')->nullable();
            $table->string('complication')->nullable();
            $table->string('comment')->nullable();
            $table->string('term')->nullable();
            $table->string('term_comment')->nullable();
            $table->string('drug')->nullable();
            $table->string('drug_comment')->nullable();
            $table->string('labour')->nu