setTimeout(function(){
    let msg = document.getElementById("regMsg");

    if(msg){
        msg.style.opacity =  "0";
        setTimeout(() => {
            msg.remove();
        }, 500);
    }
}, 3000); // 3sec