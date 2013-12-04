/*
YUI 3.14.0 (build a01e97d)
Copyright 2013 Yahoo! Inc. All rights reserved.
Licensed under the BSD License.
http://yuilibrary.com/license/
*/

YUI.add("io-base",function(e,t){function o(t){var n=this;n._uid="io:"+s++,n._init(t),e.io._map[n._uid]=n}var n=["start","complete","end","success","failure","progress"],r=["status","statusText","responseText","responseXML"],i=e.config.win,s=0;o.prototype={_id:0,_headers:{"X-Requested-With":"XMLHttpRequest"},_timeout:{},_init:function(t){var r=this,i,s;r.cfg=t||{},e.augment(r,e.EventTarget);for(i=0,s=n.length;i<s;++i)r.publish("io:"+n[i],e.merge({broadcast:1},t)),r.publish("io-trn:"+n[i],t)},_create:function(t,n){var r=this,s={id:e.Lang.isNumber(n)?n:r._id++,uid:r._uid},o=t.xdr?t.xdr.use:null,u=t.form&&t.form.upload?"iframe":null,a;return o==="native"&&(o=e.UA.ie&&!l?"xdr":null,r.setHeader("X-Requested-With")),a=o||u,s=a?e.merge(e.IO.customTransport(a),s):e.merge(e.IO.defaultTransport(),s),s.notify&&(t.notify=function(e,t,n){r.notify(e,t,n)}),a||i&&i.FormData&&t.data instanceof i.FormData&&(s.c.upload.onprogress=function(e){r.progress(s,e,t)},s.c.onload=function(e){r.load(s,e,t)},s.c.onerror=function(e){r.error(s,e,t)},s.upload=!0),s},_destroy:function(t){i&&!t.notify&&!t.xdr&&(u&&!t.upload?t.c.onreadystatechange=null:t.upload?(t.c.upload.onprogress=null,t.c.onload=null,t.c.onerror=null):e.UA.ie&&!t.e&&t.c.abort()),t=t.c=null},_evt:function(t,r,i){var s=this,o,u=i.arguments,a=s.cfg.emitFacade,f="io:"+t,l="io-trn:"+t;this.detach(l),r.e&&(r.c={status:0,statusText:r.e}),o=[a?{id:r.id,data:r.c,cfg:i,arguments:u}:r.id],a||(t===n[0]||t===n[2]?u&&o.push(u):(r.evt?o.push(r.evt):o.push(r.c),u&&o.push(u))),o.unshift(f),s.fire.apply(s,o),i.on&&(o[0]=l,s.once(l,i.on[t],i.context||e),s.fire.apply(s,o))},start:function(e,t){this._evt(n[0],e,t)},complete:function(e,t){this._evt(n[1],e,t)},end:function(e,t){this._evt(n[2],e,t),this._destroy(e)},success:function(e,t){this._evt(n[3],e,t),this.end(e,t)},failure:function(e,t){this._evt(n[4],e,t),this.end(e,t)},progress:function(e,t,r){e.evt=t,this._evt(n[5],e,r)},load:function(e,t,r){e.evt=t.target,this._evt(n[1],e,r)},error:function(e,t,r){e.evt=t,this._evt(n[4],e,r)},_retry:function(e,t,n){return this._destroy(e),n.xdr.use="flash",this.send(t,n,e.id)},_concat:function(e,t){return e+=(e.indexOf("?")===-1?"?":"&")+t,e},setHeader:function(e,t){t?this._headers[e]=t:delete this._headers[e]},_setHeaders:function(t,n){n=e.merge(this._headers,n),e.Object.each(n,function(e,r){e!=="disable"&&t.setRequestHeader(r,n[r])})},_startTimeout:function(e,t){var n=this;n._timeout[e.id]=setTimeout(function(){n._abort(e,"timeout")},t)},_clearTimeout:function(e){clearTimeout(this._timeout[e]),delete this._timeout[e]},_result:function(e,t){var n;try{n=e.c.status}catch(r){n=0}n>=200&&n<300||n===304||n===1223?this.success(e,t):this.failure(e,t)},_rS:function(e,t){var n=this;e.c.readyState===4&&(t.timeout&&n._clearTimeout(e.id),setTimeout(function(){n.complete(e,t),n._result(e,t)},0))},_abort:function(e,t){e&&e.c&&(e.e=t,e.c.abort())},send:function(t,n,i){var s,o,u,a,f,c,h=this,p=t,d={};n=n?e.Object(n):{},s=h._create(n,i),o=n.method?n.method.toUpperCase():"GET",f=n.sync,c=n.data,e.Lang.isObject(c)&&!c.nodeType&&!s.upload&&e.QueryString&&e.QueryString.stringify&&(n.data=c=e.QueryString.stringify(c));if(n.form){if(n.form.upload)return h.upload(s,t,n);c=h._serialize(n.form,c)}c||(c="");if(c)switch(o){case"GET":case"HEAD":case"DELETE":p=h._concat(p,c),c="";break;case"POST":case"PUT":n.headers=e.merge({"Content-Type":"application/x-www-form-urlencoded; charset=UTF-8"},n.headers)}if(s.xdr)return h.xdr(p,s,n);if(s.notify)return s.c.send(s,t,n);!f&&!s.upload&&(s.c.onreadystatechange=function(){h._rS(s,n)});try{s.c.open(o,p,!f,n.username||null,n.password||null),h._setHeaders(s.c,n.headers||{}),h.start(s,n),n.xdr&&n.xdr.credentials&&l&&(s.c.withCredentials=!0),s.c.send(c);if(f){for(u=0,a=r.length;u<a;++u)d[r[u]]=s.c[r[u]];return d.getAllResponseHeaders=function(){return s.c.getAllResponseHeaders()},d.getResponseHeader=function(e){return s.c.getResponseHeader(e)},h.complete(s,n),h._result(s,n),d}}catch(v){if(s.xdr)return h._retry(s,t,n);h.complete(s,n),h._result(s,n)}return n.timeout&&h._startTimeout(s,n.timeout),{id:s.id,abort:function(){return s.c?h._abort(s,"abort"):!1},isInProgress:function(){return s.c?s.c.readyState%4:!1},io:h}}},e.io=function(t,n){var r=e.io._map["io:0"]||new o;return r.send.apply(r,[t,n])},e.io.header=function(t,n){var r=e.io._map["io:0"]||new o;r.setHeader(t,n)},e.IO=o,e.io._map={};var u=i&&i.XMLHttpRequest,a=i&&i.XDomainRequest,f=i&&i.ActiveXObject,l=u&&"withCredentials"in new XMLHttpRequest;e.mix(e.IO,{_default:"xhr",defaultTransport:function(t){if(!t){var n={c:e.IO.transports[e.IO._default](),notify:e.IO._default==="xhr"?!1:!0};return n}e.IO._default=t},transports:{xhr:function(){return u?new XMLHttpRequest:f?new ActiveXObject("Microsoft.XMLHTTP"):null},xdr:function(){return a?new XDomainRequest:null},iframe:function(){return{}},flash:null,nodejs:null},customTransport:function(t){var n={c:e.IO.transports[t]()};return n[t==="xdr"||t==="flash"?"xdr":"notify"]=!0,n}}),e.mix(e.IO.prototype,{notify:function(e,t,n){var r=this;switch(e){case"timeout":case"abort":case"transport error":t.c={status:0,statusText:e},e="failure";default:r[e].apply(r,[t,n])}}})},"3.14.0",{requires:["event-custom-base","querystring-stringify-simple"]});
