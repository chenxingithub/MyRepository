(function(a){a.Callbacks=function(l){l=a.extend({},l);var e,b,f,d,g,h,i=[],j=!l.once&&[],c=function(m){e=l.memory&&m;b=true;h=d||0;d=0;g=i.length;f=true;for(;i&&h<g;++h){if(i[h].apply(m[0],m[1])===false&&l.stopOnFalse){e=false;break}}f=false;if(i){if(j){j.length&&c(j.shift())}else{if(e){i.length=0}else{k.disable()}}}},k={add:function(){if(i){var n=i.length,m=function(o){a.each(o,function(q,p){if(typeof p==="function"){if(!l.unique||!k.has(p)){i.push(p)}}else{if(p&&p.length&&typeof p!=="string"){m(p)}}})};m(arguments);if(f){g=i.length}else{if(e){d=n;c(e)}}}return this},remove:function(){if(i){a.each(arguments,function(o,m){var n;while((n=a.inArray(m,i,n))>-1){i.splice(n,1);if(f){if(n<=g){--g}if(n<=h){--h}}}})}return this},has:function(m){return !!(i&&(m?a.inArray(m,i)>-1:i.length))},empty:function(){g=i.length=0;return this},disable:function(){i=j=e=undefined;return this},disabled:function(){return !i},lock:function(){j=undefined;if(!e){k.disable()}return this},locked:function(){return !j},fireWith:function(n,m){if(i&&(!b||j)){m=m||[];m=[n,m.slice?m.slice():m];if(f){j.push(m)}else{c(m)}}return this},fire:function(){return k.fireWith(this,arguments)},fired:function(){return !!b}};return k}})(Zepto);(function(b){var c=Array.prototype.slice;function a(f){var e=[["resolve","done",b.Callbacks({once:1,memory:1}),"resolved"],["reject","fail",b.Callbacks({once:1,memory:1}),"rejected"],["notify","progress",b.Callbacks({memory:1})]],g="pending",h={state:function(){return g},always:function(){d.done(arguments).fail(arguments);return this},then:function(){var i=arguments;return a(function(j){b.each(e,function(l,k){var m=b.isFunction(i[l])&&i[l];d[k[1]](function(){var p=m&&m.apply(this,arguments);if(p&&b.isFunction(p.promise)){p.promise().done(j.resolve).fail(j.reject).progress(j.notify)}else{var o=this===h?j.promise():this,n=m?[p]:arguments;j[k[0]+"With"](o,n)}})});i=null}).promise()},promise:function(i){return i!=null?b.extend(i,h):h}},d={};b.each(e,function(k,j){var m=j[2],l=j[3];h[j[1]]=m.add;if(l){m.add(function(){g=l},e[k^1][2].disable,e[2][2].lock)}d[j[0]]=function(){d[j[0]+"With"](this===d?h:this,arguments);return this};d[j[0]+"With"]=m.fireWith});h.promise(d);if(f){f.call(d,d)}return d}b.when=function(d){var k=c.call(arguments),h=k.length,e=0,g=h!==1||(d&&b.isFunction(d.promise))?h:0,n=g===1?d:a(),m,j,l,f=function(p,o,q){return function(i){o[p]=this;q[p]=arguments.length>1?c.call(arguments):i;if(q===m){n.notifyWith(o,q)}else{if(!(--g)){n.resolveWith(o,q)}}}};if(h>1){m=new Array(h);j=new Array(h);l=new Array(h);for(;e<h;++e){if(k[e]&&b.isFunction(k[e].promise)){k[e].promise().done(f(e,l,k)).fail(n.reject).progress(f(e,j,m))}else{--g}}}if(!g){n.resolveWith(l,k)}return n.promise()};b.Deferred=a})(Zepto);