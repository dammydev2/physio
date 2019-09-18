d xpath="@tool-id"/>
    </xsd:keyref>
    <xsd:key name="K_count-group_name">
      <xsd:selector xpath=".//xlf:count-group"/>
      <xsd:field xpath="@name"/>
    </xsd:key>
    <xsd:unique name="U_context-group_name">
      <xsd:selector xpath=".//xlf:context-group"/>
      <xsd:field xpath="@name"/>
    </xsd:unique>
    <xsd:key name="K_phase-name">
      <xsd:selector xpath="xlf:header/xlf:phase-group/xlf:phase"/>
      <xsd:field xpath="@phase-name"/>
    </xsd:key>
    <xsd:keyref name="KR_phase-name" refer="xlf:K_phase-name">
      <xsd:selector xpath=".//xlf:count|.//xlf:trans-unit|.//xlf:target|.//bin-unit|.//bin-target"/>
      <xsd:field xpath="@phase-name"/>
    </xsd:keyref>
    <xsd:unique name="U_uid">
      <xsd:selector xpath=".//xlf:external-file"/>
      <xsd:field xpath="@uid"/>
    </xsd:unique>
  </xsd:element>
  <xsd:element name="header">
    <xsd:complexType>
      <xsd:sequence>
        <xsd:element minOccurs="0" name="skl" type="xlf:ElemType_ExternalReference"/>
        <xsd:element minOccurs="0" ref="xlf:phase-group"/>
        <xsd:choice maxOccurs="unbounded" minOccurs="0">
          <xsd:element name="glossary" type="xlf:ElemType_ExternalReference"/>
          <xsd:element name="reference" type="xlf:ElemType_ExternalReference"/>
          <xsd:element ref="xlf:count-group"/>
          <xsd:element ref="xlf:note"/>
          <xs