// When the user scrolls the page, execute myFunction
window.onscroll = function() {
    myFunction(),
        scrollFunction()
};

// Get the header
var header = document.getElementById("wrapper")

// Get the offset position of the navbar
var sticky = header.offsetTop;
scrollTop = document.getElementById("scrollToTop");

// Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
function myFunction() {
    if (window.pageYOffset > sticky) {
        header.classList.add("sticky")
    } else {
        header.classList.remove("sticky");
    }
}



function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        scrollTop.style.display = "block";
    } else {
        scrollTop.style.display = "none";
    }
}



// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}