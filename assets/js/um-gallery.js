/*! Magnific Popup - v1.1.0 - 2016-02-20
* http://dimsemenov.com/plugins/magnific-popup/
* Copyright (c) 2016 Dmitry Semenov; */
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):a("object"==typeof exports?require("jquery"):window.jQuery||window.Zepto)}(function(a){var b,c,d,e,f,g,h="Close",i="BeforeClose",j="AfterClose",k="BeforeAppend",l="MarkupParse",m="Open",n="Change",o="mfp",p="."+o,q="mfp-ready",r="mfp-removing",s="mfp-prevent-close",t=function(){},u=!!window.jQuery,v=a(window),w=function(a,c){b.ev.on(o+a+p,c)},x=function(b,c,d,e){var f=document.createElement("div");return f.className="mfp-"+b,d&&(f.innerHTML=d),e?c&&c.appendChild(f):(f=a(f),c&&f.appendTo(c)),f},y=function(c,d){b.ev.triggerHandler(o+c,d),b.st.callbacks&&(c=c.charAt(0).toLowerCase()+c.slice(1),b.st.callbacks[c]&&b.st.callbacks[c].apply(b,a.isArray(d)?d:[d]))},z=function(c){return c===g&&b.currTemplate.closeBtn||(b.currTemplate.closeBtn=a(b.st.closeMarkup.replace("%title%",b.st.tClose)),g=c),b.currTemplate.closeBtn},A=function(){a.magnificPopup.instance||(b=new t,b.init(),a.magnificPopup.instance=b)},B=function(){var a=document.createElement("p").style,b=["ms","O","Moz","Webkit"];if(void 0!==a.transition)return!0;for(;b.length;)if(b.pop()+"Transition"in a)return!0;return!1};t.prototype={constructor:t,init:function(){var c=navigator.appVersion;b.isLowIE=b.isIE8=document.all&&!document.addEventListener,b.isAndroid=/android/gi.test(c),b.isIOS=/iphone|ipad|ipod/gi.test(c),b.supportsTransition=B(),b.probablyMobile=b.isAndroid||b.isIOS||/(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(navigator.userAgent),d=a(document),b.popupsCache={}},open:function(c){var e;if(c.isObj===!1){b.items=c.items.toArray(),b.index=0;var g,h=c.items;for(e=0;e<h.length;e++)if(g=h[e],g.parsed&&(g=g.el[0]),g===c.el[0]){b.index=e;break}}else b.items=a.isArray(c.items)?c.items:[c.items],b.index=c.index||0;if(b.isOpen)return void b.updateItemHTML();b.types=[],f="",c.mainEl&&c.mainEl.length?b.ev=c.mainEl.eq(0):b.ev=d,c.key?(b.popupsCache[c.key]||(b.popupsCache[c.key]={}),b.currTemplate=b.popupsCache[c.key]):b.currTemplate={},b.st=a.extend(!0,{},a.magnificPopup.defaults,c),b.fixedContentPos="auto"===b.st.fixedContentPos?!b.probablyMobile:b.st.fixedContentPos,b.st.modal&&(b.st.closeOnContentClick=!1,b.st.closeOnBgClick=!1,b.st.showCloseBtn=!1,b.st.enableEscapeKey=!1),b.bgOverlay||(b.bgOverlay=x("bg").on("click"+p,function(){b.close()}),b.wrap=x("wrap").attr("tabindex",-1).on("click"+p,function(a){b._checkIfClose(a.target)&&b.close()}),b.container=x("container",b.wrap)),b.contentContainer=x("content"),b.st.preloader&&(b.preloader=x("preloader",b.container,b.st.tLoading));var i=a.magnificPopup.modules;for(e=0;e<i.length;e++){var j=i[e];j=j.charAt(0).toUpperCase()+j.slice(1),b["init"+j].call(b)}y("BeforeOpen"),b.st.showCloseBtn&&(b.st.closeBtnInside?(w(l,function(a,b,c,d){c.close_replaceWith=z(d.type)}),f+=" mfp-close-btn-in"):b.wrap.append(z())),b.st.alignTop&&(f+=" mfp-align-top"),b.fixedContentPos?b.wrap.css({overflow:b.st.overflowY,overflowX:"hidden",overflowY:b.st.overflowY}):b.wrap.css({top:v.scrollTop(),position:"absolute"}),(b.st.fixedBgPos===!1||"auto"===b.st.fixedBgPos&&!b.fixedContentPos)&&b.bgOverlay.css({height:d.height(),position:"absolute"}),b.st.enableEscapeKey&&d.on("keyup"+p,function(a){27===a.keyCode&&b.close()}),v.on("resize"+p,function(){b.updateSize()}),b.st.closeOnContentClick||(f+=" mfp-auto-cursor"),f&&b.wrap.addClass(f);var k=b.wH=v.height(),n={};if(b.fixedContentPos&&b._hasScrollBar(k)){var o=b._getScrollbarSize();o&&(n.marginRight=o)}b.fixedContentPos&&(b.isIE7?a("body, html").css("overflow","hidden"):n.overflow="hidden");var r=b.st.mainClass;return b.isIE7&&(r+=" mfp-ie7"),r&&b._addClassToMFP(r),b.updateItemHTML(),y("BuildControls"),a("html").css(n),b.bgOverlay.add(b.wrap).prependTo(b.st.prependTo||a(document.body)),b._lastFocusedEl=document.activeElement,setTimeout(function(){b.content?(b._addClassToMFP(q),b._setFocus()):b.bgOverlay.addClass(q),d.on("focusin"+p,b._onFocusIn)},16),b.isOpen=!0,b.updateSize(k),y(m),c},close:function(){b.isOpen&&(y(i),b.isOpen=!1,b.st.removalDelay&&!b.isLowIE&&b.supportsTransition?(b._addClassToMFP(r),setTimeout(function(){b._close()},b.st.removalDelay)):b._close())},_close:function(){y(h);var c=r+" "+q+" ";if(b.bgOverlay.detach(),b.wrap.detach(),b.container.empty(),b.st.mainClass&&(c+=b.st.mainClass+" "),b._removeClassFromMFP(c),b.fixedContentPos){var e={marginRight:""};b.isIE7?a("body, html").css("overflow",""):e.overflow="",a("html").css(e)}d.off("keyup"+p+" focusin"+p),b.ev.off(p),b.wrap.attr("class","mfp-wrap").removeAttr("style"),b.bgOverlay.attr("class","mfp-bg"),b.container.attr("class","mfp-container"),!b.st.showCloseBtn||b.st.closeBtnInside&&b.currTemplate[b.currItem.type]!==!0||b.currTemplate.closeBtn&&b.currTemplate.closeBtn.detach(),b.st.autoFocusLast&&b._lastFocusedEl&&a(b._lastFocusedEl).focus(),b.currItem=null,b.content=null,b.currTemplate=null,b.prevHeight=0,y(j)},updateSize:function(a){if(b.isIOS){var c=document.documentElement.clientWidth/window.innerWidth,d=window.innerHeight*c;b.wrap.css("height",d),b.wH=d}else b.wH=a||v.height();b.fixedContentPos||b.wrap.css("height",b.wH),y("Resize")},updateItemHTML:function(){var c=b.items[b.index];b.contentContainer.detach(),b.content&&b.content.detach(),c.parsed||(c=b.parseEl(b.index));var d=c.type;if(y("BeforeChange",[b.currItem?b.currItem.type:"",d]),b.currItem=c,!b.currTemplate[d]){var f=b.st[d]?b.st[d].markup:!1;y("FirstMarkupParse",f),f?b.currTemplate[d]=a(f):b.currTemplate[d]=!0}e&&e!==c.type&&b.container.removeClass("mfp-"+e+"-holder");var g=b["get"+d.charAt(0).toUpperCase()+d.slice(1)](c,b.currTemplate[d]);b.appendContent(g,d),c.preloaded=!0,y(n,c),e=c.type,b.container.prepend(b.contentContainer),y("AfterChange")},appendContent:function(a,c){b.content=a,a?b.st.showCloseBtn&&b.st.closeBtnInside&&b.currTemplate[c]===!0?b.content.find(".mfp-close").length||b.content.append(z()):b.content=a:b.content="",y(k),b.container.addClass("mfp-"+c+"-holder"),b.contentContainer.append(b.content)},parseEl:function(c){var d,e=b.items[c];if(e.tagName?e={el:a(e)}:(d=e.type,e={data:e,src:e.src}),e.el){for(var f=b.types,g=0;g<f.length;g++)if(e.el.hasClass("mfp-"+f[g])){d=f[g];break}e.src=e.el.attr("data-mfp-src"),e.src||(e.src=e.el.attr("href"))}return e.type=d||b.st.type||"inline",e.index=c,e.parsed=!0,b.items[c]=e,y("ElementParse",e),b.items[c]},addGroup:function(a,c){var d=function(d){d.mfpEl=this,b._openClick(d,a,c)};c||(c={});var e="click.magnificPopup";c.mainEl=a,c.items?(c.isObj=!0,a.off(e).on(e,d)):(c.isObj=!1,c.delegate?a.off(e).on(e,c.delegate,d):(c.items=a,a.off(e).on(e,d)))},_openClick:function(c,d,e){var f=void 0!==e.midClick?e.midClick:a.magnificPopup.defaults.midClick;if(f||!(2===c.which||c.ctrlKey||c.metaKey||c.altKey||c.shiftKey)){var g=void 0!==e.disableOn?e.disableOn:a.magnificPopup.defaults.disableOn;if(g)if(a.isFunction(g)){if(!g.call(b))return!0}else if(v.width()<g)return!0;c.type&&(c.preventDefault(),b.isOpen&&c.stopPropagation()),e.el=a(c.mfpEl),e.delegate&&(e.items=d.find(e.delegate)),b.open(e)}},updateStatus:function(a,d){if(b.preloader){c!==a&&b.container.removeClass("mfp-s-"+c),d||"loading"!==a||(d=b.st.tLoading);var e={status:a,text:d};y("UpdateStatus",e),a=e.status,d=e.text,b.preloader.html(d),b.preloader.find("a").on("click",function(a){a.stopImmediatePropagation()}),b.container.addClass("mfp-s-"+a),c=a}},_checkIfClose:function(c){if(!a(c).hasClass(s)){var d=b.st.closeOnContentClick,e=b.st.closeOnBgClick;if(d&&e)return!0;if(!b.content||a(c).hasClass("mfp-close")||b.preloader&&c===b.preloader[0])return!0;if(c===b.content[0]||a.contains(b.content[0],c)){if(d)return!0}else if(e&&a.contains(document,c))return!0;return!1}},_addClassToMFP:function(a){b.bgOverlay.addClass(a),b.wrap.addClass(a)},_removeClassFromMFP:function(a){this.bgOverlay.removeClass(a),b.wrap.removeClass(a)},_hasScrollBar:function(a){return(b.isIE7?d.height():document.body.scrollHeight)>(a||v.height())},_setFocus:function(){(b.st.focus?b.content.find(b.st.focus).eq(0):b.wrap).focus()},_onFocusIn:function(c){return c.target===b.wrap[0]||a.contains(b.wrap[0],c.target)?void 0:(b._setFocus(),!1)},_parseMarkup:function(b,c,d){var e;d.data&&(c=a.extend(d.data,c)),y(l,[b,c,d]),a.each(c,function(c,d){if(void 0===d||d===!1)return!0;if(e=c.split("_"),e.length>1){var f=b.find(p+"-"+e[0]);if(f.length>0){var g=e[1];"replaceWith"===g?f[0]!==d[0]&&f.replaceWith(d):"img"===g?f.is("img")?f.attr("src",d):f.replaceWith(a("<img>").attr("src",d).attr("class",f.attr("class"))):f.attr(e[1],d)}}else b.find(p+"-"+c).html(d)})},_getScrollbarSize:function(){if(void 0===b.scrollbarSize){var a=document.createElement("div");a.style.cssText="width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;",document.body.appendChild(a),b.scrollbarSize=a.offsetWidth-a.clientWidth,document.body.removeChild(a)}return b.scrollbarSize}},a.magnificPopup={instance:null,proto:t.prototype,modules:[],open:function(b,c){return A(),b=b?a.extend(!0,{},b):{},b.isObj=!0,b.index=c||0,this.instance.open(b)},close:function(){return a.magnificPopup.instance&&a.magnificPopup.instance.close()},registerModule:function(b,c){c.options&&(a.magnificPopup.defaults[b]=c.options),a.extend(this.proto,c.proto),this.modules.push(b)},defaults:{disableOn:0,key:null,midClick:!1,mainClass:"",preloader:!0,focus:"",closeOnContentClick:!1,closeOnBgClick:!0,closeBtnInside:!0,showCloseBtn:!0,enableEscapeKey:!0,modal:!1,alignTop:!1,removalDelay:0,prependTo:null,fixedContentPos:"auto",fixedBgPos:"auto",overflowY:"auto",closeMarkup:'<button title="%title%" type="button" class="mfp-close">&#215;</button>',tClose:"Close (Esc)",tLoading:"Loading...",autoFocusLast:!0}},a.fn.magnificPopup=function(c){A();var d=a(this);if("string"==typeof c)if("open"===c){var e,f=u?d.data("magnificPopup"):d[0].magnificPopup,g=parseInt(arguments[1],10)||0;f.items?e=f.items[g]:(e=d,f.delegate&&(e=e.find(f.delegate)),e=e.eq(g)),b._openClick({mfpEl:e},d,f)}else b.isOpen&&b[c].apply(b,Array.prototype.slice.call(arguments,1));else c=a.extend(!0,{},c),u?d.data("magnificPopup",c):d[0].magnificPopup=c,b.addGroup(d,c);return d};var C,D,E,F="inline",G=function(){E&&(D.after(E.addClass(C)).detach(),E=null)};a.magnificPopup.registerModule(F,{options:{hiddenClass:"hide",markup:"",tNotFound:"Content not found"},proto:{initInline:function(){b.types.push(F),w(h+"."+F,function(){G()})},getInline:function(c,d){if(G(),c.src){var e=b.st.inline,f=a(c.src);if(f.length){var g=f[0].parentNode;g&&g.tagName&&(D||(C=e.hiddenClass,D=x(C),C="mfp-"+C),E=f.after(D).detach().removeClass(C)),b.updateStatus("ready")}else b.updateStatus("error",e.tNotFound),f=a("<div>");return c.inlineElement=f,f}return b.updateStatus("ready"),b._parseMarkup(d,{},c),d}}});var H,I="ajax",J=function(){H&&a(document.body).removeClass(H)},K=function(){J(),b.req&&b.req.abort()};a.magnificPopup.registerModule(I,{options:{settings:null,cursor:"mfp-ajax-cur",tError:'<a href="%url%">The content</a> could not be loaded.'},proto:{initAjax:function(){b.types.push(I),H=b.st.ajax.cursor,w(h+"."+I,K),w("BeforeChange."+I,K)},getAjax:function(c){H&&a(document.body).addClass(H),b.updateStatus("loading");var d=a.extend({url:c.src,success:function(d,e,f){var g={data:d,xhr:f};y("ParseAjax",g),b.appendContent(a(g.data),I),c.finished=!0,J(),b._setFocus(),setTimeout(function(){b.wrap.addClass(q)},16),b.updateStatus("ready"),y("AjaxContentAdded")},error:function(){J(),c.finished=c.loadError=!0,b.updateStatus("error",b.st.ajax.tError.replace("%url%",c.src))}},b.st.ajax.settings);return b.req=a.ajax(d),""}}});var L,M=function(c){if(c.data&&void 0!==c.data.title)return c.data.title;var d=b.st.image.titleSrc;if(d){if(a.isFunction(d))return d.call(b,c);if(c.el)return c.el.attr(d)||""}return""};a.magnificPopup.registerModule("image",{options:{markup:'<div class="mfp-figure"><div class="mfp-close"></div><figure><div class="mfp-img"></div><figcaption><div class="mfp-bottom-bar"><div class="mfp-title"></div><div class="mfp-counter"></div></div></figcaption></figure></div>',cursor:"mfp-zoom-out-cur",titleSrc:"title",verticalFit:!0,tError:'<a href="%url%">The image</a> could not be loaded.'},proto:{initImage:function(){var c=b.st.image,d=".image";b.types.push("image"),w(m+d,function(){"image"===b.currItem.type&&c.cursor&&a(document.body).addClass(c.cursor)}),w(h+d,function(){c.cursor&&a(document.body).removeClass(c.cursor),v.off("resize"+p)}),w("Resize"+d,b.resizeImage),b.isLowIE&&w("AfterChange",b.resizeImage)},resizeImage:function(){var a=b.currItem;if(a&&a.img&&b.st.image.verticalFit){var c=0;b.isLowIE&&(c=parseInt(a.img.css("padding-top"),10)+parseInt(a.img.css("padding-bottom"),10)),a.img.css("max-height",b.wH-c)}},_onImageHasSize:function(a){a.img&&(a.hasSize=!0,L&&clearInterval(L),a.isCheckingImgSize=!1,y("ImageHasSize",a),a.imgHidden&&(b.content&&b.content.removeClass("mfp-loading"),a.imgHidden=!1))},findImageSize:function(a){var c=0,d=a.img[0],e=function(f){L&&clearInterval(L),L=setInterval(function(){return d.naturalWidth>0?void b._onImageHasSize(a):(c>200&&clearInterval(L),c++,void(3===c?e(10):40===c?e(50):100===c&&e(500)))},f)};e(1)},getImage:function(c,d){var e=0,f=function(){c&&(c.img[0].complete?(c.img.off(".mfploader"),c===b.currItem&&(b._onImageHasSize(c),b.updateStatus("ready")),c.hasSize=!0,c.loaded=!0,y("ImageLoadComplete")):(e++,200>e?setTimeout(f,100):g()))},g=function(){c&&(c.img.off(".mfploader"),c===b.currItem&&(b._onImageHasSize(c),b.updateStatus("error",h.tError.replace("%url%",c.src))),c.hasSize=!0,c.loaded=!0,c.loadError=!0)},h=b.st.image,i=d.find(".mfp-img");if(i.length){var j=document.createElement("img");j.className="mfp-img",c.el&&c.el.find("img").length&&(j.alt=c.el.find("img").attr("alt")),c.img=a(j).on("load.mfploader",f).on("error.mfploader",g),j.src=c.src,i.is("img")&&(c.img=c.img.clone()),j=c.img[0],j.naturalWidth>0?c.hasSize=!0:j.width||(c.hasSize=!1)}return b._parseMarkup(d,{title:M(c),img_replaceWith:c.img},c),b.resizeImage(),c.hasSize?(L&&clearInterval(L),c.loadError?(d.addClass("mfp-loading"),b.updateStatus("error",h.tError.replace("%url%",c.src))):(d.removeClass("mfp-loading"),b.updateStatus("ready")),d):(b.updateStatus("loading"),c.loading=!0,c.hasSize||(c.imgHidden=!0,d.addClass("mfp-loading"),b.findImageSize(c)),d)}}});var N,O=function(){return void 0===N&&(N=void 0!==document.createElement("p").style.MozTransform),N};a.magnificPopup.registerModule("zoom",{options:{enabled:!1,easing:"ease-in-out",duration:300,opener:function(a){return a.is("img")?a:a.find("img")}},proto:{initZoom:function(){var a,c=b.st.zoom,d=".zoom";if(c.enabled&&b.supportsTransition){var e,f,g=c.duration,j=function(a){var b=a.clone().removeAttr("style").removeAttr("class").addClass("mfp-animated-image"),d="all "+c.duration/1e3+"s "+c.easing,e={position:"fixed",zIndex:9999,left:0,top:0,"-webkit-backface-visibility":"hidden"},f="transition";return e["-webkit-"+f]=e["-moz-"+f]=e["-o-"+f]=e[f]=d,b.css(e),b},k=function(){b.content.css("visibility","visible")};w("BuildControls"+d,function(){if(b._allowZoom()){if(clearTimeout(e),b.content.css("visibility","hidden"),a=b._getItemToZoom(),!a)return void k();f=j(a),f.css(b._getOffset()),b.wrap.append(f),e=setTimeout(function(){f.css(b._getOffset(!0)),e=setTimeout(function(){k(),setTimeout(function(){f.remove(),a=f=null,y("ZoomAnimationEnded")},16)},g)},16)}}),w(i+d,function(){if(b._allowZoom()){if(clearTimeout(e),b.st.removalDelay=g,!a){if(a=b._getItemToZoom(),!a)return;f=j(a)}f.css(b._getOffset(!0)),b.wrap.append(f),b.content.css("visibility","hidden"),setTimeout(function(){f.css(b._getOffset())},16)}}),w(h+d,function(){b._allowZoom()&&(k(),f&&f.remove(),a=null)})}},_allowZoom:function(){return"image"===b.currItem.type},_getItemToZoom:function(){return b.currItem.hasSize?b.currItem.img:!1},_getOffset:function(c){var d;d=c?b.currItem.img:b.st.zoom.opener(b.currItem.el||b.currItem);var e=d.offset(),f=parseInt(d.css("padding-top"),10),g=parseInt(d.css("padding-bottom"),10);e.top-=a(window).scrollTop()-f;var h={width:d.width(),height:(u?d.innerHeight():d[0].offsetHeight)-g-f};return O()?h["-moz-transform"]=h.transform="translate("+e.left+"px,"+e.top+"px)":(h.left=e.left,h.top=e.top),h}}});var P="iframe",Q="//about:blank",R=function(a){if(b.currTemplate[P]){var c=b.currTemplate[P].find("iframe");c.length&&(a||(c[0].src=Q),b.isIE8&&c.css("display",a?"block":"none"))}};a.magnificPopup.registerModule(P,{options:{markup:'<div class="mfp-iframe-scaler"><div class="mfp-close"></div><iframe class="mfp-iframe" src="//about:blank" frameborder="0" allowfullscreen></iframe></div>',srcAction:"iframe_src",patterns:{youtube:{index:"youtube.com",id:"v=",src:"//www.youtube.com/embed/%id%?autoplay=1"},vimeo:{index:"vimeo.com/",id:"/",src:"//player.vimeo.com/video/%id%?autoplay=1"},gmaps:{index:"//maps.google.",src:"%id%&output=embed"}}},proto:{initIframe:function(){b.types.push(P),w("BeforeChange",function(a,b,c){b!==c&&(b===P?R():c===P&&R(!0))}),w(h+"."+P,function(){R()})},getIframe:function(c,d){var e=c.src,f=b.st.iframe;a.each(f.patterns,function(){return e.indexOf(this.index)>-1?(this.id&&(e="string"==typeof this.id?e.substr(e.lastIndexOf(this.id)+this.id.length,e.length):this.id.call(this,e)),e=this.src.replace("%id%",e),!1):void 0});var g={};return f.srcAction&&(g[f.srcAction]=e),b._parseMarkup(d,g,c),b.updateStatus("ready"),d}}});var S=function(a){var c=b.items.length;return a>c-1?a-c:0>a?c+a:a},T=function(a,b,c){return a.replace(/%curr%/gi,b+1).replace(/%total%/gi,c)};a.magnificPopup.registerModule("gallery",{options:{enabled:!1,arrowMarkup:'<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',preload:[0,2],navigateByImgClick:!0,arrows:!0,tPrev:"Previous (Left arrow key)",tNext:"Next (Right arrow key)",tCounter:"%curr% of %total%"},proto:{initGallery:function(){var c=b.st.gallery,e=".mfp-gallery";return b.direction=!0,c&&c.enabled?(f+=" mfp-gallery",w(m+e,function(){c.navigateByImgClick&&b.wrap.on("click"+e,".mfp-img",function(){return b.items.length>1?(b.next(),!1):void 0}),d.on("keydown"+e,function(a){37===a.keyCode?b.prev():39===a.keyCode&&b.next()})}),w("UpdateStatus"+e,function(a,c){c.text&&(c.text=T(c.text,b.currItem.index,b.items.length))}),w(l+e,function(a,d,e,f){var g=b.items.length;e.counter=g>1?T(c.tCounter,f.index,g):""}),w("BuildControls"+e,function(){if(b.items.length>1&&c.arrows&&!b.arrowLeft){var d=c.arrowMarkup,e=b.arrowLeft=a(d.replace(/%title%/gi,c.tPrev).replace(/%dir%/gi,"left")).addClass(s),f=b.arrowRight=a(d.replace(/%title%/gi,c.tNext).replace(/%dir%/gi,"right")).addClass(s);e.click(function(){b.prev()}),f.click(function(){b.next()}),b.container.append(e.add(f))}}),w(n+e,function(){b._preloadTimeout&&clearTimeout(b._preloadTimeout),b._preloadTimeout=setTimeout(function(){b.preloadNearbyImages(),b._preloadTimeout=null},16)}),void w(h+e,function(){d.off(e),b.wrap.off("click"+e),b.arrowRight=b.arrowLeft=null})):!1},next:function(){b.direction=!0,b.index=S(b.index+1),b.updateItemHTML()},prev:function(){b.direction=!1,b.index=S(b.index-1),b.updateItemHTML()},goTo:function(a){b.direction=a>=b.index,b.index=a,b.updateItemHTML()},preloadNearbyImages:function(){var a,c=b.st.gallery.preload,d=Math.min(c[0],b.items.length),e=Math.min(c[1],b.items.length);for(a=1;a<=(b.direction?e:d);a++)b._preloadItem(b.index+a);for(a=1;a<=(b.direction?d:e);a++)b._preloadItem(b.index-a)},_preloadItem:function(c){if(c=S(c),!b.items[c].preloaded){var d=b.items[c];d.parsed||(d=b.parseEl(c)),y("LazyLoad",d),"image"===d.type&&(d.img=a('<img class="mfp-img" />').on("load.mfploader",function(){d.hasSize=!0}).on("error.mfploader",function(){d.hasSize=!0,d.loadError=!0,y("LazyLoadError",d)}).attr("src",d.src)),d.preloaded=!0}}}});var U="retina";a.magnificPopup.registerModule(U,{options:{replaceSrc:function(a){return a.src.replace(/\.\w+$/,function(a){return"@2x"+a})},ratio:1},proto:{initRetina:function(){if(window.devicePixelRatio>1){var a=b.st.retina,c=a.ratio;c=isNaN(c)?c():c,c>1&&(w("ImageHasSize."+U,function(a,b){b.img.css({"max-width":b.img[0].naturalWidth/c,width:"100%"})}),w("ElementParse."+U,function(b,d){d.src=a.replaceSrc(d,c)}))}}}}),A()});

var modal_id = '#um-gallery-modal';
Dropzone.autoDiscover = false;
var myDropzone = '';

function _um_gallery_album_save( id ){
	var album_name = jQuery('#album_name').val();
	var album_description = jQuery('#album_description').val();
	var album_privacy = jQuery('#album_privacy').val();
	var file_added = false;
	if(myDropzone.files.length > 0){
		file_added = true;
	}
	 jQuery('.um-gallery-message').html('').slideUp();
	 jQuery.ajax({
		type: 'post',
		url: um_gallery_config.ajax_url,
		 data: {
			'action': 'um_gallery_album_update',
			'id': id,
			'album_name' : album_name,
			'album_description' : album_description,
			'album_privacy'  : album_privacy,
			'security': um_gallery_config.nonce
		},
		cache: false,
		success: function(response) {
			var file_response;
			if(response.id){
				jQuery('#um-gallery-save').data('id', response.id);

				if( file_added == true){
					myDropzone.on('sending', function(file, xhr, formData){
						formData.append('album_id', response.id);
						formData.append('action', 'um_gallery_photo_upload');
					});
					myDropzone.processQueue();

					myDropzone.on('complete', function( file ){
						file_response = file.xhr.response;
						file_response = jQuery.parseJSON( file_response );
						file_html = '';
						file_html = '<div class="um-gallery-item um-gallery-col-1-4" id="um-photo-'+ file_response.id +'">'+
					    	'<div class="um-gallery-inner">'+
					            '<a href="' + file_response.image_src + '" class="um-gallery-open-photo" id="um-gallery-item-'+ file_response.id +'" data-title=""  data-id="'+ file_response.id +'"><img src="' + file_response.thumb + '" />'+
					            '</a>'+
					            '<div class="um-gallery-mask">'+
					                '<a href="#" class="um-gallery-delete-item" data-id="'+ file_response.id +'"><i class="um-faicon-trash"></i></a>'+
					            '</div>'+
					        '</div>'+
					    '</div>';
						jQuery('.um-gallery-grid').append( file_html );
						if (typeof um_gallery_images !== 'undefined') {
							um_gallery_images = file_response.gallery_images;
						}
						if( response.new === true){
							_um_gallery_get_album_item( response.id );
						}
					});
				}else{
					if( response.new === true){
						_um_gallery_get_album_item( response.id );
					}
				}
			}
		}
	});
}
function _um_gallery_get_album_item( id ) {
	if( jQuery('#um-album-' + id).length ){
		return false;
	}
	jQuery.ajax({
		type: 'get',
		url: um_gallery_config.ajax_url,
		 data: {
			'action': 'um_gallery_get_album_item',
			'album_id': id,
			'security': um_gallery_config.nonce
		},
		cache: false,
		success: function(response) {
			jQuery('.um-gallery-album-list').append( response );
		}
	});
}
function _um_gallery_edit_photo( id ){
	var caption = jQuery('.um-user-gallery-modify #caption').val();
	var description = jQuery('.um-user-gallery-modify #description').val();
	var default_caption = um_gallery_images[id].caption;
	jQuery.ajax({
		type: 'post',
		url: um_gallery_config.ajax_url,
		 data: {
			'action': 'um_gallery_photo_update',
			'id': id,
			'album_id': um_gallery_config.album_id,
			'caption' : caption,
			'default_caption' : default_caption,
			'description' : description,
			'security': um_gallery_config.nonce
		},
		cache: false,
		success: function(response) {
			//jQuery('#um-gallery-data').html('var um_gallery_images = '+JSON.stringify(response)+';');
			um_gallery_images = response;
			_um_load_image( id );
		}
	});
}
function _um_gallery_enable_edit( id ){

}
function _um_gallery_album_form( id ){
	var modal_id = '#um-gallery-modal';
	jQuery(modal_id).html('<div class="um-gallery-loader"><i class="fa fa-spin fa-spinner"></i></div>');
	jQuery.magnificPopup.open({
	  items: {
		src: jQuery('<div id="um-gallery-modal" class="um-gallery-popup"></div>')
	  },
	  type: 'inline',
	  'mainClass': 'um-gallery-modal-wrapper',

	  // You may add options here, they're exactly the same as for $.fn.magnificPopup call
	  // Note that some settings that rely on click event (like disableOn or midClick) will not work here
	}, 0);
	if(!id){
		id = 0;
	}
	jQuery.ajax({
		type: 'get',
		url: um_gallery_config.ajax_url,
		data: {
			'action': 'um_gallery_get_album_form',
			'album_id': id
		},
		success: function(response) {
			jQuery(modal_id).html(response);
			jQuery(modal_id).animate({'max-width':'740px'}, 'slow');
			myDropzone = new Dropzone("#dropzone", {
				 url: um_gallery_config.ajax_url,
				 autoProcessQueue: false,
				 parallelUploads: 56,
				 method: "post",
				 dictDefaultMessage: um_gallery_config.dictDefaultMessage,
				 queuecomplete: function(){
					jQuery('.um-gallery-message').html(um_gallery_config.upload_complete).slideDown();
					//location.reload();
				 }
			});

		}
	});
}

function _um_gallery_photo_form( id ){
	jQuery.ajax({
		type: 'post',
		url: um_gallery_config.ajax_url,
		 data: {
			'action': 'um_gallery_photo_delete',
			'id': id,
			'security': um_gallery_config.nonce
		},
		cache: false,
		success: function(response) {

		}
	});
}

function _um_gallery_photo_delete( id ){
	jQuery.ajax({
		type: 'post',
		url: um_gallery_config.ajax_url,
		data: {
			'action': 'sp_gallery_um_delete',
			'id': id,
			'album_id': um_gallery_config.album_id,
			'security': um_gallery_config.nonce
		},
		cache: false,
		success: function(response) {
			jQuery.magnificPopup.close();
			jQuery("#um-photo-" + id).slideUp().remove();
			um_gallery_images = response;
		}
	});
}

function _um_gallery_album_delete( id ){
	jQuery.ajax({
		type: 'post',
		url: um_gallery_config.ajax_url,
		data: {
			'action': 'um_gallery_delete_album',
			'id': id,
			'security': um_gallery_config.nonce
		},
		cache: false,
		success: function(response) {
				jQuery("#um-album-" + id).slideUp().remove();
		}
	});
}

function _um_load_info( id ){
	jQuery.ajax({
		type: 'get',
		url: um_gallery_config.ajax_url,
		data: {
			'action': 'um_photo_info',
			'id': id,
			'security': um_gallery_config.nonce
		},
		cache: false,
		success: function(response) {
			jQuery('#um-user-gallery-title').text(response.title);
			jQuery('#um-user-gallery-description').text(response.caption);
			//_um_gallery_comment_height();
		}
	});
}
function _um_load_image( id ){
	if( !id || id == 'undefined'){
		return false;
	}
	var image = jQuery('#um-gallery-item-' + id).attr('href');
	jQuery('#um-gallery-modal,#um-gallery-caption-edit').data('id', id);
	jQuery('.um-user-gallery-image-wrap').css('background-image',  'url(' + image + ')');
	jQuery('#aqm_comment_id').val(id);
	var user_id = um_gallery_images[id].user_id;
	var content = '<div class="um-gallery-header-avatar"><a href="'+um_gallery_users[user_id].link+'">'+um_gallery_users[user_id].avatar+'</a></div>'+
	'<div class=""><a href="'+um_gallery_users[user_id].link+'">'+um_gallery_users[user_id].name+'</a></div>';
	jQuery('.um-user-gallery-user').html(content);
	var caption = um_gallery_images[id].caption;
	//caption.replace("\n", "<br />");
	var description = um_gallery_images[id].description;
	//description = description.replace("\n", "<br />");
	//description = description.split(" ").join("\n")
	jQuery('#um-user-gallery-title').html(caption);
	jQuery('.um-user-gallery-modify #caption').val(caption);
	jQuery('#um-user-gallery-description').html(description);
	jQuery('.um-user-gallery-modify #description').val(description);
	jQuery('.um-user-gallery-modify').hide();
	jQuery('.um-user-gallery-caption,#um-gallery-caption-edit').show();
}
function _um_gallery_open_photo( id ){
	var image = jQuery('#um-gallery-item-' + id).attr('href');
	var modal_layout = '<div id="um-gallery-modal" class="um-gallery-popup" data-id="'+ id +'">'+
	'<div class="um-user-gallery-inner">'+
      	'<div class="um-user-gallery-left">'+
        	'<div class="um-user-gallery-arrow aqm-left-gallery-arrow"><a href="#" data-direction="left"><i class="um-faicon-angle-left" aria-hidden="true"></i></a></div>'+
        	'<div class="um-user-gallery-arrow aqm-right-gallery-arrow"><a href="#" data-direction="right"><i class="um-faicon-angle-right" aria-hidden="true"></i></a></div>'+
        	'<div class="um-user-gallery-image-wrap">'+
            	'<div class="image-holder"></div>'+
            '</div>'+
            '<div class="um-user-gallery-image-options">'+
            	'<span class="um-user-gallery-options">';
					if( um_gallery_config.is_owner ){
                	modal_layout+='<div class="um-user-gallery-normal"><a href="#" class="um-gallery-quick-edit">Edit Photo</a> | <a href="#" class="aqm-delete-gallery-photo">Delete Photo</a>'+
                    '</div>'+
                    '<div class="um-user-gallery-edit" style="display: none;">'+
                    	'Are you sure want to delete this? <a href="#"  class="um-user-gallery-confirm" data-option="yes">Yes</a> | <a href="#" class="um-user-gallery-confirm" data-option="no">No</a>'+
                    '</div>';
					}
                modal_layout+='</span>'+
            '</div>'+
        '</div>'+
      	'<div class="um-user-gallery-right"><div class="um-user-gallery-right-inner">'+
		'<div class="um-user-gallery-user"></div>'+
		'<div class="um-user-gallery-info">'+
			'<div class="um-user-gallery-caption">'+
				'<div class="um-user-gallery-title" id="um-user-gallery-title"></div>'+
				'<div class="um-user-gallery-description" id="um-user-gallery-description"></div>'+
			'</div>';
			if( um_gallery_config.is_owner ){
				 modal_layout+= '<div class="um-user-gallery-modify">'+
				 '<div class="um-caption-text"><input type="hidden" id="caption" /></div>'+
				 '<div class="um-caption-text"><textarea id="description" /></div>'+
				 '<div class="um-caption-text"><input type="submit" id="savePhoto" value="'+ um_gallery_config.save_text +'" /><input type="button" id="cancelPhoto" value="'+ um_gallery_config.cancel_text +'" /></div>'+
				 '</div>'+
				 '<div class="um-gallery-caption-edit-wrapper"><a href="#" id="um-gallery-caption-edit" data-id="">'+ um_gallery_config.edit_text +'</a></div>';
			}
		modal_layout+='</div>'+
		'</div>'+
		'</div>'+
      '</div>'+
	'</div>';
	jQuery.magnificPopup.open({
	  items: {
		src: jQuery(modal_layout)
	  },
	  type: 'inline',
	  'mainClass': 'um-gallery-modal-wrapper',
		callbacks: {
			open: function() {
			  jQuery('body').addClass('gallery-open');
			  var winsize = jQuery(window).width();
			  var modal_size = winsize - (winsize * 0.15);
			  modal_size = Math.round(modal_size);
			  jQuery('#um-gallery-modal').animate({'max-width': modal_size + 'px'}, 'slow');
			  jQuery('.um-user-gallery-image-wrap').css('background-image',  'url(' + image + ')');
			  //_um_load_info( id );
			  _um_load_image( id );
			},
			close: function() {
			  jQuery('body').removeClass('gallery-open');
			}
		  }
	}, 0);
}

function _um_gallery_comment_height(){
	var showChar = 100;
	var ellipsestext = "...";
	var moretext = "more";
	var lesstext = "less";
	jQuery('.cmmnt-content p').each(function() {
		var content = jQuery(this).html();

		if(content.length > showChar) {

			var c = content.substr(0, showChar);
			var h = content.substr(showChar-1, content.length - showChar);

			var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

			jQuery(this).html(html);
		}

	});
	return;

	var slideHeight = 75; // px
	var defHeight = jQuery('.cmmnt').height();
	if(defHeight >= slideHeight){
		jQuery('.cmmnt').css('height' , slideHeight + 'px');
		jQuery('#read-more').append('<a href="#">Click to Read More</a>');
		jQuery('#read-more a').click(function(){
			var curHeight = $('.cmmnt').height();
			if(curHeight == slideHeight){
				jQuery('#wrap').animate({
				  height: defHeight
				}, "normal");
				jQuery('#read-more a').html('Close');
				jQuery('#gradient').fadeOut();
			}else{
				jQuery('.cmmnt').animate({
				  height: slideHeight
				}, "normal");
				jQuery('#read-more a').html('Click to Read More');
				jQuery('#gradient').fadeIn();
			}
			return false;
		});
	}
}
jQuery(window).resize(function($) {
	var winsize = jQuery(window).width();
	var modal_size = winsize - (winsize * 0.15);
	modal_size = Math.round(modal_size);
	jQuery('#um-gallery-modal').animate({'max-width': modal_size + 'px'}, 'slow');
});
jQuery(document).ready(function($) {
	//Open Album Form
    jQuery(document).on("click", ".um-gallery-form,.um-gallery-edit-link", function(event){
		event.preventDefault();
		var id = $(this).data('id');
		_um_gallery_album_form( id );

	});
	jQuery(document).on("click", "#um-gallery-caption-edit,.um-gallery-quick-edit", function(event){
		event.preventDefault();
		var id = $(this).data('id');
		$('.um-user-gallery-modify').slideDown(500);
		$('.um-user-gallery-caption,#um-gallery-caption-edit').slideUp(500);
		//_um_gallery_enable_edit( id );
	});
	jQuery(document).on("click", "#um-gallery-save", function(event){
		event.preventDefault();
		var id = $(this).data('id');
		var type = $(this).data('type');
		if( type === 'album' ){
			_um_gallery_album_save( id );
		}
	});
	jQuery(document).on("click", ".um-delete-album", function(event){
		event.preventDefault();
		var id = $(this).data('id');
		_um_gallery_album_delete( id );
	});
	jQuery(document).on("click",".um-gallery-delete-item", function(event){
		event.preventDefault();
		var id = jQuery(this).data("id");
		var obj = jQuery(this);
		jQuery.ajax({
		  method: "POST",
		  url: um_gallery_config.ajax_url,
		  data: { action: "sp_gallery_um_delete", id: id, 'album_id': um_gallery_config.album_id},
		  success: function(result){
				obj.closest('.um-gallery-item').slideUp().remove();
		  }
		})
	});
	jQuery(document).on("click", ".um-gallery-close,.um-gallery-cancel", function(event){
		event.preventDefault();
		//close modal
		jQuery.magnificPopup.close();
	});
	jQuery(document).on("click", "#savePhoto", function(event){
		event.preventDefault();
		var id = $('#um-gallery-modal').data('id');
		_um_gallery_edit_photo( id );
	});
	jQuery(document).on("click", "#cancelPhoto", function(event){
		event.preventDefault();
		$('.um-user-gallery-modify').slideUp(500);
		$('.um-user-gallery-caption,#um-gallery-caption-edit').slideDown(500);
	});
	jQuery(document).on("click", ".um-gallery-open-photo", function(event){
		event.preventDefault();
		var id = jQuery(this).data('id');
		_um_gallery_open_photo( id );
	});
	jQuery(document).on("click", ".aqm-delete-gallery-photo", function(e){
		e.preventDefault();
		jQuery('.um-user-gallery-normal').slideUp(500);
		jQuery('.um-user-gallery-edit').slideDown(600);
	});
	$(document).on("click", ".um-user-gallery-confirm", function(e){
		e.preventDefault();
		var option = $(this).data('option');
		if(option === 'no'){
			$('.um-user-gallery-normal').slideDown(500);
			$('.um-user-gallery-edit').slideUp(600);
		}else if(option === 'yes'){
			var id =  $('#um-gallery-modal').data('id');
			_um_gallery_photo_delete( id );
		}
	});
	//Click on arrows
	jQuery(document).on("click", ".um-user-gallery-arrow a", function(event){
		event.preventDefault();
		var id =  jQuery('#um-gallery-modal').data('id');
		var direction = $(this).data('direction');
		var adjacent_id = '';
		var previous, next, ids = [];
		jQuery.each(um_gallery_images, function(key, value){
			ids.push(key);
		});
		jQuery.each(ids, function (i, data) {
			var currentId = data;
			if (currentId == id){
				//$before = ($index > 0 ? $array[$index - 1] : $array[count($array)-1]);
				//$after = ($index + 1) < count($array) ? $array[$index + 1] : $array[0];
				next = i > 0 ? ids[i-1] : ids[ids.length - 1];
				previous = (i+1) < ids.length  ? ids[i + 1 ] : ids[0];
			}
		});

		if(direction === 'left'){
			//adjacent_id = jQuery('#um-gallery-item-' + id).closest('.um-gallery-item').prev().find('.um-gallery-open-photo').data('id');
			adjacent_id = previous;
			_um_load_image(adjacent_id);
		}
		if(direction === 'right'){
			//adjacent_id = jQuery('#um-gallery-item-' + id).closest('.um-gallery-item').next().find('.um-gallery-open-photo').data('id');
			adjacent_id = next;
			_um_load_image(adjacent_id);
		}
	});
	//change arrows with keyboard
	jQuery(document).on("keydown", function(e){
	//check if modal is open
	if( $('.mfp-wrap #um-gallery-modal').length){

		//check if we are inside of form field
		if (e.target.tagName.toLowerCase() !== 'input' &&
			e.target.tagName.toLowerCase() !== 'textarea') {

			var id =  jQuery('#um-gallery-modal').data('id');

			  if(e.keyCode == 37) { // left
				adjacent_id = jQuery('#um-gallery-item-' + id).closest('.um-gallery-item').prev().find('.um-gallery-open-photo').data('id');
				_um_load_image(adjacent_id);
			  }
			  else if(e.keyCode == 39) { // right
				adjacent_id = jQuery('#um-gallery-item-' + id).closest('.um-gallery-item').next().find('.um-gallery-open-photo').data('id');
				_um_load_image(adjacent_id);
			  }
			}
		}
	});

});
