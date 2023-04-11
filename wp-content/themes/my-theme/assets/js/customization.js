

const parentElement = $('.dropdown-menu').parent();

$(parentElement).on('click',function(event){
  $('.dropdown-menu').toggle();
  $('.dropdown-menu li a').css({
color:'black',
  });

  event.stopPropagation();
});


$(document).on('click', function(event) {
    // check if the target of the click event is not a child of the .dropdown-menu element
    if (!$(event.target).closest('.dropdown-menu').length) {
      // if it's not, hide the .dropdown-menu element
      $('.dropdown-menu').hide();
    }
  });