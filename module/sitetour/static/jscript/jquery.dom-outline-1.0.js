/**
* Firebug/Web Inspector Outline Implementation using $
* Tested to work in Chrome, FF, Safari. Buggy in IE ;(
* Andrew Childs <ac@glomerate.com>
*
* Example Setup:
* var myClickHandler = function (element) { console.log('Clicked element:', element); }
* var myDomOutline = DomOutline({ onClick: myClickHandler });
*
* Public API:
* myDomOutline.start();
* myDomOutline.stop();
*/

function compileLabelText(element,show_size, width, height) {
    var label = element.tagName.toLowerCase();
    if (element.id) {
        label += '#' + element.id;
    }
    if (element.className) {
        label += ('.' + $.trim(element.className).replace(/ /g, '.')).replace(/\.\.+/g, '.');
    }
    if(show_size){
        return label + ' (' + Math.round(width) + 'x' + Math.round(height) + ')';
    }
    else{
        return label;
    } 
}

function getSectorElement(element){
    var sCurrentSector = $(element).data('currentSector');
    if($(sCurrentSector).length == 1){
        return sCurrentSector;
    }
    else if($(sCurrentSector).length == 0){
        return '';
    }
    else{
        var elementCopy = element;
        var lastSector = sCurrentSector;
        var currentChildSector = '';
        while(true){
            var parent = $(elementCopy).parent().get(0);
            if(parent.tagName.toLowerCase() == 'body'){
                var parentEle = $(element).parent().get(0);
                if(parentEle.tagName.toLowerCase() == 'body'){
                    var sTem = 'body>' + sCurrentSector;
                    if($(sTem).length == 1){
                        return sTem;
                    }
                    sTem = 'body>' + sCurrentSector + ':nth-child(' + ($(element).index() + 1) + ')';
                    if($(sTem).length == 1){
                        return sTem;
                    }
                }
                return '';
            }
            var sParentSector = compileLabelText(parent,false);
            var sTem = sParentSector+ ' ' + sCurrentSector;
            if($(sTem).length == 1){
                return sTem;
            }
            var s = (currentChildSector == '' ? ' ' : '>');
            sTem = sParentSector + '>' + lastSector + ':nth-child(' + ($(elementCopy).index() + 1) + ')' + s + currentChildSector;
            if($(sTem).length == 1){
                return sTem;
            }
            currentChildSector = lastSector + ':nth-child(' + ($(elementCopy).index() + 1) + ')' + s + currentChildSector;
            sCurrentSector = sTem;
            lastSector = sParentSector;
            elementCopy = parent;
        }
    }
    return sCurrentSector;
}

