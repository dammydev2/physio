<?xml version="1.0" encoding="UTF-8"?>
<project default="build">
    <!-- Set executables according to OS -->
    <condition property="phpunit" value="${basedir}/vendor/bin/phpunit.bat" else="${basedir}/vendor/bin/phpunit">
        <os family="windows" />
    </condition>

    <condition property="phpcs" value="${basedir}/vendor/bin/phpcs.bat" else="${basedir}/vendor/bin/phpcs">
        <os family="windows" />
    </condition>

    <condition property="parallel-lint" value="${basedir}/vendor/bin/parallel-lint.bat" else="${basedir}/vendor/bin/parallel-lint">
        <os family="windows" />
    </condition>

    <condition property="var-dump-check" value="${basedir}/vendor/bin/var-dump-check.bat" else="${basedir}/vendor/bin/var-dump-check">
        <os family="windows"/>
    </condition>

    <!-- Use colors in output can be disabled when calling ant with -Duse-colors=false -->
    <property name="use-colors" value="true" />

    <condition property="colors-arg.color" value="--colors" else="">
        <equals arg1="${use-colors}" arg2="true" />
    </condition>

    <condition property="colors-arg.no-colors" value="" else="--no-colors">
        <equals arg1="${use-colors}" arg2="true" />
    </condition>
