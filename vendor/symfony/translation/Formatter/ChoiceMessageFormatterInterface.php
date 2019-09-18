/TR/xmlbase/</a>
      for information about this attribute.
     </p>

    </div>
   </xs:documentation>
  </xs:annotation>
 </xs:attribute>
 
 <xs:attribute name="id" type="xs:ID">
  <xs:annotation>
   <xs:documentation>
    <div>
     
      <h3>id (as an attribute name)</h3> 
      <p>

       denotes an attribute whose value
       should be interpreted as if declared to be of type ID.
       This name is reserved by virtue of its definition in the
       xml:id specification.</p>
     
     <p>
      See <a
      href="http://www.w3.org/TR/xml-id/">http://www.w3.org/TR/xml-id/</a>
      for information about this attribute.
     </p>
    </div>
   </xs:documentation>
  </xs:annotation>

 </xs:attribute>

 <xs:attributeGroup name="specialAttrs">
  <xs:attribute ref="xml:base"/>
  <xs:attribute ref="xml:lang"/>
  <xs:attribute ref="xml:space"/>
  <xs:attribute ref="xml:id"/>
 </xs:attributeGroup>

 <xs:annotation>

  <xs:documentation>
   <div>
   
    <h3>Father (in any context at all)</h3> 

    <d