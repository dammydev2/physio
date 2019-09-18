epends,defects"/>
            <xs:enumeration value="random"/>
            <xs:enumeration value="reverse"/>
            <xs:enumeration value="depends,random"/>
            <xs:enumeration value="depends,reverse"/>
            <xs:enumeration value="depends,duration"/>
        </xs:restriction>
    </xs:simpleType>
    <xs:complexType name="fileFilterType">
        <xs:simpleContent>
            <xs:extension base="xs:anyURI">
                <xs:attributeGroup ref="phpVersionGroup"/>
            </xs:extension>
        </xs:simpleContent>
    </xs:complexType>
    <xs:attributeGroup name="phpVersionGroup">
        <xs:attribute name="phpVersion" type="xs:string" default="5.3.0"/>
        <xs:attribute name="phpVersionOperator" type="xs:string" default="&gt;="/>
    </xs:attributeGroup>
    <xs:complexType name="phpType">
        <xs:sequence>
            <xs:choice maxOccurs="unbounded">
                <xs:element name="includePath" type="xs:anyURI" minOccurs="0" maxOccurs="unbounded"/>
                <xs:element name="ini" type="namedValueType" minOccurs="0" maxOccurs="unbounded"/>
                <xs:element name="const" type="namedValueType" minOccurs="0" maxOccurs="unbounded"/>
                <xs:element name="var" type="namedValueType" minOccurs="0" maxOccurs="unbounded"/>
                <xs:element name="env" type="namedValueType" minOccurs="0" maxOccurs="unbounded"/>
                <xs:element name="post" type="namedValueType" minOccurs="0" maxOccurs="unbounded"/>
                <xs:element name="get" type="namedValueType" minOccurs="0" maxOccurs="unbounded"/>
                <xs:element name="cookie" type="namedValueType" minOccurs="0" maxOccurs="unbounded"/>
                <xs:element name="server" type="namedValueType" minOccurs="0" maxOccurs="unbounded"/>
                <xs:element name="files" type="namedValueType" minOccurs="0" maxOccurs="unbounded"/>
                <xs:element name="request" type="namedValueType" minOccurs="0" maxOccurs="unbounded"/>
            </xs:choice>
        </xs:sequence>
    </xs:complexType>
    <xs:complexType name="namedValueType">
        <xs:attribute name="name" use="required" type="xs:string"/>
        <xs:attribute name="value" use="required" type="xs:anySimpleType"/>
        <xs:attribute name="verbatim" use="optional" type="xs:boolean"/>
        <xs:attribute name="force" use="optional" type="xs:boolean"/>
    </xs:complexType>
    <xs:complexType name="phpUnitType">
        <xs:annotation>
            <xs:documentation>The main type specifying the document structure</xs:documentation>
        </xs:annotation>
        <xs:group ref="configGroup"/>
        <xs:attributeGroup ref="configAttributeGroup"/>
    </xs:complexType>
    <xs:attributeGroup name="configAttributeGroup">
        <xs:attribute name="backupGlobals" type="xs:boolean" default="false"/>
        <xs:attribute name="backupStaticAttributes" type="xs:boolean" default="false"/>
        <xs