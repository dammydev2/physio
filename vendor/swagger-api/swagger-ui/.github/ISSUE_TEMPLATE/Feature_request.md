his.index=r,this.operation=o,this.tree=i}return n(t,e),t}(Error);t.PatchError=s},function(e,t,n){var r=n(49),o=n(155),i=n(72),a=n(115),u=n(917);e.exports=function(e,t){var n=1==e,s=2==e,l=3==e,c=4==e,f=6==e,p=5==e||f,d=t||u;return function(t,u,h){for(var v,m,g=i(t),y=o(g),b=r(u,h,3),_=a(y.length),w=0,E=n?d(t,_):s?d(t,0):void 0;_>w;w++)if((p||w in y)&&(m=b(v=y[w],w,g),e))if(n)E[w]=m;else if(m)switch(e){case 3:return!0;case 5:return v;case 6:return w;case 2:E.push(v)}else if(c)return!1;return f?-1:l||c?c:E}}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.authorizeRequest=t.authorizeAccessCodeWithBasicAuthentication=t.authorizeAccessCodeWithFormParams=t.authorizeApplication=t.authorizePassword=t.preAuthorizeImplicit=t.CONFIGURE_AUTH=t.VALIDATE=t.AUTHORIZE_OAUTH2=t.PRE_AUTHORIZE_OAUTH2=t.LOGOUT=t.AUTHORIZE=t.SHOW_AUTH_POPUP=void 0;var r=l(n(45)