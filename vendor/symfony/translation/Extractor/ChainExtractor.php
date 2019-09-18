<?xml version="1.0" encoding="UTF-8"?>
<!--

    XLIFF Version 2.0
    OASIS Standard
    05 August 2014
    Copyright (c) OASIS Open 2014. All rights reserved.
    Source: http://docs.oasis-open.org/xliff/xliff-core/v2.0/os/schemas/
     -->
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema"
    elementFormDefault="qualified"
    xmlns:xlf="urn:oasis:names:tc:xliff:document:2.0"
    targetNamespace="urn:oasis:names:tc:xliff:document:2.0">

  <!-- Import -->

  <xs:import namespace="http://www.w3.org/XML/1998/namespace"
      schemaLocation="informativeCopiesOf3rdPartySchemas/w3c/xml.xsd"/>

  <!-- Element Group -->

  <xs:group name="inline">
    <xs:choice>
      <xs:element ref="xlf:cp"/>
      <xs:element ref="xlf:ph"/>
      <xs:element ref="xlf:pc"/>
      <xs:element ref="xlf:sc"/>
      <xs:element ref="xlf:ec"/>
      <xs:element ref="xlf:mrk"/>
      <xs:element ref="xlf:sm"/>
      <xs:element ref="xlf:em"/>
    </xs:choice>
  </xs:group>

  <!-- Attribute Types -->

  <xs:simpleType name="yesNo">
    <xs:restriction base="xs:string">
      <xs:enumeration value="yes"/>
      <xs:enumeration value="no"/>
    </xs:restriction>
  </xs:simpleType>

  <xs:simpleType name="yesNoFirstNo">
    <xs:restriction base="xs:string">
      <xs:enumeration value="yes"/>
      <xs:enumeration value="firstNo"/>
      <xs:enumeration value="no"/>
    </xs:restriction>
  </xs:simpleTy