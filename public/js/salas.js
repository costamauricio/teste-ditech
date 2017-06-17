;(function($, exports) {

    var colors = [
        "#709df4",
        "#54d93d",
        "#ff8440",
        "#e94d33",
        "#c2a463",
        "#fae666",
        "#58ebc2"
    ];

    var Salas = function() {
        this.salas = {};
    }

    Salas.prototype.get = function(id) {
        return this.salas[id];
    }

    Salas.prototype.add = function(id, nome) {

        this.salas[id] = {
            id : id,
            nome : nome,
            cor : colors[Object.keys(this.salas).length] || "#000000"
        }

        var div = document.createElement("div");
        div.appendChild(document.createTextNode(nome));
        div.style["background-color"] = this.salas[id].cor;

        $(".lista-legenda").append(div);
    }

    Salas.prototype.all = function() {
        return this.salas;
    }

    exports.Salas = Salas;
})(jQuery, this);
