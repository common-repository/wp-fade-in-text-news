function gopiNoEnterKey(e)
{
    var pK = e ? e.which : window.event.keyCode;
    return pK != 13;
}
document.onkeypress = gopiNoEnterKey;
if (document.layers) document.captureEvents(Event.KEYPRESS);
