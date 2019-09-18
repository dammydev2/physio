<?xml version='1.0'?>
<grammar xmlns="http://relaxng.org/ns/structure/1.0"
  datatypeLibrary="http://www.w3.org/2001/XMLSchema-datatypes"
  ns="http://www.iana.org/assignments">

  <define name="registryMeta">
    <interleave>
      <attribute name="id"><data type="ID"/></attribute>
      <optional><element name="title"><ref name="text_with_references"/></element></optional>
      <optional><element name="created"><ref name="genericDate"/></element></optional>
      <optional><element name="updated"><data type="date"/></element></optional>
      <optional><element name="registration_rule"><ref
            name="text_with_references"/></element></optional>
      <optional><element name="expert"><text/></element></optional>
      <optional><element name="description"><ref name="text_with_references"/></element></optional>
      <zeroOrMore><element name="note"><ref name="text_with_references"/></element></zeroOrMore>
      <ref name="references"/>
      <optional><element name="hide"><empty/></element></optional>
      <zeroOrMore><element name="category"><text/></element></zeroOrMore>
      <zeroOrMore><ref name="range"/></zeroOrMore>
      <optional><ref name="file"/></optional>
    </interleave>
  </define>

  <define name="range">
    <element name="range">
       <interleave>
	  <element name="value"><text/></element>
	  <optional><element name="hex"><text/></element></optional>
	  <element name="registration_rule"><ref name="text_with_references"/></element>
	  <optional><element name="note"><ref name="text_with_references"/></element></optional>
	  <optional><ref name="xref"/></optional>
       </interleave>
    </element>
  </define>

  <define name="people">
    <element name="people">
      <zeroOrMore>
        <element name="person">
          <attribute name="id"><data type="ID"/></attribute>
          <optional><element name="name"><text/></element></optional>
          <optional><element name="org"><text/></element></optional>
          <zeroOrMore><element name="uri"><data type="anyURI"/></element></zeroOrMore>
       