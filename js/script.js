var app_name = "Quiz Panel";
document.onscroll = function(){
  if(window.scrollY < 30){
    document.getElementsByClassName('navbar')[0].classList.remove("bg-primary");
  }
  else {
    document.getElementsByClassName('navbar')[0].classList.add("bg-primary");
  }
}