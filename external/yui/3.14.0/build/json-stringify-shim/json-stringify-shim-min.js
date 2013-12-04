/*
YUI 3.14.0 (build a01e97d)
Copyright 2013 Yahoo! Inc. All rights reserved.
Licensed under the BSD License.
http://yuilibrary.com/license/
*/

YUI.add("json-stringify-shim",function(e,t){function _(e){var t=typeof e;return d[t]||d[o.call(e)]||(t===a?e?a:f:u)}function D(e){return A[e]||(A[e]="\\u"+("0000"+(+e.charCodeAt(0)).toString(16)).slice(-4),O[e]=0),++O[e]===M&&(k.push([new RegExp(e,"g"),A[e]]),L=k.length),A[e]}function P(e){var t,n;for(t=0;t<L;t++)n=k[t],e=e.replace(n[0],n[1]);return N+e.replace(C,D)+N}function H(e,t){return e.replace(/^/gm,t)}var n=e.Lang,r=n.isFunction,i=n.isObject,s=n.isArray,o=Object.prototype.toString,u="undefined",a="object",f="null",l="string",c="number",h="boolean",p="date",d={"undefined":u,string:l,"[object String]":l,number:c,"[object Number]":c,"boolean":h,"[object Boolean]":h,"[object Date]":p,"[object RegExp]":a},v="",m="{",g="}",y="[",b="]",w=",",E=",\n",S="\n",x=":",T=": ",N='"',C=/[\x00-\x07\x0b\x0e-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,k=[[/\\/g,"\\\\"],[/\"/g,'\\"'],[/\x08/g,"\\b"],[/\x09/g,"\\t"],[/\x0a/g,"\\n"],[/\x0c/g,"\\f"],[/\x0d/g,"\\r"]],L=k.length,A={},O,M;e.JSON.stringify=function(n,u,d){function j(e,t){var n=e[t],o=_(n),C=[],A=d?T:x,O,M,D,B,F;i(n)&&r(n.toJSON)?n=n.toJSON(t):o===p&&(n=k(n)),r(N)&&(n=N.call(e,t,n)),n!==e[t]&&(o=_(n));switch(o){case p:case a:break;case l:return P(n);case c:return isFinite(n)?n+v:f;case h:return n+v;case f:return f;default:return undefined}for(M=L.length-1;M>=0;--M)if(L[M]===n)throw new Error("JSON.stringify. Cyclical reference");O=s(n),L.push(n);if(O)for(M=n.length-1;M>=0;--M)C[M]=j(n,M)||f;else{D=u||n,M=0;for(B in D)D.hasOwnProperty(B)&&(F=j(n,B),F&&(C[M++]=P(B)+A+F))}return L.pop(),d&&C.length?O?y+S+H(C.join(E),d)+S+b:m+S+H(C.join(E),d)+S+g:O?y+C.join(w)+b:m+C.join(w)+g}if(n===undefined)return undefined;var N=r(u)?u:null,C=o.call(d).match(/String|Number/)||[],k=e.JSON.dateToString,L=[],A,D,B;O={},M=e.JSON.charCacheThreshold;if(N||!s(u))u=undefined;if(u){A={};for(D=0,B=u.length;D<B;++D)A[u[D]]=!0;u=A}return d=C[0]==="Number"?(new Array(Math.min(Math.max(0,d),10)+1)).join(" "):(d||v).slice(0,10),j({"":n},"")},e.JSON.stringify.isShim=!0},"3.14.0",{requires:["json-stringify"]});
