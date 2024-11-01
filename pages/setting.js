function FadeIn_submit()
{
	if(document.FadeIn_form.FadeIn_text.value=="")
	{
		alert(FadeIn_adminscripts.FadeIn_text);
		document.FadeIn_form.FadeIn_text.focus();
		return false;
	}
	else if(document.FadeIn_form.FadeIn_link.value=="")
	{
		alert(FadeIn_adminscripts.FadeIn_link);
		document.FadeIn_form.FadeIn_link.focus();
		return false;
	}
	else if(document.FadeIn_form.FadeIn_status.value=="")
	{
		alert(FadeIn_adminscripts.FadeIn_status);
		document.FadeIn_form.FadeIn_status.focus();
		return false;
	}
	else if(document.FadeIn_form.FadeIn_group.value=="")
	{
		alert(FadeIn_adminscripts.FadeIn_group);
		document.FadeIn_form.FadeIn_group.focus();
		return false;
	}
	else if(document.FadeIn_form.FadeIn_status.value=="")
	{
		alert(FadeIn_adminscripts.FadeIn_status);
		document.FadeIn_form.FadeIn_status.focus();
		return false;
	}
	else if(document.FadeIn_form.FadeIn_order.value=="")
	{
		alert(FadeIn_adminscripts.FadeIn_order);
		document.FadeIn_form.FadeIn_order.focus();
		return false;
	}
	else if(isNaN(document.FadeIn_form.FadeIn_order.value))
	{
		alert(FadeIn_adminscripts.FadeIn_order);
		document.FadeIn_form.FadeIn_order.focus();
		return false;
	}
	_FadeIn_escapeVal(document.FadeIn_form.FadeIn_text,'<br>');
}

function _FadeIn_delete(id)
{
	if(confirm(FadeIn_adminscripts.FadeIn_delete))
	{
		document.frm_FadeIn_display.action="options-general.php?page=wp-fade-in-text-news&ac=del&did="+id;
		document.frm_FadeIn_display.submit();
	}
}	

function _FadeIn_redirect()
{
	window.location = "options-general.php?page=wp-fade-in-text-news";
}

function _FadeIn_escapeVal(textarea,replaceWith)
{
	textarea.value = escape(textarea.value) //encode textarea strings carriage returns
	for(i=0; i<textarea.value.length; i++)
	{
		//loop through string, replacing carriage return encoding with HTML break tag
		if(textarea.value.indexOf("%0D%0A") > -1)
		{
			//Windows encodes returns as \r\n hex
			textarea.value=textarea.value.replace("%0D%0A",replaceWith)
		}
		else if(textarea.value.indexOf("%0A") > -1)
		{
			//Unix encodes returns as \n hex
			textarea.value=textarea.value.replace("%0A",replaceWith)
		}
		else if(textarea.value.indexOf("%0D") > -1)
		{
			//Macintosh encodes returns as \r hex
			textarea.value=textarea.value.replace("%0D",replaceWith)
		}
	}
	textarea.value=unescape(textarea.value) //unescape all other encoded characters
}

function _FadeIn_help()
{
	window.open("http://www.gopiplus.com/work/2011/04/22/wordpress-plugin-wp-fadein-text-news/");
}