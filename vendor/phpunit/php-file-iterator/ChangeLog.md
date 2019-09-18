har-autoload.php.in" tofile="${basedir}/build/binary-phar-autoload.php"/>
        <replace file="${basedir}/build/binary-phar-autoload.php" token="X.Y.Z" value="${_version}"/>

        <exec executable="${basedir}/tools/phpab" taskname="phpab" failonerror="true">
            <arg value="--all" />
            <arg value="--nolower" />
            <arg value="--static" />
            <arg value="--phar" />
            <arg value="--hash" />
            <arg value="SHA-1" />
            <arg value="--output" />
            <arg path="${basedir}/build/phpunit-${_version}.phar" />
            <arg value="--template" />
            <arg path="${basedir}/build/binary-phar-autoload.php" />
            <arg path="${basedir}/build/phar" />
        </exec>

        <chmod file="${basedir}/build/phpunit-${_version}.phar" perm="ugo+rx"/>

        <delete dir="${basedir}/build/phar"/>
        <delete file="${basedir}/build/binary-phar-autoload.php"/>
    </target>

    <target name="-phar-determine-version">
        <exec executable="${basedir}/build/version.php" outputproperty="version" failonerror="true" />
    </target>

    <target name="generate-project-documentation" depends="-phploc,-checkstyle,-phpunit">
        <exec executable="${basedir}/tools/phpdox" dir="${basedir}/build" taskname="phpdox"/>
    </target>

    <target name="update-tools">
        <exec executable="phive">
            <arg value="--no-progress"/>
            <arg value="update"/>
        </exec>

        <exec executable="${basedir}/tools/composer">
            <arg value="self-update"/>
        </exec>
    </target>

    <target name="-phploc" depends="prepare">
        <exec executable="${basedir}/tools/phploc" output="/dev/null" taskname="phploc">
            <arg value="--count-tests"/>
            <arg value="--log-xml"/>
            <arg path="${basedir}/build/logfiles/phploc.xml"/>
            <arg path="${basedir}/src"/>
            <arg path="${basedir}/tests"/>
        </exec>
    </target>

    <target name="-checkstyle" depends="prepare">
        <exec executable="${basedir}/tools/php-cs-fixer" output="${basedir}/build/logfiles/checkstyle.xml" error="/dev/null" taskname="php-cs-fixer">
            <arg value="--diff"/>
            <arg value="--dry-run"/>
            <arg value="fix"/>
            <arg 