ports=n(691)()?Array.from:n(692)},function(e,t,n){"use strict";var r=n(705),o=n(67),i=n(82),a=Array.prototype.indexOf,u=Object.prototype.hasOwnProperty,s=Math.abs,l=Math.floor;e.exports=function(e){var t,n,c,f;if(!r(e))return a.apply(this,arguments);for(n=o(i(this).length),c=arguments[1],t=c=isNaN(c)?0:c>=0?l(c):o(this.length)-l(s(c));t<n;++t)if(u.call(this,t)&&(f=this[t],r(f)))return t;return-1}},function(e,t,n){"use strict";(function(t,n){var r,o;r=function(e){if("function"!=typeof e)throw new TypeError(e+" is not a function");return e},o=function(e){var t,n,o=document.createTextNode(""),i=0;return new e(function(){var e;if(t)n&&(t=n.concat(t));else{if(!n)return;t=n}if(n=t,t=null,"function"==typeof n)return e=n,n=null,void e();for(o.data=i=++i%2;n;)e=n.shift(),n.length||(n=null),e()}).observe(o,{characterData:!0}),f