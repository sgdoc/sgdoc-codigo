;/*
 * Copyright 2008 ICMBio
 * Este arquivo é parte do programa SISICMBio
 * O SISICMBio é um software livre; você pode redistribuíção e/ou modifição dentro dos termos
 * da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão
 * 2 da Licença.
 *
 * Este programa é distribuíção na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem
 * uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
 * Licença Pública Geral GNU/GPL em português para maiores detalhes.
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 * junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço
 * www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF)
 * Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
 * */
(function($) {
    $.AutocompleterOnline = function($elem, options) {

        this.cacheData_ = {};
        this.cacheLength_ = 0;
        this.selectClass_ = 'jquery-autocomplete-selected-item';
        this.keyTimeout_ = null;
        this.lastKeyPressed_ = null;
        this.lastProcessedValue_ = null;
        this.lastSelectedValue_ = null;
        this.active_ = false;
        this.finishOnBlur_ = true;
        this.comboBox = null;


        /**
         * Default options for autocomplete plugin
         */
        $.fn.autocompleteonline.defaults = {
            idTypeField: null,
            idComboBox: null,
            setElemOnclick: true,
            paramName: "query",
            paramTypeName: '',
            inputClass: "acInput",
            resultsClass: "acResults",
            loadingClass: "acLoading",
            lineSeparator: "\n\n",
            cellSeparator: "|",
            minChars: 3,
            delay: 1700,
            mustMatch: false,
            matchCase: false,
            matchInside: false,
            matchSubset: false,
            useCache: false,
            maxCacheLength: 0,
            autoFill: false,
            sortResults: false,
            onNoMatch: false,
            extraParams: {}
        /*
                    paramName: "query",
                    inputClass: "acInput",
                    resultsClass: "acResults",
                    loadingClass: "acLoading",
                    lineSeparator: "\n\n",
                    cellSeparator: "|",
                    minChars: 3,
                    delay: 1000,
                    mustMatch: false,
                    matchCase: false,
                    matchInside: false,
                    matchSubset: false,
                    useCache: false,
                    maxCacheLength: 0,
                    autoFill: false,
                    sortResults: false,
                    onNoMatch: false
         */
        };

        /**
         * Assert parameters
         */
        if (!$elem || !($elem instanceof jQuery) || $elem.length !== 1 || $elem.get(0).tagName.toUpperCase() !== 'INPUT') {
            alert('Invalid parameter for jquery.Autocompleter, jQuery object with one element with INPUT tag expected');
            return;
        }

        /**
         * Init and sanitize options
         */
        if (typeof options === 'string') {
            this.options = {
                url:options
            };
        } else {
            this.options = options;
        }
        this.options.maxCacheLength = parseInt(this.options.maxCacheLength);
        if (isNaN(this.options.maxCacheLength) || this.options.maxCacheLength < 1) {
            this.options.maxCacheLength = 1;
        }
        this.options.minChars = parseInt(this.options.minChars);
        if (isNaN(this.options.minChars) || this.options.minChars < 1) {
            this.options.minChars = 1;
        }

        /**
         * Autor : Michael Fernandes Rodrigues
         * Email : cerberosnash@gmail.com
         * Funcao: Criar Combobox para Armazenar os Resultados da Busca.
         */

        if(this.options.idComboBox){
            var setElemOnclick = this.options.setElemOnclick;
            this.comboBox = $('#'+this.options.idComboBox);
            $('#'+this.options.idComboBox).click(function(){
                if(setElemOnclick){
                    if($(this).find(':selected').text()){
                        $elem.val($(this).find(':selected').text());
                    }
                }
            });
            $('#'+this.options.idComboBox).change(function(){
                if(setElemOnclick){
                    $elem.val($(this).find(':selected').text());
                }
            });
        }

        /**
             * Init DOM elements repository
             */
        this.dom = {};

        /**
             * Store the input element we're attached to in the repository, add class
             */
        this.dom.$elem = $elem;
        if (this.options.inputClass) {
            this.dom.$elem.addClass(this.options.inputClass);
        }

        /**
             * Create DOM element to hold results
             */
        this.dom.$results = $('<div></div>').hide();
        if (this.options.resultsClass) {
            this.dom.$results.addClass(this.options.resultsClass);
        }
        this.dom.$results.css({
            position: 'absolute'
        });
        $('body').append(this.dom.$results);

        /**
             * Shortcut to self
             */
        var self = this;

        /**
             * Attach keyboard monitoring to $elem
             */
        $elem.keydown(function(e) {
            self.lastKeyPressed_ = e.keyCode;
            switch(self.lastKeyPressed_) {

                case 38: // up
                    e.preventDefault();
                    if (self.active_) {
                        self.focusPrev();
                    } else {
                        self.activate();
                    }
                    return false;
                    break;

                case 40: // down
                    e.preventDefault();
                    if (self.active_) {
                        self.focusNext();
                    } else {
                        self.activate();
                    }
                    return false;
                    break;

                case 9: // tab
                case 13: // return
                    if (self.active_) {
                        e.preventDefault();
                        self.selectCurrent();
                        return false;
                    }
                    break;

                case 27: // escape
                    if (self.active_) {
                        e.preventDefault();
                        self.finish();
                        return false;
                    }
                    break;

                default:
                    self.activate();

            }
        });
        $elem.blur(function() {
            if (self.finishOnBlur_) {
                setTimeout(function() {
                    self.finish();
                }, 200);
            }
        });

    };

    $.AutocompleterOnline.prototype.position = function() {
        var offset = this.dom.$elem.offset();
        this.dom.$results.css({
            top: offset.top + this.dom.$elem.outerHeight(),
            left: offset.left
        });
    };

    $.AutocompleterOnline.prototype.cacheRead = function(filter) {
        var filterLength, searchLength, search, maxPos, pos;
        if (this.options.useCache) {
            filter = String(filter);
            filterLength = filter.length;
            if (this.options.matchSubset) {
                searchLength = 1;
            } else {
                searchLength = filterLength;
            }
            while (searchLength <= filterLength) {
                if (this.options.matchInside) {
                    maxPos = filterLength - searchLength;
                } else {
                    maxPos = 0;
                }
                pos = 0;
                while (pos <= maxPos) {
                    search = filter.substr(0, searchLength);
                    if (this.cacheData_[search] !== undefined) {
                        return this.cacheData_[search];
                    }
                    pos++;
                }
                searchLength++;
            }
        }
        return false;
    };

    $.AutocompleterOnline.prototype.cacheWrite = function(filter, data) {
        if (this.options.useCache) {
            if (this.cacheLength_ >= this.options.maxCacheLength) {
                this.cacheFlush();
            }
            filter = String(filter);
            if (this.cacheData_[filter] !== undefined) {
                this.cacheLength_++;
            }
            return this.cacheData_[filter] = data;
        }
        return false;
    };

    $.AutocompleterOnline.prototype.cacheFlush = function() {
        this.cacheData_ = {};
        this.cacheLength_ = 0;
    };

    $.AutocompleterOnline.prototype.callHook = function(hook, data) {
        var f = this.options[hook];
        if (f && $.isFunction(f)) {
            return f(data, this);
        }
        return false;
    };

    $.AutocompleterOnline.prototype.activate = function() {
        var self = this;
        var activateNow = function() {
            self.activateNow();
        };
        var delay = parseInt(this.options.delay);
        if (isNaN(delay) || delay <= 0) {
            delay = 250;
        }
        if (this.keyTimeout_) {
            clearTimeout(this.keyTimeout_);
        }
        this.keyTimeout_ = setTimeout(activateNow, delay);
    };

    $.AutocompleterOnline.prototype.activateNow = function() {
        var value = this.dom.$elem.val();
        if (value !== this.lastProcessedValue_ && value !== this.lastSelectedValue_) {
            if (value.length >= this.options.minChars) {
                this.active_ = true;
                this.lastProcessedValue_ = value;
                this.fetchData(value);
            }
        }
    };

    $.AutocompleterOnline.prototype.fetchData = function(value) {
        if (this.options.data) {
            this.filterAndShowResults(this.options.data, value);
        } else {
            var self = this;
            this.fetchRemoteData(value, function(remoteData) {
                self.filterAndShowResults(remoteData, value);
            });
        }
    };

    $.AutocompleterOnline.prototype.fetchRemoteData = function(filter, callback) {
        var data = this.cacheRead(filter);
        if (data) {
            callback(data);
        } else {
            var self = this;
            this.dom.$elem.addClass(this.options.loadingClass);
            var ajaxCallback = function(data) {
                var parsed = false;
                if (data !== false) {
                    parsed = self.parseRemoteData(data);
                    self.cacheWrite(filter, parsed);
                }
                self.dom.$elem.removeClass(self.options.loadingClass);
                callback(parsed);
            };
            $.ajax({
                url: this.makeUrl(filter),
                success: ajaxCallback,
                error: function() {
                    ajaxCallback(false);
                }
            });
        }
    };

    $.AutocompleterOnline.prototype.setExtraParam = function(name, value) {
        var index = $.trim(String(name));
        if (index) {
            if (!this.options.extraParams) {
                this.options.extraParams = {};
            }
            if (this.options.extraParams[index] !== value) {
                this.options.extraParams[index] = value;
                this.cacheFlush();
            }
        }
    };

    $.AutocompleterOnline.prototype.makeUrl = function(param) {
        var self = this;
        var paramName = this.options.paramName || 'query';
        var url = this.options.url;
        var params = $.extend({}, this.options.extraParams);
        // If options.paramName === false, append query to url
        // instead of using a GET parameter
        if (this.options.paramName === false) {
            url += encodeURIComponent(param);
        } else {
            params[paramName] = param;
        }
        var urlAppend = [];
        $.each(params, function(index, value) {
            urlAppend.push(self.makeUrlParam(index, value));
        });
        if (urlAppend.length) {
            url += url.indexOf('?') == -1 ? '?' : '&';
            url += urlAppend.join('&');
        }

        //return url;
        if(this.options.paramTypeName){
            return url + '&'+this.options.paramTypeName+'=' + $('#'+this.options.idTypeField).val();
        }
        return url;
    };

    $.AutocompleterOnline.prototype.makeUrlParam = function(name, value) {
        return String(name) + '=' + encodeURIComponent(value);
    }

    $.AutocompleterOnline.prototype.parseRemoteData = function(remoteData) {
        var results = [];
        var text = String(remoteData).replace('\r\n', '\n');
        var i, j, data, line, lines = text.split('\n');
        var value;
        for (i = 0; i < lines.length; i++) {
            line = lines[i].split('|');
            data = [];
            for (j = 0; j < line.length; j++) {
                data.push(unescape(line[j]));
            }
            value = data.shift();
            results.push({
                value: unescape(value),
                data: data
            });
        }
        return results;
    };

    $.AutocompleterOnline.prototype.filterAndShowResults = function(results, filter) {
        this.showResults(this.filterResults(results, filter), filter);
    };

    $.AutocompleterOnline.prototype.filterResults = function(results, filter) {

        var filtered = [];
        var value, data, i, result, type;
        var regex, pattern, attributes = '';

        for (i = 0; i < results.length; i++) {
            result = results[i];
            type = typeof result;
            if (type === 'string') {
                value = result;
                data = {};
            } else if ($.isArray(result)) {
                value = result.shift();
                data = result;
            } else if (type === 'object') {
                value = result.value;
                data = result.data;
            }
            value = String(value);
            // Condition below means we do NOT do empty results
            if (value) {
                if (typeof data !== 'object') {
                    data = {};
                }
                // pattern = String(filter);
                /* if (!this.options.matchInside) {
                    pattern = '^' + pattern;
                }
                if (!this.options.matchCase) {
                    attributes = 'i';
                }*/
                // regex = new RegExp(pattern, attributes);
                //if (regex.test(value)) {
                filtered.push({
                    value: value,
                    data: data
                });
            //}
            }
        }

        if (this.options.sortResults) {
            return this.sortResults(filtered);
        }

        return filtered;

    };

    $.AutocompleterOnline.prototype.sortResults = function(results) {
        var self = this;
        if ($.isFunction(this.options.sortFunction)) {
            results.sort(this.options.sortFunction);
        } else {
            results.sort(function(a, b) {
                return self.sortValueAlpha(a, b);
            });
        }
        return results;
    };

    $.AutocompleterOnline.prototype.sortValueAlpha = function(a, b) {
        a = String(a.value);
        b = String(b.value);
        if (!this.options.matchCase) {
            a = a.toLowerCase();
            b = b.toLowerCase();
        }
        if (a > b) {
            return 1;
        }
        if (a < b) {
            return -1;
        }
        return 0;
    };

    $.AutocompleterOnline.prototype.showResults = function(results, filter) {
        var self = this;
        var $ul = $('<ul></ul>');
        var i, result, $li, extraWidth, first = false, $first = false;
        var numResults = results.length;
        var newOptions = {};
        
        for (i = 0; i < numResults; i++) {
      
            result = results[i];
            
            if(this.options.idComboBox){
                newOptions[result.data] = result.value ;
            }else{
            
                $li = $('<li>' + this.showResult(result.value, result.data) + '</li>');
                $li.data('value', result.value);
                $li.data('data', result.data);
                $li.click(function() {
                    var $this = $(this);
                    self.selectItem($this);
                }).mousedown(function() {
                    self.finishOnBlur_ = false;
                }).mouseup(function() {
                    self.finishOnBlur_ = true;
                });
                $ul.append($li);
                if (first === false) {
                    first = String(result.value);
                    $first = $li;
                    $li.addClass(this.options.firstItemClass);
                }
                if (i == numResults - 1) {
                    $li.addClass(this.options.lastItemClass);
                }
            }
            
        }
        if(this.options.idComboBox){
            var options = this.comboBox.attr('options');
            $('option', this.comboBox).remove();
            $.each(newOptions, function(val, text) {
                options[options.length] = new Option(text, val);
            });
        }else{
            this.position();

            this.dom.$results.html($ul).show();
            extraWidth = this.dom.$results.outerWidth() - this.dom.$results.width();
            this.dom.$results.width(this.dom.$elem.outerWidth() - extraWidth);
            $('li', this.dom.$results).hover(
                function() {
                    self.focusItem(this);
                },
                function() {/* void */}
                );
            if (this.autoFill(first, filter)) {
                this.focusItem($first);
            }
        }
   
    };

    $.AutocompleterOnline.prototype.showResult = function(value, data) {
        if ($.isFunction(this.options.showResult)) {
            return this.options.showResult(value, data);
        } else {
            return value;
        }
    };

    $.AutocompleterOnline.prototype.autoFill = function(value, filter) {
        var lcValue, lcFilter, valueLength, filterLength;
        if (this.options.autoFill && this.lastKeyPressed_ != 8) {
            lcValue = String(value).toLowerCase();
            lcFilter = String(filter).toLowerCase();
            valueLength = value.length;
            filterLength = filter.length;
            if (lcValue.substr(0, filterLength) === lcFilter) {
                this.dom.$elem.val(value);
                this.selectRange(filterLength, valueLength);
                return true;
            }
        }
        return false;
    };

    $.AutocompleterOnline.prototype.focusNext = function() {
        this.focusMove(+1);
    };

    $.AutocompleterOnline.prototype.focusPrev = function() {
        this.focusMove(-1);
    };

    $.AutocompleterOnline.prototype.focusMove = function(modifier) {
        var i, $items = $('li', this.dom.$results);
        modifier = parseInt(modifier);
        for (var i = 0; i < $items.length; i++) {
            if ($($items[i]).hasClass(this.selectClass_)) {
                this.focusItem(i + modifier);
                return;
            }
        }
        this.focusItem(0);
    };

    $.AutocompleterOnline.prototype.focusItem = function(item) {
        var $item, $items = $('li', this.dom.$results);
        if ($items.length) {
            $items.removeClass(this.selectClass_).removeClass(this.options.selectClass);
            if (typeof item === 'number') {
                item = parseInt(item);
                if (item < 0) {
                    item = 0;
                } else if (item >= $items.length) {
                    item = $items.length - 1;
                }
                $item = $($items[item]);
            } else {
                $item = $(item);
            }
            if ($item) {
                $item.addClass(this.selectClass_).addClass(this.options.selectClass);
            }
        }
    };

    $.AutocompleterOnline.prototype.selectCurrent = function() {
        var $item = $('li.' + this.selectClass_, this.dom.$results);
        if ($item.length == 1) {
            this.selectItem($item);
        } else {
            this.finish();
        }
    };

    $.AutocompleterOnline.prototype.selectItem = function($li) {
        var value = $li.data('value');
        var data = $li.data('data');
        var displayValue = this.displayValue(value, data);
        this.lastProcessedValue_ = displayValue;
        this.lastSelectedValue_ = displayValue;
        this.dom.$elem.val(displayValue).focus();
        this.setCaret(displayValue.length);
        this.callHook('onItemSelect', {
            value: value,
            data: data
        });
        this.finish();
    };

    $.AutocompleterOnline.prototype.displayValue = function(value, data) {
        if ($.isFunction(this.options.displayValue)) {
            return this.options.displayValue(value, data);
        } else {
            return value;
        }
    };

    $.AutocompleterOnline.prototype.finish = function() {
        if (this.keyTimeout_) {
            clearTimeout(this.keyTimeout_);
        }
        if (this.dom.$elem.val() !== this.lastSelectedValue_) {
            if (this.options.mustMatch) {
                this.dom.$elem.val('');
            }
            this.callHook('onNoMatch');
        }
        this.dom.$results.hide();
        this.lastKeyPressed_ = null;
        this.lastProcessedValue_ = null;
        if (this.active_) {
            this.callHook('onFinish');
        }
        this.active_ = false;
    };

    $.AutocompleterOnline.prototype.selectRange = function(start, end) {
        var input = this.dom.$elem.get(0);
        if (input.setSelectionRange) {
            input.focus();
            input.setSelectionRange(start, end);
        } else if (this.createTextRange) {
            var range = this.createTextRange();
            range.collapse(true);
            range.moveEnd('character', end);
            range.moveStart('character', start);
            range.select();
        }
    };

    $.AutocompleterOnline.prototype.setCaret = function(pos) {
        this.selectRange(pos, pos);
    };

    /**
         * autocomplete plugin
         */
    $.fn.autocompleteonline = function(options) {

        var o = $.extend({}, $.fn.autocompleteonline.defaults, options);
        return this.each(function() {
            var $this = $(this);
            var ac = new $.AutocompleterOnline($this, o);
            $this.data('autocompleteonline', ac);
        });

    };

})(jQuery);