te name="id" use="optional" type="xs:NMTOKEN"/>
    </xs:complexType>
  </xs:element>

  <xs:element name="notes">
    <xs:complexType mixed="false">
      <xs:sequence>
        <xs:element minOccurs="1" maxOccurs="unbounded" ref="xlf:note"/>
      </xs:sequence>
    </xs:complexType>
  </xs:element>

  <xs:element name="note">
    <xs:complexType mixed="true">
      <xs:attribute name="id" use="optional" type="xs:NMTOKEN"/>
      <xs:attribute name="appliesTo" use="optional" type="xlf:appliesTo"/>
      <xs:attribute name="category" use="optional"/>
      <xs:attribute name="priority" use="optional" type="xlf:priorityValue" default="1"/>
      <xs:anyAttribute namespace="##other" processContents="lax"/>
    </xs:complexType>
  </xs:element>

  <xs:element name="originalData">
    <xs:complexType mixed="false">
      <xs:sequence>
        <xs:element minOccurs="1" maxOccurs="unbounded" ref="xlf:data"/>
      </xs:sequence>
    </xs:complexType>
  </xs:element>

  <xs:element name="data">
    <xs:complexType mixed="true">
      <xs:sequence>
        <xs:element minOccurs="0" maxOccurs="unbounded" ref="xlf:cp"/>
      </xs:sequence>
      <xs:attribute name="id" use="required" type="xs:NMTOKEN"/>
      <xs:attribute name="dir" use="optional" type="xlf:dirValue" default="auto"/>
      <xs:attribute ref="xml:space" use="optional" fixed="preserve"/>
    </xs:complexType>
  </xs:element>

  <xs:element name="source">
    <xs:complexType mixed="true">
      <xs:group ref="xlf:inline" minOccurs="0" maxOccurs="unbounded"/>
      <xs:attribute ref="xml:lang" use="optional"/>
      <xs:attribute ref="xml:space" use="optional"/>
    </xs:complexType>
  </xs:element>

  <xs:element name="target">
    <xs:complexType mixed="true">
      <xs:group ref="xlf:inline" minOccurs="0" maxOccurs="unbounded"/>
      <xs:attribute ref="xml:lang" use="optional"/>
      <xs:attribute ref="xml:space" use="optional"/>
      <xs:attribute name="order" use="optional" type="xs:positiveInteger"/>
    </xs:complexType>
  </xs:element>

  <!-- Inline Elements -->

  <xs:element name="cp">
    <!-- Code Point -->
    <xs:complexType mixed="false">
      <xs:attribute name="hex" use="required" type="xs:hexBinary"/>
    </xs:complexType>
  </xs:element>

  <xs:element name="ph">
    <!-- Placeholder -->
    <xs:complexType mixed="false">
      <xs:attribute name="canCopy" use="optional" type="xlf:yesNo" default="yes"/>
      <xs:attribute name="canDelete" use="optional" type="xlf:yesNo" default="yes"/>
      <xs:attribute name="canReorder" use="optional" type="xlf:yesNoFirstNo" default="yes"/>
      <xs:attribute name="copyOf" use="optional" type="xs:NMTOKEN"/>
      <xs:attribute name="disp" use="optional"/>
      <xs:attribute name="equiv" use="optional"/>
      <xs:attribute name="id" use="required" type="xs:NMTOKEN"/>
      <xs:attribute name="dataRef" use="optional" type="xs:NMTOKEN"/>
      <xs:attribute name="subFlows" use="optional" type="xs:NMTOKENS"/>
      <xs:attribute name="subType" use="optional" type="xlf:userDefinedValue"/>
      <xs:attribute name="type" use="optional" type="xlf:attrType_type"/>
      <xs:anyAttribute namespace="##other" processContents="lax"/>
    </xs:complexType>
  </xs:element>

  <xs:element name="pc">
    <!-- Paired Code -->
    <xs:complexType mixed="true">
      <xs:group ref="xlf:inline" minOccurs="0" maxOccurs="unbounded"/>
      <xs:attribute name="canCopy" use="optional" type="xlf:yesNo" default="yes"/>
      <xs:attribute name="canDelete" use="optional" type="xlf:yesNo" default="yes"/>
      <xs:attribute name="canOverlap" use="optional" type="xlf:yesNo"/>
      <xs:attribute name="canReorder" use="optional" type="xlf:yesNoFirstNo" default="yes"/>
      <xs:attribute name="copyOf" use="optional" type="xs:NMTOKEN"/>
      <xs:attribute name="dispEnd" use="optional"/>
      <xs:attribute name="dispStart" use="optional"/>
      <xs:attribute name="equivEnd" use="optional"/>
      <xs:attribute name="equivStart" use="optional"/>
      <xs:attribute name="id" use="required" type="xs:NMTOKEN"/>
      <xs:attribute name="dataRefEnd" use="optional" type="xs:NMTOKEN"/>
      <xs:attribute name="dataRefStart" use="optional" type="xs:NMTOKEN"/>
      <xs:attribute name="subFlowsEnd" use="optional" type="xs:NMTOKENS"/>
      <xs:attribute name="subFlowsStart" use="optional" type="xs:NMTOKENS"/>
      <xs:attribute name="subType" use="optional" type="xlf:userDefinedValue"/>
      <xs:attribute name="type" use="optional" type="xlf:attrType_type"/>
      <xs:attribute name="dir" use="optional" type="xlf:dirValue"/>
      <xs:anyAttribute namespace="##other" processContents="lax"/>
    </xs:complexType>
  </xs:element>

  <xs:element name="sc">
    <!-- Start Code -->
    <xs:complexType mixed="false">
      <xs:attribute name="canCopy" use="optional" type="xlf:yesNo" default="yes"/>
      <xs:attribute name="canDelete" use="optional" type="xlf:yesNo" default="yes"/>
      <xs:attribute name="canOverlap" use="optional" type="xlf:yesNo" default="yes"/>
      <xs:attribute name="canReorder" use="optional" type="xlf:yesNoFirstNo" default="yes"/>
      <xs:attribute name="copyOf" use="optional" type="xs:NMTOKEN"/>
      <xs:attribute name="dataRef" use="optional" type="xs:NMTOKEN"/>
      <xs:attribute name="dir" use="optional" type="xlf:dirValue"/>
      <xs:attribute name="disp" use="optional"/>
      <xs:attribute name="equiv" use="optional"/>
      <xs:attribute name="id" use="required" type="xs:NMTOKEN"/>
      <xs:attribute name="isolated" use="optional" type="xlf:yesNo" default="no"/>
      <xs:attribute name="subFlows" use="optional" type="xs:NMTOKENS"/>
      <xs:attribute name="subType" use="optional" type="xlf:userDefinedValue"/>
      <xs:attribute name="type" use="optional" type="xlf:attrType_type"/>
      <xs:anyAttribute namespace="##other" processContents="lax"/>
    </xs:complexType>
  </xs:element>

  <xs:element name="ec">
    <!-- End Code -->
    <xs:complexType mixed="false">
      <xs:attribute name="canCopy" use="optional" type="xlf:yesNo" default="yes"/>
      <xs:attribute name="canDelete" use="optional" type="xlf:yesNo" default="yes"/>
      <xs:attribute name="canOverlap" use="optional" type="xlf:yesNo" default="yes"/>
      <xs:attribute name="canReorder" use="optional" type="xlf:yesNoFirstNo" default="yes"/>
      <xs:attribute name="copyOf" use="optional" type="xs:NMTOKEN"/>
      <xs:attribute name="dataRef" use="optional" type="xs:NMTOKEN"/>
      <xs:attribute name="dir" use="optional" type="xlf:dirValue"/>
      <xs:attribute name="disp" use="optional"/>
      <xs:attribute name="equiv" use="optional"/>
      <xs:attribute name="id" use="optional" type="xs:NMTOKEN"/>
      <xs:attribute name="isolated" use="optional" type="xlf:yesNo" default="no"/>
      <xs:attribute name="startRef" use="optional" type="xs:NMTOKEN"/>
      <xs:attribute name="subFlows" use="optional" type="xs:NMTOKENS"/>
      <xs:attribute name="subType" use="optional" type="xlf:userDefinedValue"/>
      <xs:attribute name="type" use="optional" type="xlf:attrType_type"/>
      <