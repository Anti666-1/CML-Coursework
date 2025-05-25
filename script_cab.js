let footer = document.querySelector("footer");

document.getElementById("button").addEventListener("click", function(){
    footer.innerHTML = ""; //очищаем футер от прошлого заполнения

    let input = document.querySelectorAll("input");

    if(input[0].value == "" || input[1].value == "" || input[2].value == ""){
        footer.innerHTML = "<h1>Заполните необходимые поля!</h1>";
    } else {
        let html = "";
        html += "<h1>Аккаунт успешно создан</h1><br>";
        
        footer.innerHTML = html;
    }
});