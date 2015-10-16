$(document).ready(function() 
{
	$('[clicktocopy]').click(function()
	{
	  $(this).effect("highlight", {}, 1000);
	});

    $("body").on("copy", "[clicktocopy]", function(e) 
    {
		e.clipboardData.clearData();
		e.clipboardData.setData("text/plain", $(this).html());
		e.preventDefault();
	});

});