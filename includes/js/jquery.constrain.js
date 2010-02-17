/*
	jquery.constrain plugin
	* You may distribute this code under the same license as jQuery (BSD or GPL)
	
	$.constrain usage:
		//limit field to having maximum of one 'p' and four '\'		
		$("#myfield").constrain({
					limit: { "p":1 , "\\":4 }
				});
				
		//prohibit field from having alphabet chars
		$("#myfield").constrain({
					prohibit:	{ regex: "[a-zA-Z]" }
				});		
		//prohibit field from having vowels
		$("#myfield").constrain({
					prohibit:	{ chars: "aeiouAEIOU" }
				});				
		
		//allow field to only have alphabet chars
		$("#myfield").constrain({
					allow:	{ regex: "[a-zA-Z]" }
				});						
	/*
	$.numeric usage:
	This is adapted from number-functions.js at http://www.xaprb.com/blog/2007/07/15/javascript-number-formatting-library-v13-released/
	
	$(".double").numeric({format:"0.0"});	
	$(".double-keyup").numeric({format:"0.000",onblur:false});				
	$(".integer").numeric();
		
	Rev:$090508$	
*/		
		
		
(function($) {
	$.fn.constrain = function(opt) { 
		opt = $.extend(true,{}, {						
			limit: 		{}, //key/value pairs ie {"p":4,"\\":4}
			prohibit:	{chars:"",regex:false},
			allow:		{chars:"",regex:false}			
		},opt);
		
		function isProhibitedByLimit(input,e) {
			var prohibited=false;
			$.each(opt.limit,function(token,idx) {				
				var max = this;												
				if(token.charCodeAt(0)==e.which) {
					prohibited = max < 0 ? false : max < $(input).val().split(token).length;
					return false;//break 'each' iterator
				}
			});
			return prohibited;			
		};
		
		//has prohibit or allow been configured by user?
		function isConfigured(item) { 
			return item.chars.length > 0 || (item.regex && item.regex.length > 0);
		};
		
		//does the prohibit or allow collection find a match given the key?
		function match(item,input,e) {
			var arr = item.chars.split("");
			for(var i in arr) {													
				var token = arr[i];
				if(token.charCodeAt(0)==e.which) {
					return true;
				}
			}								
			if( item.regex) {		
				var re = new RegExp(item.regex);
				if(re.test(String.fromCharCode(e.which))){
					return true;
				}
			}								
			
			return false;
		};
		
		function isProhibited(input,e) {			
			if( e.which == 0 || e.which == 8 || e.which == 27 ) {//always permit space,tab, or escape
				return false;
			}
			
			var prohibit = isConfigured(opt.prohibit) ? match(opt.prohibit,input,e) : false;			
			var allow = isConfigured(opt.allow) ? match(opt.allow,input,e) : true;			
			var limited = isProhibitedByLimit(input,e);									
			return prohibit || !allow || limited;					
		};	
			
		return this.each(function() {
			$(this).keypress( function(e) { 
				if(isProhibited(this,e)){
					e.preventDefault();
				}
			});
						
		});
	};
	//todo use number formatring script from http://www.xaprb.com/blog/2007/07/15/javascript-number-formatting-library-v13-released/
	$.fn.numeric = function(opt) {
		opt = $.extend(true,{}, {						
			onblur		:	true,
			format		:	""	//"0,0.0" for thousand sep (52,456,34.49) or "0.0" for no thousands sep			
		},opt);
		
		var parts = opt.format.split(".");
		var precision = parts.length > 1 ? parts[1].length : false;		
		return this.each(function() {
			var allowRe = "\\d";
			
			if(opt.format.indexOf(".")>-1){
				allowRe+="\\.";
			}
			if(opt.format.indexOf(",")>-1) {
				allowRe+=",";
			}
			var constraintOptions = {
				allow	:	{ regex: "[" + allowRe + "]" },
				limit	:	{".":1} 
			};
			
			$(this).constrain(constraintOptions);
			
			if( precision ) {
				//on the field's blur event, correct the value to the configured precision, rounding if necessary
				//so if precision.num is set to '1', then 14.2563 would be changed to 14.3 on the blur event
				$(this).blur( function(e) {			
					var n = parseFloat($(this).val());
					if(	!isNaN(n) ) {
						var val = $(this).val();
						$(this).val($.formatNumber(val,opt.format));
					}						
				});
				if(!opt.onblur) {
					//on the field's keyup event, correct the value to the configured precision right away
					//this is a bit jarring as the number they just entered is removed if it exceeds the precision value
					
					var prec = new RegExp("\\d+\\.*\\d{0," + precision + "}") ;
					$(this).keyup( function(e) {					
						//since keyup e.which is considering numberkeys differently 
						if(	( e.which <48 && e.which >57 ) || //number keys
								( e.which < 96 && e.which > 105 )) { //number keypad 
								return; 
						}								
						var val = $(this).val();						
						$(this).val(val.match(prec));	//we can't invoke format fn here 						
					});
				}
			}		
								
		});
	};
	
})(jQuery);
				
