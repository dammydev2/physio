<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaePage10bsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pae_page10bs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sys_num');
            $table->string('rec');
            $table->string('grade');
            $table->string('often');
            $table->string('long');
            $table->string('grp');
            $table->string('sp_comment');
            $table->string('whom');
            $table->string('tp_comment');
            $table->string('religious');
            $table->string('goal');
            $table->string('physio_name');
            $table->string('info');
            $table->timestamps();
        });
    }