var DomOutline = function (options) {
    options = options || {};

    var pub = {};
    var self = {
        opts: {
            namespace: options.namespace || 'DomOutline',
            borderWidth: options.borderWidth || 2,
            onClick: options.onClick || false
        },
        keyCodes: {
            BACKSPACE: 8,
            ESC: 27,
            DELETE: 46
        },
        active: false,
        initialized: false,
        elements: {}
    };

    function writeStylesheet(css) {
        var element = document.createElement('style');
        element.type = 'text/css';
        document.getElementsByTagName('head')[0].appendChild(element);

        if (element.styleSheet) {
            element.styleSheet.cssText = css; // IE
        } else {
            element.innerHTML = css; // Non-IE
        }
    }

    function initStylesheet() {
        if (self.initialized !== true) {
            var css = '' +
            '.' + self.opts.namespace + ' {' +
            '    background: #09c;' +
            '    position: absolute;' +
            '    z-index: 1000000;' +
            '}' +
            '.' + self.opts.namespace + '_label {' +
            '    background: #09c;' +
            '    border-radius: 2px;' +
            '    color: #fff;' +
            '    font: bold 12px/12px Helvetica, sans-serif;' +
            '    padding: 4px 6px;' +
            '    position: absolute;' +
            '    text-shadow: 0 1px 1px rgba(0, 0, 0, 0.25);' +
            '    z-index: 1000001;' +
            '}';

            writeStylesheet(css);
            self.initialized = true;
        }
    }

    function createOutlineElements() {
        $('.DomOutline_label').remove();
        $('.DomOutline').remove();
        self.elements.label = $('<div></div>').addClass(self.opts.namespace + '_label').appendTo('body');
        self.elements.top = $('<div></div>').addClass(self.opts.namespace).appendTo('body');
        self.elements.bottom = $('<div></div>').addClass(self.opts.namespace).appendTo('body');
        self.elements.left = $('<div></div>').addClass(self.opts.namespace).appendTo('body');
        self.elements.right = $('<div></div>').addClass(self.opts.namespace).appendTo('body');
    }

    function removeOutlineElements() {
        $.each(self.elements, function(name, element) {
            element.remove();
        });
    }

    function getScrollTop() {
        if (!self.elements.window) {
            self.elements.window = $(window);
        }
        return self.elements.window.scrollTop();
    }

    function updateOutlinePosition(e) {
        if (e.target.className.indexOf(self.opts.namespace) !== -1) {
            return;
        }
        pub.element = e.target;
        if($('.block_add_newtour').is(':hover')){
            return;
        }
        var b = self.opts.borderWidth;
        var scroll_top = getScrollTop();
        var pos = pub.element.getBoundingClientRect();
        var top = pos.top + scroll_top;

        var label_text = compileLabelText(pub.element,false, pos.width, pos.height);
        var label_top = Math.max(0, top - 20 - b, scroll_top);
        $(pub.element).data('currentSector',label_text);
        var sSectorElement = getSectorElement(pub.element);
        $(pub.element).data('sector',sSectorElement);
        self.elements.label.text(label_text);
        
        var label_left = Math.max(0, pos.left - b);
        if($(window).width() < label_left + self.elements.label.width() + 20){
            label_left = $(window).width() - self.elements.label.width() - 20;
        }
        
        self.elements.label.css({ top: label_top, left: label_left });
        var temLeft = pos.left + pos.width -2;
        if($(window).width() < temLeft + 2){
            temLeft = $(window).width() - temLeft - 4;
        }
        self.elements.top.css({ top: Math.max(0, top - b) + 2, left: pos.left - b +2 , width: pos.width + b - 4, height: b});
        self.elements.bottom.css({ top: top + pos.height - 2, left: pos.left - b + 2, width: pos.width + b - 4, height: b });
        self.elements.left.css({ top: top - b + 2, left: Math.max(0, pos.left - b) + 2, width: b, height: pos.height + b - 4});
        self.elements.right.css({ top: top - b + 2 , left: /*pos.left + pos.width - 2*/ temLeft, width: b, height: pos.height + (b * 2) -4 });
    }

    function stopOnEscape(e) {
        if (e.keyCode === self.keyCodes.ESC || e.keyCode === self.keyCodes.BACKSPACE || e.keyCode === self.keyCodes.DELETE) {
            pub.stop();
        }

        return false;
    }

    function clickHandler(e) {
        pub.stop();
        self.opts.onClick(pub.element);

        return false;
    }

    pub.start = function () {
        initStylesheet();
        if (self.active !== true) {
            self.active = true;
            createOutlineElements();
            $('body').bind('mousemove.' + self.opts.namespace, updateOutlinePosition);
            $('body').bind('keyup.' + self.opts.namespace, stopOnEscape);
            if (self.opts.onClick) {
                setTimeout(function () {
                    $('body').bind('click.' + self.opts.namespace, clickHandler);
                    }, 50);
            }
        }
    };

    pub.stop = function () {
        self.active = false;
        removeOutlineElements();
        $('body').unbind('mousemove.' + self.opts.namespace)
        .unbind('keyup.' + self.opts.namespace)
        .unbind('click.' + self.opts.namespace);
    };

    return pub;
};

function scrollIntoView(element) {
    var $element, $window, counter, offsetTop, scrollTop, windowHeight;
    $element = $(element);
    if (!$element.length) {
        return false;
    }
    $window = $(window);
    offsetTop = $element.offset().top;
    windowHeight = $window.height();
    scrollTop = Math.max(0, offsetTop - (windowHeight / 2));
    $("body,html").stop(true, true).animate({
        scrollTop: offsetTop
    });
};