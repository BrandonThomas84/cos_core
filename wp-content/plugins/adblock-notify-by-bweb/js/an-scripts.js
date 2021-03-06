/*
 an_scripts.js
 AdBlock Notify
 Copyright: (c) 2014 Brice CAPOBIANCO, b-website.com
 */

jQuery(document).ready(function ($) {
    //define global testing var
    var $an_state = null;

	 /*  Detection
     /* ------------------------------------ */
	 
    $(window).load(function () {

        setTimeout(function () {

            //launch FIRST test (jQuery) - check adsense element height
            if ( $('#adsense.an-sponsored').length > 0 ) {
                if ($('#adsense.an-sponsored .an-advert-banner').outerHeight() == 0 ) {
                    $an_state = true;
                    $('#adsense.an-sponsored').remove();
                }
            }

            //launch SECOND test with fuckadblock script (js file)
        	//Disabled due to too many bug repports
            /*function adBlockDetected() {
				$an_state = true;
				//do action
				an_message_display($an_state);
            }
			function adBlockNotDetected() {
				//do action
				an_message_display($an_state);
			}
			if(typeof fuckAdBlock === 'undefined') {
				adBlockDetected();
			} else {
				fuckAdBlock.onDetected(adBlockDetected);
				fuckAdBlock.onNotDetected(adBlockNotDetected);
			}*/

			an_message_display($an_state);

        }, 500);

    });

    /*  Do action
     /* ------------------------------------ */

    function an_message_display($an_state) {
        if ($an_state === true) {

            //IF MODAL BOX IS ACTIVATED
            if ((anOptions.anOptionChoice == 2 && anOptions.anOptionCookie == 1 && getCookie('anCookie') !== 'true') || (anOptions.anOptionChoice == 2 && anOptions.anOptionCookie == 2)) {

                if (anOptions.anOptionModalBxtitle != '') {
                    var headingColor = 'style="color:' + anOptions.anOptionModalBxtitle + '"';
                } else {
                    var headingColor = '';
                }

                $('#an-Modal').prepend('<h1 ' + headingColor + '>' + anOptions.anModalTitle + '</h1>' + anOptions.anModalText + '<a class="close-reveal-modal">&#215;</a>');

                $('#an-Modal').bind('reveal:open', function () {                    	//on modale box open
                    $('.reveal-modal-bg').css({                                     	//apply custom style
                        'background': anOptions.anOptionModalOverlay
                    });

                    //fixed for IE
                    if (msieversion() == 'IE') {
                        $('#an-Modal').css("left", Math.max(0, (($(window).width() - $('#an-Modal').outerWidth()) / 2) + $(window).scrollLeft()) + "px");
                    }

                });

                $('#an-Modal').reveal({
                    animation: anOptions.anOptionModalEffect,                       	//fade, fadeAndPop, none
                    animationspeed: anOptions.anOptionModalspeed,                  	 	//how fast animtions are
                    closeonbackgroundclick: anOptions.anOptionModalclose,           	//if you click background will modal close?
                    dismissmodalclass: 'close-reveal-modal'                         	//the class of a button or element that will close an open modal
                }).trigger('reveal:open');

                $('#an-Modal').bind('reveal:close', function () {                   	//on modale box close
                    $('#an-Modal p, #an-Modal a').fadeOut(150);                     	//fix for visual elements
                    setCookie('anCookie', 'true', anOptions.anOptionCookieLife, '/');   //set cookie to true
                    setTimeout(function () {
                        $('#an-Modal, .reveal-modal-bg').remove();
                    }, anOptions.anOptionModalspeed);
                });

                //IF PAGE REDIRECT IS ACTIVATED
            } else if (anOptions.anOptionChoice == 3 && anOptions.anPermalink !== 'undefined' && getCookie('anCookie') !== 'true') {
                setCookie('anCookie', 'true', anOptions.anOptionCookieLife, '/');      	//set cookie to true
                window.location.replace(anOptions.anPermalink);                     	//redirect to user page
            }

            //IF AD PLACEHOLDER IS ACTIVATED
            if (anOptions.anAlternativeActivation == true && anOptions.anAlternativeElement != '') {

                $(anOptions.anAlternativeElement).each(function (i, obj) {

                    var $element = $(this);
                    if (($element.outerHeight() == 0) || ($element.size() <= 2)) {

                        if (anOptions.anAlternativeClone < 4) {
                            var elementType = $element[0].tagName;
                            var newElement = document.createElement(elementType),
                                    newElement = $(newElement);
                        } else {
                            var newElement = document.createElement('DIV'),
                                    newElement = $(newElement);
                        }

                        if (anOptions.anAlternativeClone == 1 && anOptions.anAlternativeProperties != '') {

                            var copiedStyles = getStyleObjectCss($element);
                            if (typeof (copiedStyles) == 'undefined') {
                                var copiedStyles = $element.getStyleObject();
                            }
                            newElement.css(copiedStyles);

                            var anAskedCSS = anOptions.anAlternativeProperties.split(' ').join('');
                            console.log(anAskedCSS);
                            var arrayProperties = new Array();
                            arrayProperties = anAskedCSS.split(',');

                            var anKeepCSS = [];
                            $.each(arrayProperties, function (item, value) {
                                var elProperty = newElement.css(value);
                                if (typeof elProperty !== 'undefined') {
                                    if (elProperty !== '') {
                                        anKeepCSS.push(value + ':' + elProperty + ';');
                                    }
                                }
                            });

                            anKeepCSS = anKeepCSS.join('');
                            newElement.removeAttr('style').attr("style", anKeepCSS);

                        } else if (anOptions.anAlternativeClone == 2) {

                            var copiedStyles = getStyleObjectCss($element);
                            if (typeof (copiedStyles) == 'undefined') {
                                var copiedStyles = $element.getStyleObject();
                            }
                            newElement.css(copiedStyles).css(anExcludeRules);
                        } else if (anOptions.anAlternativeClone == 3) {

                            var copiedStyles = $element.getStyleObject();
                            newElement.css(copiedStyles).css(anExcludeRules);

                        }

                        newElement.html(anOptions.anAlternativeText);
                        $element.before(newElement);

                        newElement.addClass('an-alternative').fadeIn(300);

                    }
                    
                });

            }

            an_blocker_counter(['total', 'blocked']);                       	//adblocker detected

        } else {

            //IF AD BLOCKER IS DEACTIVATED
            if (getCookie('anCookie') == 'true') {
                an_blocker_counter(['total', 'deactivated']);					//adblocker deactivated	
                setCookie('anCookie', '', anOptions.anOptionCookieLife, '/');	//set cookie to true
            } else {
                an_blocker_counter(['total']);									//no adblocker	
            }

        }

    }


//COUNT PAGE VIEWS WITH ADBLOCKER
    function an_blocker_counter(value) {
        if (anOptions.anOptionStats != 2) {
            $.post(ajax_object.ajaxurl, {
                action: 'call_an_adblock_counter',
                an_state: value
            });
            return false;
        }
    }

    function msieversion() {
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");
        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))       // If Internet Explorer, return version number
            return 'IE';
    }

    /*  Fetch all DEFINED Element CSS Properties
    /*  Source: http://stackoverflow.com/a/5830517
    /* ------------------------------------ */
    function getStyleObjectCss(element) {
        var sheets = document.styleSheets, o = {};
        for (var i in sheets) {
            try {
                if (typeof (sheets[i].cssRules) != 'undefined') {
                    var rules = sheets[i].rules || sheets[i].cssRules;
                    for (var r in rules) {
                        if (element.is(rules[r].selectorText)) {
                            o = $.extend(o, css2json(rules[r].style), css2json(element.attr('style')));
                        }
                    }
                }
            } catch (e) {
                return;
            }

        }
        return o;
    }

    function css2json(css) {
        var s = {};
        if (!css)
            return s;
        if (css instanceof CSSStyleDeclaration) {
            for (var i in css) {
                if ((css[i]).toLowerCase) {
                    s[(css[i]).toLowerCase()] = (css[css[i]]);
                }
            }
        } else if (typeof css == "string") {
            css = css.split("; ");
            for (var i in css) {
                var l = css[i].split(": ");
                s[l[0].toLowerCase()] = (l[1]);
            }
        }
        return s;
    }

    /*  Fetch ALL Element CSS Properties
    /*  Source: http://stackoverflow.com/a/5830517
    /* ------------------------------------ */
    $.fn.getStyleObject = function () {
        var dom = this.get(0);
        var style;
        var returns = {};
        if (window.getComputedStyle) {
            var camelize = function (a, b) {
                return b.toUpperCase();
            };
            style = window.getComputedStyle(dom, null);
            for (var i = 0, l = style.length; i < l; i++) {
                var prop = style[i];
                var camel = prop.replace(/\-([a-z])/, camelize);
                var val = style.getPropertyValue(prop);
                returns[camel] = val;
            }
            return returns;
        }

        if (style = dom.currentStyle) {
            for (var prop in style) {
                returns[prop] = style[prop];
            }
            ;
            return returns;
        }

        if (style = dom.style) {
            for (var prop in style) {
                if (typeof style[prop] != 'function') {
                    returns[prop] = style[prop];
                }
            }
            return returns;
        }
        return returns;
    }

    /*  Initiate cookies functions
    /* ------------------------------------ */
	function setCookie(cname, cvalue, exdays, cpath) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toGMTString();
        var path = "path=" + cpath;
        document.cookie = cname + "=" + cvalue + "; " + expires + "; " + path;
    }
	function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i].trim();
            if (c.indexOf(name) == 0)
                return c.substring(name.length, c.length);
        }
        return "";
    }

    //All CSS rules to exclude
    var anExcludeRules = {'height': '', 'min-height': '', 'max-height': '', 'orphans': '', 'align-content': '', 'align-items': '', 'align-self': '', 'animation': '', 'animation-play-state': '', 'backface-visibility': '', 'border-collapse': '', 'border-spacing': '', 'box-shadow': '', 'content-box': '', 'clip': '', 'content': '', 'counter-increment': '', 'counter-reset': '', 'cursor': '', 'direction': '', 'empty-cells': '', 'flex': '', 'flex-flow': '', 'font': '', 'image-orientation': '', 'ime-mode': '', 'justify-content': '', 'letter-spacing': '', 'list-style': '', 'marker-offset': '', 'order': '', 'outline': '', 'outline-offset': '', 'page-break-after': '', 'page-break-before': '', 'page-break-inside': '', 'perspective': '', 'perspective-origin': '', 'pointer-events': '', 'quotes': '', 'resize': '', 'table-layout': '', 'text-indent': '', 'text-overflow': '', 'text-shadow': '', 'text-transform': '', 'transform': '', 'transform-origin': '', 'transform-style': '', 'transition': '', 'unicode-bidi': '', 'vertical-align': '', 'white-space': '', 'word-break': '', 'word-spacing': '', 'word-wrap': '', '-moz-appearance': '', '-moz-background-inline-policy': '', '-moz-binding': '', '-moz-box-align': '', '-moz-box-direction': '', '-moz-box-flex': '', '-moz-box-ordinal-group': '', '-moz-box-orient': '', '-moz-box-pack': '', '-moz-columns': '', '-moz-column-fill': '', '-moz-column-gap': '', '-moz-column-rule': '', '-moz-float-edge': '', '-moz-force-broken-image-icon': '', '-moz-hyphens': '', '-moz-image-region': '', '-moz-orient': '', '-moz-outline-radius': '', '-moz-stack-sizing': '', '-moz-tab-size': '', '-moz-text-align-last': '', '-moz-text-decoration-color': '', '-moz-text-decoration-line': '', '-moz-text-decoration-style': '', '-moz-text-size-adjust': '', '-moz-user-focus': '', '-moz-user-input': '', '-moz-user-modify': '', '-moz-user-select': '', '-moz-window-shadow': '', 'clip-path': '', 'clip-rule': '', 'color-interpolation': '', 'color-interpolation-filters': '', 'dominant-baseline': '', 'fill': '', 'fill-opacity': '', 'fill-rule': '', 'filter': '', 'flood-color': '', 'flood-opacity': '', 'image-rendering': '', 'lighting-color': '', 'marker': '', 'mask': '', 'shape-rendering': '', 'stop-color': '', 'stop-opacity': '', 'stroke': '', 'stroke-dasharray': '', 'stroke-dashoffset': '', 'stroke-linecap': '', 'stroke-linejoin': '', 'stroke-miterlimit': '', 'stroke-opacity': '', 'stroke-width': '', 'text-anchor': '', 'text-rendering': '', 'vector-effect': '', 'background-blend-mode': '', 'border-bottom-left-radius': '', 'border-bottom-right-radius': '', 'border-image-outset': '', 'border-image-repeat': '', 'border-image-slice': '', 'border-image-source': '', 'border-image-width': '', 'border-top-left-radius': '', 'border-top-right-radius': '', 'box-sizing': '', 'caption-side': '', 'font-kerning': '', 'font-variant-ligatures': '', 'object-fit': '', 'object-position': '', 'overflow-wrap': '', 'speak': '', 'tab-size': '', 'widows': '', 'zoom': '', '-webkit-appearance': '', '-webkit-background-clip': '', '-webkit-background-composite': '', '-webkit-background-origin': '', '-webkit-background-size': '', '-webkit-border-fit': '', '-webkit-border-image': '', '-webkit-box-align': '', '-webkit-box-decoration-break': '', '-webkit-box-direction': '', '-webkit-box-flex': '', '-webkit-box-flex-group': '', '-webkit-box-lines': '', '-webkit-box-ordinal-group': '', '-webkit-box-orient': '', '-webkit-box-pack': '', '-webkit-box-reflect': '', '-webkit-box-shadow': '', '-webkit-clip-path': '', '-webkit-column-break-after': '', '-webkit-column-break-before': '', '-webkit-column-break-inside': '', '-webkit-column-count': '', '-webkit-column-gap': '', '-webkit-column-rule-color': '', '-webkit-column-rule-style': '', '-webkit-column-rule-width': '', '-webkit-column-span': '', '-webkit-column-width': '', '-webkit-filter': '', '-webkit-font-smoothing': '', '-webkit-highlight': '', '-webkit-hyphenate-character': '', '-webkit-line-box-contain': '', '-webkit-line-break': '', '-webkit-margin-before-collapse': '', '-webkit-margin-after-collapse': '', '-webkit-mask-box-image-source': '', '-webkit-mask-box-image-slice': '', '-webkit-mask-box-image-width': '', '-webkit-mask-box-image-outset': '', '-webkit-mask-box-image-repeat': '', '-webkit-mask': '', '-webkit-mask-composite': '', '-webkit-mask-size': '', '-webkit-perspective-origin-x': '', '-webkit-perspective-origin-y': '', '-webkit-print-color-adjust': '', '-webkit-rtl-ordering': '', '-webkit-tap-highlight-color': '', '-webkit-text-combine': '', '-webkit-text-decorations-in-effect': '', '-webkit-text-emphasis-color': '', '-webkit-text-emphasis-position': '', '-webkit-text-emphasis-style': '', '-webkit-text-fill-color': '', '-webkit-text-orientation': '', '-webkit-text-security': '', '-webkit-text-stroke-color': '', '-webkit-text-stroke-width': '', '-webkit-user-drag': '', '-webkit-user-modify': '', '-webkit-user-select': '', '-webkit-writing-mode': '', '-webkit-app-region': '', 'buffered-rendering': '', 'color-rendering': '', 'marker-end': '', 'marker-mid': '', 'marker-start': '', 'mask-type': '', 'alignment-baseline': '', 'baseline-shift': '', 'kerning': '', 'writing-mode': '', 'glyph-orientation-horizontal': '', 'glyph-orientation-vertical': '', 'paint-order': ''};
