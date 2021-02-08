function select_op(op)
{
    document.getElementById("op").innerHTML = op;
}

let socket;

function f()
{
    socket = new WebSocket("ws://localhost:8080");

    socket.onopen = function(e) {
        alert("[open] Соединение установлено");
    };

    socket.onmessage = function(event) {
        document.getElementById("res").innerHTML = event.data;
    };

    socket.onclose = function(event) {
        if (event.wasClean) {
            alert(`[close] Соединение закрыто чисто, код=${event.code} причина=${event.reason}`);
        } else {
            // например, сервер убил процесс или сеть недоступна
            // обычно в этом случае event.code 1006
            alert('[close] Соединение прервано');
        }
    };

    socket.onerror = function(error) {
        alert(`[error] ${error.message}`);
    };
}

function execute()
{
    let op = encodeURIComponent(document.getElementById("op").innerHTML);
    let arg1 = document.getElementById("arg1").value;
    let arg2 = document.getElementById("arg2").value;

    let msg = `arg1=${arg1}&op=${op}&arg2=${arg2}`;
    socket.send(msg);
}