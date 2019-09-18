nOccurs="0" ref="xlf:seg-source"/>
        <xsd:element maxOccurs="1" ref="xlf:target"/>
        <xsd:element maxOccurs="unbounded" minOccurs="0" ref="xlf:context-group"/>
        <xsd:element maxOccurs="unbounded" minOccurs="0" ref="xlf:note"/>
        <xsd:any maxOccurs="unbounded" minOccurs="0" namespace="##other" processContents="strict"/>
      </xsd:sequence>
      <xsd:attribute name="match-quality" type="xsd:string" use="optional"/>
      <xsd:attribute name="tool-id" type="xsd:string" use="optional"/>
      <xsd:attribute name="crc" type="xsd:NMTOKEN" use="optional"/>
      <xsd:attribute ref="xml:lang" use="optional"/>
      <xsd:attribute name="origin" type="xsd:string" use="optional"/>
      <xsd:attribute name="datatype" type="xlf:AttrType_datatype" use="optional"/>
      <xsd:attribute default="default" ref="xml:space" use="optional"/>
      <xsd:attribute name="restype" type="xlf:AttrType_restype" use="optional"/>
      <xsd:attribute name="resname" type="xsd:string" use="optional"/>
      <xsd:attribute name="extradata" type="xsd:string" use="optional"/>
      <xsd:attribute name="extype" type="xsd:string" use="optional"/>
      <xsd:attribute name="help-id" type="xsd:NMTOKEN" use="optional"/>
      <xsd:attribute name="menu" type="xsd:string" use="optional"/>
      <xsd:attribute name="menu-option" type="xsd:string" use="optional"/>
      <xsd:attribute name="menu-name" type="xsd:string" use="optional"/>
      <xsd:attribute name="mid" type="xsd:NMTOKEN" use="optional"/>
      <xsd:attribute name="coord" type="xlf:AttrType_Coordinates" use="optional"/>
   