/* formatNumber function for extension directly on jQuery namespace */				
(function($) {
	$.numericFormat = $.numericFormat || {}; $.numericFormat.formats = $.numericFormat.formats || new Array();
	
	$.extend({
		formatNumber : function(num,format) {

			//hide our internals so the createNewFormat function can recurse on it without requiring the user to ignore the 'context' arg
			function _numberFormat(num,format,context) {		
				
				function createNewFormat(format,formatName) {			
						
					var code = "var " + formatName + " = function(num){\n";			
					
					//todo: internationalization concerns will need to be met here by sanitizing the correct thousands separator out
					code += "num = num.replace(/,/,'');";
					
					// Decide whether the function is a terminal or a pos/neg/zero function
					var formats = format.split(";");
					switch (formats.length) {
						case 1:
							code += createTerminalFormat(format);
							break;
						case 2:
							code += "return (num < 0) ? _numberFormat(num,\""
								+ escape(formats[1])							
								+ "\", 1) : _numberFormat(num,\""
								+ escape(formats[0])
								+ "\", 2);";
							break;
						case 3:
							code += "return (num < 0) ? _numberFormat(num,\""
								+ escape(formats[1])
								+ "\", 1) : ((num == 0) ? _numberFormat(num,\""
								+ escape(formats[2])
								+ "\", 2) : _numberFormat(num,\""
								+ escape(formats[0])
								+ "\", 3));";
							break;
						default:
							code += "throw 'Too many semicolons in format string';";
							break;
					}
					
					return code + "};";
					
				};
				
				function createTerminalFormat(format) {				
				    // If there is no work to do, just return the literal value
				    if (format.length > 0 && format.search(/[0#?]/) == -1) {
				        return "return '" + escape(format) + "';\n";
				    }
				    // Negative values are always displayed without a minus sign when section separators are used.
					
				    var code = "var val = (context == null) ? new Number(num) : Math.abs(num);\n";
				    var thousands = false;
				    var lodp = format;
				    var rodp = "";
				    var ldigits = 0;
				    var rdigits = 0;
				    var scidigits = 0;
				    var scishowsign = false;
				    var sciletter = "";
				    // Look for (and remove) scientific notation instructions, which can be anywhere
				    m = format.match(/\..*(e)([+-]?)(0+)/i);
				    if (m) {
				        sciletter = m[1];
				        scishowsign = (m[2] == "+");
				        scidigits = m[3].length;
				        format = format.replace(/(e)([+-]?)(0+)/i, "");
				    }
				    // Split around the decimal point
				    var m = format.match(/^([^.]*)\.(.*)$/);
				    if (m) {
				        lodp = m[1].replace(/\./g, "");
				        rodp = m[2].replace(/\./g, "");
				    }
				    // Look for %
				    if (format.indexOf('%') >= 0) {
				        code += "val *= 100;\n";
				    }
				    // Look for comma-scaling to the left of the decimal point
				    m = lodp.match(/(,+)(?:$|[^0#?,])/);
				    if (m) {
				        code += "val /= " + Math.pow(1000, m[1].length) + "\n;";
				    }
				    // Look for comma-separators
				    if (lodp.search(/[0#?],[0#?]/) >= 0) {
				        thousands = true;
				    }
				    // Nuke any extraneous commas
				    if ((m) || thousands) {
				        lodp = lodp.replace(/,/g, "");
				    }
				    // Figure out how many digits to the l/r of the decimal place
				    m = lodp.match(/0[0#?]*/);
				    if (m) {
				        ldigits = m[0].length;
				    }
				    m = rodp.match(/[0#?]*/);
				    if (m) {
				        rdigits = m[0].length;
				    }
					
				    // Scientific notation takes precedence over rounding etc
				    if (scidigits > 0) {
				        code += "var sci = toScientific(num,val,"
				            + ldigits + ", " + rdigits + ", " + scidigits + ", " + scishowsign + ");\n"
				            + "var arr = [sci.l, sci.r];\n";
				    }
				    else {
				        // If there is no decimal point, round to nearest integer, AWAY from zero
				        if (format.indexOf('.') < 0) {
				            code += "val = (val > 0) ? Math.ceil(val) : Math.floor(val);\n";
				        }
				        // Numbers are rounded to the correct number of digits to the right of the decimal
				        //code += "var arr = val.round(" + rdigits + ").toFixed(" + rdigits + ").split('.');\n";
				        code += "var arr = round(val," + rdigits + ").toFixed(" + rdigits + ").split('.');\n";
				        // There are at least "ldigits" digits to the left of the decimal, so add zeros if needed.
				        code += "arr[0] = (val < 0 ? '-' : '') + leftPad((val < 0 ? arr[0].substring(1) : arr[0]), "
				            + ldigits + ", '0');\n";
				    }
				    // Add thousands separators
				    if (thousands) {
				        code += "arr[0] = addSeparators(arr[0]);\n";
				    }
					
				    // Insert the digits into the formatting string.  On the LHS, extra digits are copied
				    // into the result.  On the RHS, rounding has chopped them off.
				    code += "arr[0] = reverse(injectIntoFormat(reverse(arr[0]), '" + escape(reverse(lodp)) + "', true));\n";
				    if (rdigits > 0) {
				        code += "arr[1] = injectIntoFormat(arr[1], '" + escape(rodp) + "', false);\n";
				    }
				    if (scidigits > 0) {
				        code += "arr[1] = arr[1].replace(/(\\d{" + rdigits + "})/, '$1" + sciletter + "' + sci.s);\n";
				    }
					
				    return code + "return arr.join('.');\n";
				};
				
				function toScientific(num,val, ldigits, rdigits, scidigits, showsign) {
				    var result = {l:"", r:"", s:""};
				    var ex = "";
				    // Make ldigits + rdigits significant figures
				    var before = Math.abs(val).toFixed(ldigits + rdigits + 1).trim('0');
				    // Move the decimal point to the right of all digits we want to keep,
				    // and round the resulting value off
				    var after = Math.round(num,new Number(before.replace(".", "").replace(
				        new RegExp("(\\d{" + (ldigits + rdigits) + "})(.*)"), "$1.$2"))).toFixed(0);
				    // Place the decimal point in the new string
				    if (after.length >= ldigits) {
				        after = after.substring(0, ldigits) + "." + after.substring(ldigits);
				    }
				    else {
				        after += '.';
				    }
				    // Find how much the decimal point moved.  This is #places to LODP in the original
				    // number, minus the #places in the new number.  There are no left-padded zeroes in
				    // the new number, so the calculation for it is simpler than for the old number.
				    result.s = (before.indexOf(".") - before.search(/[1-9]/)) - after.indexOf(".");
				    // The exponent is off by 1 when it gets moved to the left.
				    if (result.s < 0) {
				        result.s++;
				    }
				    // Split the value around the decimal point and pad the parts appropriately.
				    result.l = (val < 0 ? '-' : '') + leftPad(after.substring(0, after.indexOf(".")), ldigits, "0");
				    result.r = after.substring(after.indexOf(".") + 1);
				    if (result.s < 0) {
				        ex = "-";
				    }
				    else if (showsign) {
				        ex = "+";
				    }
				    result.s = ex + leftPad(Math.abs(result.s).toFixed(0), scidigits, "0");
				    return result;
				};
				

				function reverse(str) {
				    var res = "";
				    for (var i = str.length; i > 0; --i) {
				        res += str.charAt(i - 1);
				    }
				    return res;
				};	
				function escape(string) {
				    return string.replace(/('|\\)/g, "\\$1");
				};
				function leftPad(val, size, ch) {
				    var result = new String(val);
				    if (ch == null) {
				        ch = " ";
				    }
				    while (result.length < size) {
				        result = ch + result;
				    }
				    return result;
				};
					
				function round(num,decimals) {
					if (decimals > 0) {
						var m = num.toFixed(decimals + 1).match(
							new RegExp("(-?\\d*)\.(\\d{" + decimals + "})(\\d)\\d*$"));
						if (m && m.length) {
							return new Number(m[1] + "." + leftPad(Math.round(m[2] + "." + m[3]), decimals, "0"));
						}
					}
					return num;
				};
				
				function addSeparators(val) {
					//return val.reverse().replace(/(\d{3})/g, "$1,").reverse().replace(/^(-)?,/, "$1");
					var s = reverse(val).replace(/(\d{3})/g, "$1,");			
				    return reverse(s).replace(/^(-)?,/, "$1");
				};
				
				function injectIntoFormat(val, format, stuffExtras) {			
				    var i = 0;
				    var j = 0;
				    var result = "";
				    var revneg = val.charAt(val.length - 1) == '-';
				    if ( revneg ) {
				       val = val.substring(0, val.length - 1);
				    }
				    while (i < format.length && j < val.length && format.substring(i).search(/[0#?]/) >= 0) {
				        if (format.charAt(i).match(/[0#?]/)) {
				            // It's a formatting character; copy the corresponding character
				            // in the value to the result
				            if (val.charAt(j) != '-') {
				                result += val.charAt(j);
				            }
				            else {
				                result += "0";
				            }
				            j++;
				        }
				        else {
				            result += format.charAt(i);
				        }
				        ++i;
				    }
				    if ( revneg && j == val.length ) {
				        result += '-';
				    }
				    if (j < val.length) {
				        if (stuffExtras) {
				            result += val.substring(j);
				        }
				        if ( revneg ) {
				             result += '-';
				        }
				    }
				    if (i < format.length) {
				        result += format.substring(i);
				    }			
				    return result.replace(/#/g, "").replace(/\?/g, " ");
				};
				
				//add our dynamic function			
				var formatName = "numFormat" + $.numericFormat.formats.length++;
				eval(createNewFormat(format,formatName));
				//return our new function named by our formatName
				return eval(formatName);
			};		
			
			if(!$.numericFormat.formats[format]) {										
					$.numericFormat.formats[format] = _numberFormat(num,format);
			};						
			return $.numericFormat.formats[format](num);
		}
	});
	
})(jQuery);

