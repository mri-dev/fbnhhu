var slideshow_timer	= null;
var slideshow_num	= null;

$(function()
{
    $(document).live('keydown', function(e)
    {
	switch ( e.keyCode )
	{
	    case 37 :
		if ( $('#slideshow-left a').length > 0 )
		{
		    $('#slideshow-left a').trigger('click');
		}

		break;

	    case 39 :
		if ( $('#slideshow-right a').length > 0 )
		{
		    $('#slideshow-right a').trigger('click');
		}

		break;
	}
    });

    $('#slideshow-left a').live('click', function()
    {
	slide_slideshows(true);

	return(false);
    });

    $('#slideshow-right a').live('click', function()
    {
	slide_slideshows();

	return(false);
    });

    slide_slideshows();
});

slide_default_margins = function()
{
    $('#slideshow-items .slideshow-item').each(function(num, box)
    {
        $(box).css({marginLeft : ($(box).width() * num + 'px')});
    });
}

slide_slideshows = function(prev)
{
    window.clearTimeout(slideshow_timer);

    var width   = $('#slideshow-items .slideshow-item:eq(0)').width();
    var sum     = ($('#slideshow-items .slideshow-item').length - 1);

    if ( slideshow_num !== null )
    {
        for ( var i = 0; i <= sum; i++ )
        {
            var selected_box    = $('#slideshow-items .slideshow-item:eq(' + slideshow_num + ')');
            var box             = $('#slideshow-items .slideshow-item:eq(' + i + ')');
            var marginl		= width * i - (slideshow_num * width);

            if ( typeof prev != 'undefined'
    	    &&   selected_box.prev('.slideshow-item').length > 0 )
            {
                marginl += width;
            }
            else
            if ( typeof prev == 'undefined'
            &&   selected_box.next('.slideshow-item').length > 0 )
	    {
                marginl -= width;
	    }
            else
            {
                if ( typeof prev == 'undefined' )
                    marginl += (width * sum);

                else
                    marginl -= (width * sum);
            }

            box.stop().animate({marginLeft: marginl});
        }
    }
    else
    {
        slide_default_margins();
    }

    if ( typeof prev == 'undefined' )
    {
        slideshow_num = (slideshow_num == null || ++slideshow_num > sum ? 0 : slideshow_num);
    }
    else
    {
        slideshow_num = (--slideshow_num < 0 ? sum : slideshow_num);
    }

    slideshow_timer = window.setTimeout(slide_slideshows, 15000);
}