//,'width':'','min-width':'','max-width':''	



    /*
     * jQuery Reveal Plugin 1.0
     * www.ZURB.com
     * Copyright 2010, ZURB
     * Free to use under the MIT license.
     * http://www.opensource.org/licenses/mit-license.php
     */

    /*---------------------------
     Listener for data-reveal-id attributes
     ----------------------------*/

    $('a[data-reveal-id]').live('click', function (e) {
        e.preventDefault();
        var modalLocation = $(this).attr('data-reveal-id');
        $('#' + modalLocation).reveal($(this).data());
    });

    /*---------------------------
     Extend and Execute
     ----------------------------*/

    $.fn.reveal = function (options) {

        var defaults = {
            animation: 'fadeAndPop',                                            //fade, fadeAndPop, none
            animationspeed: 350,                                                //how fast animtions are
            closeonbackgroundclick: true,                                       //if you click background will modal close?
            dismissmodalclass: 'close-reveal-modal'                             //the class of a button or element that will close an open modal
        };

        //Extend dem' options
        var options = $.extend({}, defaults, options);

        return this.each(function () {


            /*---------------------------
             Global Variables
             ----------------------------*/
            var modal = $(this),
                    topMeasure = parseInt(modal.css('top')),
                    topOffset = modal.height() + topMeasure,
                    locked = false,
                    modalBG = $('.reveal-modal-bg');

            /*---------------------------
             Create Modal BG
             ----------------------------*/
            if (modalBG.length == 0) {
                modalBG = $('<div class="reveal-modal-bg" />').insertAfter(modal);
            }

            /*---------------------------
             Open & Close Animations
             ----------------------------*/
            //Entrance Animations
            modal.bind('reveal:open', function () {
                modalBG.unbind('click.modalEvent');
                $('.' + options.dismissmodalclass).unbind('click.modalEvent');
                if (!locked) {
                    lockModal();
                    if (options.animation == "fadeAndPop") {
                        modal.css({'top': $(document).scrollTop() - topOffset, 'opacity': 0, 'visibility': 'visible'});
                        modalBG.fadeIn(options.animationspeed / 2);
                        modal.delay(options.animationspeed / 2).animate({
                            "top": $(document).scrollTop() + topMeasure + 'px',
                            "opacity": 1
                        }, options.animationspeed, unlockModal());
                    }
                    if (options.animation == "fade") {
                        modal.css({'opacity': 0, 'visibility': 'visible', 'top': $(document).scrollTop() + topMeasure});
                        modalBG.fadeIn(options.animationspeed / 2);
                        modal.delay(options.animationspeed / 2).animate({
                            "opacity": 1
                        }, options.animationspeed, unlockModal());
                    }
                    if (options.animation == "none") {
                        modal.css({'visibility': 'visible', 'top': $(document).scrollTop() + topMeasure});
                        modalBG.css({"display": "block"});
                        unlockModal()
                    }
                }
                modal.unbind('reveal:open');
            });

            //Closing Animation
            modal.bind('reveal:close', function () {
                if (!locked) {
                    lockModal();
                    if (options.animation == "fadeAndPop") {
                        modalBG.delay(options.animationspeed).fadeOut(options.animationspeed);
                        modal.animate({
                            "top": $(document).scrollTop() - topOffset + 'px',
                            "opacity": 0
                        }, options.animationspeed / 2, function () {
                            modal.css({'top': topMeasure, 'opacity': 1, 'visibility': 'hidden'});
                            unlockModal();
                        });
                    }
                    if (options.animation == "fade") {
                        modalBG.delay(options.animationspeed).fadeOut(options.animationspeed);
                        modal.animate({
                            "opacity": 0
                        }, options.animationspeed, function () {
                            modal.css({'opacity': 1, 'visibility': 'hidden', 'top': topMeasure});
                            unlockModal();
                        });
                    }
                    if (options.animation == "none") {
                        modal.css({'visibility': 'hidden', 'top': topMeasure});
                        modalBG.css({'display': 'none'});
                    }
                }
                modal.unbind('reveal:close');
            });

            /*---------------------------
             Open and add Closing Listeners
             ----------------------------*/
            //Open Modal Immediately
            modal.trigger('reveal:open')

            //Close Modal Listeners
            var closeButton = $('.' + options.dismissmodalclass).bind('click.modalEvent', function () {
                modal.trigger('reveal:close')
            });

            if (options.closeonbackgroundclick) {
                modalBG.css({"cursor": "pointer"})
                modalBG.bind('click.modalEvent', function () {
                    modal.trigger('reveal:close')
                });
            }
            $('body').keyup(function (e) {
                if (e.which === 27) {
                    modal.trigger('reveal:close');
                } // 27 is the keycode for the Escape key
            });


            /*---------------------------
             Animations Locks
             ----------------------------*/
            function unlockModal() {
                locked = false;
            }
            function lockModal() {
                locked = true;
            }

        });//each call
    }//orbit plugin call


//END JQUERY
})