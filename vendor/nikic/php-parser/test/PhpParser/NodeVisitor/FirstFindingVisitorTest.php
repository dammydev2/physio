<?xml version="1.0" encoding="UTF-8"?>
<project name="manifest" default="setup">
    <target name="setup" depends="clean,install-tools,install-dependencies"/>

    <target name="clean" unless="clean.done" description="Cleanup build artifacts">
        <delete dir="${basedir}/tools"/>
        <delete dir="${basedir}/vendor"/>
        <delete file="${basedir}/src/autoload.php"/>

        <property name="clean.done" value="true"/>
    </target>

    <target name="prepare" unless="prepare.done" depends="clean" description="Prepare for build">
        <property name="prepare.done" value="true"/>
    </target>

    <target name="install-dependencies" unless="dependencies-installed" depends="-dependencies-installed" description="Install dependencies with Composer">
        <exec executable="composer" taskname="composer">
            <env key="COMPOSER_DISABLE_XDEBUG_WARN" value="1"/>
            <arg value="update"/>
            <arg value="--no-interaction"/>
            <arg value="--no-progress"/>
            <arg value="--no-ansi"/>
            <arg value="--no-suggest"/>
            <arg value="--optimize-autoloader"/>
            <arg value="--prefer-stable"/>
        </exec>
    </target>

    <tar