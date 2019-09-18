striction>
  </xsd:simpleType>
  <xsd:simpleType name="count-typeValueList">
    <xsd:annotation>
      <xsd:documentation>Values for the attribute 'count-type'.</xsd:documentation>
    </xsd:annotation>
    <xsd:restriction base="xsd:NMTOKEN">
      <xsd:enumeration value="num-usages">
        <xsd:annotation>
          <xsd:documentation>Indicates the count units are items that are used X times in a certain context; example: this is a reusable text unit which is used 42 times in other texts.</xsd:documentation>
        </xsd:annotation>
      </xsd:enumeration>
      <xsd:enumeration value="repetition">
        <xsd:annotation>
          <xsd:documentation>Indicates the count units are translation units existing already in the same document.</xsd:documentation>
        </xsd:annotation>
      </xsd:enumeration>
      <xsd:enumeration value="total">
        <xsd:annotation>
          <xsd:documentation>Indicates a total count.</xsd:documentation>
        </xsd:annotation>
      </xsd:enumeration>
    </xsd:restriction>
  </xsd:simpleType>
  <xsd:simpleType name="InlineDelimitersValueList">
    <xsd:annotation>
      <xsd:documentation>Values for the attribute 'ctype' when used other elements than &lt;ph&gt; or &lt;x&gt;.</xsd:documentation>
    </xsd:annotation>
    <xsd:restriction base="xsd:NMTOKEN">
      <xsd:enumeration value="bold">
        <xsd:annotation>
          <xsd:documentation>Indicates a run of bolded text.</xsd:documentation>
        </xsd:annotation>
      </xsd:enumeration>
      <xsd:enumeration value="italic">
        <xsd:annotation>
          <xsd:documentation>Indicates a run of text in italics.</xsd:documentation>
        </xsd:annotation>
      </xsd:enumeration>
      <xsd:enumeration value="underlined">
        <xsd:annotation>
          <xsd:documentation>Indicates a run of underlined text.</xsd:documentation>
        </xsd:annotation>
      </xsd:enumeration>
      <xsd:enumeration value="link">
        <xsd:annotation>
          <xsd:documentation>Indicates a run of hyper-text.</xsd:documentation>
        </xsd:annotation>
      </xsd:enumeration>
    </xsd:restriction>
  </xsd:simpleType>
  <xsd:simpleType name="InlinePlaceholdersValueList">
    <xsd:annotation>
      <xsd:documentation>Values for the attribute 'ctype' when used with &lt;ph&gt; or &lt;x&gt;.</xsd:documentation>
    </xsd:annotation>
    <xsd:restriction base="xsd:NMTOKEN">
      <xsd:enumeration value="image">
        <xsd:annotation>
          <xsd:documentation>Indicates a inline image.</xsd:documentation>
        </xsd:annotation>
      </xsd:enumeration>
      <xsd:enumeration value="pb">
        <xsd:annotation>
          <xsd:documentation>Indicates a page break.</xsd:documentation>
        </xsd:annotation>
      </xsd:enumeration>
      <xsd:enumeration value="lb">
        <xsd:annotation>
          <xsd:documentation>Indicates a line break.</xsd:documentation>
        </xsd:annotation>
      </xsd:enumeration>
    </xsd:restriction>
  </xsd:simpleType>
  <xsd:simpleType name="mime-typeValueList">
    <xsd:restriction base="xsd:string">
      <xsd:pattern value="(text|multipart|message|application|image|audio|video|model)(/.+)*"/>
    </xsd:restriction>
  </xsd:simpleType>
  <xsd:simpleType name="datatypeValueList">
    <xsd:annotation>
      <xsd:documentation>Values for the attribute 'datatype'.</xsd:documentation>
    </xsd:annotation>
    <xsd:restriction base="xsd:NMTOKEN">
      <xsd:enumeration value="asp">
        <xsd:annotation>
          <xsd:documentation>Indicates Active Server Page data.</xsd:documentation>
        </xsd:annotation>
      </xsd:enumeration>
      <xsd:enumeration value="c">
        <xsd:annotation>
          <xsd:documentation>Indicates C source file data.</xsd:documentation>
        </xsd:annotation>
      </xsd:enumeration>
      <xsd:enumeration value="cdf">
        <xsd:annotation>
          <xsd:documentation>Indicates Channel Definition Format (CDF) data.</xsd:documentation>
        </xsd:annotation>
      </xsd:enumeration>
      <xsd:enumeration value="cfm">
        <xsd:annotation>
          <xsd:documentation>Indicates ColdFusion data.</xsd:documentation>
        </xsd:annotation>
      </xsd:enumeration>
      <xsd:enumeration value="cpp">
        <xsd:annotation>
          <xsd:documentation>Indicates C++ source file data.</xsd:documentation>
        </xsd:annotation>
      </xsd:enumeration>
      <xsd:enumeration value="csharp">
        <xsd:annotation>
          <xsd:documentation>Indicates C-Sharp data.</xsd:documentation>
        </xsd:annotation>
      </xsd:enumeration>
      <xsd:enumeration value="cstring">
        <xsd:annotation>
          <xsd:documentation>Indicates