<?xml version="1.0" encoding="UTF-8"?>
<project name="version" default="setup">
    <target name="setup" depends="clean,install-tools,generate-autoloader"/>

    <target name="clean" unless="clean.done" description="Cleanup build artifacts">
        <delete dir="${basedir}/tools"/>
        <delete dir="${basedir}/vendor"/>
        <delete file="${basedir}/src/autoload.php"/>

        <property name="clean.done" value="true"/>
    </target>

    <target name="prepare" unless="prepare.done" depends="clean" description="Prepare for build">
        <property name="prepare.done" value="true"/>
    </target>

    <target name="-tools-installed">
        <available file="${basedir}/tools" property="tools-installed" type="dir"/>
    </target>

    <target name="install-tools" unless="tools-installed" depends="-tools-installed" description="Install tools with Phive">
        <exec executable="phive" taskname="phive">
            <a