nbounded">
          <xs:element ref="xlf:unit"/>
          <xs:element ref="xlf:group"/>
        </xs:choice>
      </xs:sequence>
      <xs:attribute name="id" use="required" type="xs:NMTOKEN"/>
      <xs:attribute name="canResegment" use="optional" type="xlf:yesNo" default="yes"/>
      <xs:attribute name="original" use="optional"/>
      <xs:attribute name="translate" use="optional" type="xlf:yesNo" default="yes"/>
      <xs:attribute name="srcDir" use="optional" type="xlf:dirValue" default="auto"/>
      <xs:attribute name="trgDir" use="optional" type="xlf:dirValue" default="auto"/>
      <xs:attribute ref="xml:space" use="optional"/>
      <xs:anyAttribute namespace="##other" processContents="lax"/>
    </xs:complexType>
  </xs:element>

  <xs:element name="skeleton">
    <xs:complexType mixed="true">
      <xs:sequence>
        <xs:any minOccurs="0" maxOccurs="unbounded" namespace="##other"
            processContents="lax"/>
      </xs:sequence>
      <xs:attribute name="href" use="optional"/>
    </xs:complexType>
  </xs:element>

  <xs:e