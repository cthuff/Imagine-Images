(function($){
  $(document).ready(function(){
    $('.parallax').parallax();
  });

  $(function(){
	$('.parallax').parallax();
  }); // end of document ready

  $(document).ready(function(){
    $('select').formSelect();
  });
    
    $(document).ready(function(){
    $('input.autocomplete').autocomplete({
      data: {
        "Apple": null,
        "Microsoft": null,
        "Google": 'https://placehold.it/250x250'
      },
    });
  });
})(jQuery); // end of jQuery name space
