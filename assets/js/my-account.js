// function openTab(e, tabid){

// 	var i, tabcontent, tablink;

// 	tabcontent = document.getElementsByClassName("ch-tabcontent");

// 	for(i =0; i < tabcontent.length; i++){
// 		tabcontent[i].style.display = "none";
// 	}

// 	tablink = document.getElementsByClassName("ch-tablink");
// 		for(i =0; i < tablink.length; i++){
// 		tablink[i].className = tablink[i].className.replace("active", "");
// 	}	

// 	document.getElementById(tabid).style.display = 'block';
// 	e.currentTarget.className += 'active';
// }


function openTabs(evt, TabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("ch-tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("ch-tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(TabName).style.display = "block";
    evt.currentTarget.className += " active";
}

document.addEventListener("DOMContentLoaded", () => {
    openTabs();

});

