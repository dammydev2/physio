<?xml version="1.0" encoding="UTF-8"?>
<phar xmlns="https://phar.io/xml/manifest/1.0">
    <contains name="phpunit/phpunit" version="5.6.5" type="application"/>
    <copyright>
        <author name="Sebastian Bergmann" email="sebastian@phpunit.de"/>
        <license type="BSD-3-Clause" url="https://github.com/sebastianbergmann/phpunit/blob/master/LICENSE"/>
    </copyright>
    <requires>
        <!-- constraint on next line should be ^5.6 || ^7.0 -->
        <php version="^7.0">
            <ext name="dom"/>
            <ext name="json"/>
            <ext name="mbstring"/>
            <ext name="xml"/>
            <ext name="libxml"/>
        </php>
    </requires>
    <bundles>
        <component name="doctrine/instantiator" version="1.0.5"/>
        <component name="myclabs/deep-copy" version="1.5.5"/>
        <component name="phpdocumentor/reflection-common" version="1.0"/>
        <component name="phpdocumentor/reflection-docblock" version="3.1.1"/>
        <component name="phpdocumentor/type-resolver" version="0.2"/>
        <component nam