<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">
    <xs:annotation>
        <xs:documentation source="https://phpunit.de/documentation.html">
            This Schema file defines the rules by which the XML configuration file of PHPUnit 7.5 may be structured.
        </xs:documentation>
        <xs:appinfo source="https://phpunit.de/documentation.html"/>
    </xs:annotation>
    <xs:element name="phpunit" type="phpUnitType">
        <xs:annotation>
            <xs:documentation>Root Element</xs:documentation>
        </xs:annotation>
    </xs:element>
    <xs:complexType name="filtersType">
        <xs:sequence>
            <xs:element name="whitelist" type="whiteListType" minOccurs="0"/>
        </xs:sequence>
    </xs:complexType>
    <xs:complexType name="filterType">
        <xs:sequence>
            <xs:choice maxOccurs="unbounded" minOccurs="0">
                <xs:group ref="pathGroup"/>
                <xs:element name="exclude">
                    <xs:complexType>
                        <xs:group ref="pathGroup"/>
                    </xs:complexType>
                </xs:element>
            </xs:choice>
        </xs:sequence>
    </xs:complexType>
 