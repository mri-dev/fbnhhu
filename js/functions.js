$(function() {
    // Mobile menü toggler
    $('#menutoggler').click(function(){
      var s = $(this).data('status');
      if (s == 0) {
        $('#topmenu > .page-width-holder > ul').addClass('opened');
        $(this).data('status', 1);
      } else {
        $('#topmenu > .page-width-holder > ul').removeClass('opened');
        $(this).data('status', 0);
      }
    });

    // Login toggler
    $('#loginbox-toggler, #mobile-close-login').click(function(){
      var s = $('#loginbox-toggler').hasClass('hide');

      if (s) {
        $('#loginbox-toggler').removeClass('hide');
        $('#login-box').removeClass('opened');
      } else {
        $('#loginbox-toggler').addClass('hide');
        $('#login-box').addClass('opened');
      }
    });
    // Login close toggler

    $('dl dt label .clone').live('click', function()
    {
	var parent_dt	= $(this).parents('dt').eq(0);
	var cloned_dt	= parent_dt.clone(false);
	var cloned_dd	= parent_dt.next().clone(false);

	$('.clone', cloned_dt).remove();
	$('label', cloned_dt).append('<a href="#" class="delete_clone">[törlés]</a>');;

	cloned_dd.find('input').val('');

	cloned_dt.insertAfter(parent_dt.next());
	cloned_dd.insertAfter(parent_dt.next().next());

	return(false);
    });

    $('dl dt label .delete_clone').live('click', function()
    {
	var parent_dt	= $(this).parents('dt').eq(0);

	parent_dt.next().remove();
	parent_dt.remove();

	return(false);
    });

    $('.clear-on-click').live('click', function()
    {
	if ( this.defaultValue == $(this).val() )
	{
	    $(this).val('');
	}

    }).live('focusout', function()
    {
	if ( !$(this).val() )
	{
	    $(this).val(this.defaultValue);
	}
    });

    $('input.datepicker').datepicker();

    $('.table .ajax-delete').live('click', function()
    {
        if ( confirm('Biztos, hogy törlöd?') )
        {
            var that = $(this);

            $.ajax({
        	'url' : $(this).attr('href'),
        	'dataType' : 'json',
        	'success' : function(ret)
        	{
            	    if ( !ret.ok )
            	    {
                	dialog(ret.mess ? ret.mess : 'Ismeretlen hiba történt.');
            	    }
            	    else
            	    {
			if ( ret.redirect )
			{
			    document.location = decodeURIComponent(ret.redirect);
			}

            		if ( that.parents('tr').length )
            		{
                	    that.parents('tr').remove();
			}
			else
			{
			    that.parents('li').remove();
			}
            	    }
		}
            });
        }

        return(false);
    });

    $('.table tr').live('click', function(target) {
	var a = $('a', $(this)).eq(0);
	var event;

        if ( !$(this).parents('table').eq(0).hasClass('tablednd')
    	     && a.attr('href') != undefined
    	     && !a.hasClass('delete')
    	     && !a.hasClass('disabled-click')
    	     && !a.hasClass('ajax-delete')
    	     && target.target.nodeName.toString().toLowerCase() !== 'img'
    	     && target.target.nodeName.toString().toLowerCase() !== 'a'
    	) {
	    if ( typeof document.createEvent != 'undefined' )
	    {
            	event = document.createEvent('MouseEvent');

		if ( typeof event.initMouseEvent != 'undefined' )
	        {
    		    event.initMouseEvent('click', true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);

            	    a[0].dispatchEvent(event);
        	}
            }

    	    //document.location = a.attr('href');

	    return(false);
	}
    });

    $('.table tr').live('mouseover mouseout', function(event)
    {
	if ( event.type == 'mouseover' )
	    $(this).addClass('selected');

	else
	    $(this).removeClass('selected');
    });

    $('.input_password_with_meter').keyup(function() {
	var value = $(this).val();
	var score = 0;
	var text = '';

	// min 6karakter +24
	score += value.length * 4;

	// minimum 3 szam +5
	if ( value.match(/(.*[0-9].*[0-9].*[0-9])/) )
	{
	    score += 5;
	}

	// minimum 2 spec karakter +15
	if (value.match(/(.*[!,@,#,$,%,^,&,*,?,_,~].*[!,@,#,$,%,^,&,*,?,_,~])/))
	{
	    score += 15;
	}

	// kis nagy-betu
	if (value.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))
	{
	    score += 10;
	}

	if (value.match(/([a-zA-Z])/) && value.match(/([0-9])/))
	{
	    score += 15;
	}

	if (value.match(/^\w+$/) || value.match(/^\d+$/) )
	{
	    score -= 10;
	}

	$('#pmeter div').css('background-color', '');

	if ( score >= 75 )
	{
	    text = 'Kiváló';

	    $('#pmeter div').css('background-color', '#9ab10b');
	}
	else if ( score >= 50 )
	{
	    text = 'Jó';

	    $('#pmeter .pmeter1, #pmeter .pmeter2, #pmeter .pmeter3').css('background-color', '#b5ca28');
	}
	else if ( score >= 25 )
	{
	    text = 'Megfelelő';

	    $('#pmeter .pmeter1, #pmeter .pmeter2').css('background-color', '#d8f044');
	}
	else
	{
	    text = 'Nagyon könnyű';

	    $('#pmeter .pmeter1').css('background-color', 'red');
	}

	$('#pmeter .pmeter1').html(text);

	return score;
    });
});

function number_format (number, decimals, dec_point, thousands_sep)
{
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');

    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
	toFixedFix = function (n, prec)
        {
	    var k = Math.pow(10, prec);

	    return '' + Math.round(n * k) / k;
	};

    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');

    if (s[0].length > 3)
    {
	s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }

    if ((s[1] || '').length < prec)
    {
	s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }

    return s.join(dec);
}

function urlencode (str) {
    str = (str + '').toString();

    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}

function dialog (mess)
{
    $('#dialog').html('<p>' + mess + '</p><a class="close-reveal-modal">x</a>').reveal({animation : 'fade'});
}
