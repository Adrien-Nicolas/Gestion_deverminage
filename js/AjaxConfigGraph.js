import {AjaxRequest} from  './AjaxRequest.js';

/**
 * Permet d'ajouter le graphique
 */

var tube$i = document.getElementById('tube$i');
var btnTube$i = document.getElementById('btnTube$i');
var IconBtnTube$i = document.getElementById('iconBtnTube$i');

function ajaxAddGraph() {
    new AjaxRequest({
        url: "php/getGraph.php",
        method: "post",
        parameters: {
            id: $i,
            valid: $valid,
            refresh: document.getElementById('slide').value

        },
        onSuccess: function (res) {
            var divtube = document.createElement('div');
            divtube.innerHTML = res;
            tube$i.appendChild(divtube);
        }, onError: function (status, message) {
            window.alert('Error ' + status + ': ' + message);
        }
    });
}

btnTube$i.addEventListener("click", function () {
    if (!tube$i.classList.contains("dnone")) {
        tube$i.classList.add("dnone");
        IconBtnTube$i.classList.remove("iconBtnRotate");
        IconBtnTube$i.classList.add("iconBtnRotateNone");
        tube$i.innerHTML = "";

    }
    else {
        tube$i.classList.remove("dnone");
        IconBtnTube$i.classList.add("iconBtnRotate");
        IconBtnTube$i.classList.remove("iconBtnRotateNone");
        ajaxAddGraph();
    }
});