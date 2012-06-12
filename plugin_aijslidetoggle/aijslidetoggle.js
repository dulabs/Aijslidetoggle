jQuery.noConflict();

aijslidetoggle = function(wrapper,speed){
 		jQuery(wrapper).slideToggle(speed);
}

aijslidetoggle_accordion = function(groupname,wrapper,speed){
	jQuery(groupname).hide();
	jQuery(wrapper).slideToggle(speed);
}