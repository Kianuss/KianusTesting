
function menu() {
    const check = document.querySelector("#navmenu");
    const navbar = document.querySelector('nav');
    if (check.checked) {
      navbar.style.display = "block";
      navbar.style.transitionDelay = "250ms";
      navbar.style.transitionDuration = "250ms";
      setTimeout(function(){
        navbar.style.opacity= '100%';
    }, 100); 

    } else {
        navbar.style.transitionDelay = "0";
        navbar.style.transitionDuration = "100ms";
        navbar.style.opacity= '0%';
        setTimeout(function(){
            navbar.style.display = "none";
        }, 500); 
    }
  };   

