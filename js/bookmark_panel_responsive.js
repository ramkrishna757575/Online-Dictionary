//this js file is responsible to handle the bookmarks section in the page.
//when the screen size is large, the bookmarks section is present on the right side of the words table
//when the screen size becomes small, the bookmarks section is hidden and a button is displayed on the navbar.
//clicking the button opens the bookmarks section as a sidebar.

//the above functionality is handled by adding and removing the appropriate classes from the div of bookmarks section

function hideSidebar() {
	$('#bookmark_responsive').addClass("hide");
}
function showSidebar() {
	$('#bookmark_responsive').removeClass("hide");
}
function check() {
	if ($(window).width()>767) {
		$('#show-sidebar').addClass("hide");
		$('#bookmark_responsive').addClass("col-md-3");
		$('#bookmark_responsive').removeClass("sidebar");
		$('#close-sidebar').addClass("hide");
		$('#bookmark_responsive').removeClass("hide fixed-width");
	}
	else{
		$('#bookmark_responsive').removeClass("col-md-3");
		$('#bookmark_responsive').addClass("sidebar");
		$('#show-sidebar').removeClass("hide")
		$('#close-sidebar').removeClass("hide");
		$('#bookmark_responsive').addClass("hide fixed-width");
	}
}
check();
$(window).resize(function () {
	check();
});