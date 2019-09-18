
      <xsd:documentation>Values for the attribute 'coord'.</xsd:documentation>
    </xsd:annotation>
    <xsd:restriction base="xsd:string">
      <xsd:pattern value="(-?\d+|#);(-?\d+|#);(-?\d+|#);(-?\d+|#)"/>
    </xsd:restriction>
  </xsd:simpleType>
  <xsd:simpleType name="AttrType_Version">
    <xsd:annotation>
      <xsd:documentation>Version values: 1.0 and 1.1 are allowed for backward compatibility.</xsd:documentation>
    </xsd:annotation>
    <xsd:restriction base="xsd:string">
      <xsd:enumeration value="1.2"/>
      <xsd:enumeration value="1.1"/>
      <xsd:enumeration value="1.0"/>
    </xsd:restriction>
  </xsd:simpleType>
  <!-- Groups -->
  <xsd:group name="ElemGroup_TextContent">
    <xsd:choice>
      <xsd:element ref="xlf:g"/>
      <xsd:element ref="xlf:bpt"/>
      <xsd:element ref="xlf:ept"/>
      <xsd:element ref="xlf:ph"/>
      <xsd:element ref="xlf:it"/>
      <xsd:element ref="xlf:mrk"/>
      <xsd:element ref="xlf:x"/>
      <xsd:element ref="xlf:bx"/>
      <xsd:element ref="xlf:ex"/>
    </xsd:choice>
  </xsd:group>
  <xsd:attributeGroup name="AttrGroup_TextContent">
    <xsd:attribute name="id" type="xsd:string" use="required"/>
    <xsd:attribute name="xid" type="xsd:string" use="optional"/>
    <xsd:attribute name="equiv-text" type="xsd:string" use="optional"/>
    <xsd:anyAttribute namespace="##other" processContents="strict"/>
  </xsd:attributeGroup>
  <!-- XLIFF Structure -->
  <xsd:element name="xliff">
    <xsd:complexType>
      <xsd:sequence maxOccurs="unbounded">
        <xsd:any maxOccurs="unbo