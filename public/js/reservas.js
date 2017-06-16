;(function($, exports) {

    var horario = null,
        inputSalas = null,
        salas = null;

    var Reservas = function(s) {

        this.reservas = {};
        salas = s;

        inputSalas = $("#salas-disponiveis");
    }

    Reservas.prototype.nova = function(hora) {

        var _this = this;
        inputSalas[0].length = 0;

        for (let sala of Object.keys(salas.all())) {

            let s = salas.get(sala);
            if (this.reservado(hora, s)) {
                continue;
            }

            inputSalas[0].add(new Option(s.nome, s.id));
        }

        if (inputSalas[0].length == 0) {
            return alert("Nenhuma sala disponível no horário selecionado.");
        }

        $("#confirmar-reserva").off();
        $("#confirmar-reserva").click(function() {
            $.post("reservas/reservar", {
                horario : hora.year + "-" + hora.month + "-" + hora.day + " " + hora.hour + ":00",
                sala : inputSalas.val()
            }, function(data) {

                _this.adicionar(hora, salas.get(inputSalas.val()), data.id, true);
                $('#adicionar-reserva').modal('hide')
            })
        });

        $("#horario-reservar").text(hora.day + "/" + hora.month + "/" + hora.year + " " + hora.hour + ":00")

        $('#adicionar-reserva').modal('show')
    }

    /**
     * Adiciona uma nova reserva
     */
    Reservas.prototype.adicionar = function(horario, sala, id, responsavel) {

        var _this = this;

        let segment = document.createElement("div");
        segment.appendChild(document.createTextNode(""))
        segment.style["background-color"] = sala.cor;

        segment.onclick = function() {

            $.get("reservas/detalhes", { reserva: id }, function(data) {

                let buttonRemover = $("#excluir-reserva");

                buttonRemover.hide();
                responsavel && buttonRemover.show();
                buttonRemover.off();
                buttonRemover.click(function() {
                    if (confirm("Confirmar exclusão da reserva?")) {
                        $.get("reservas/remover", { reserva : id }, function(data) {

                            if (data.erro) {
                                return alert("Erro ao remover a reserva.");
                            }

                            $("#detalhes-reserva").modal("hide");
                            _this.remover(horario, sala, segment);
                        })
                    }
                })

                $("#detalhes-reserva").modal("show");
            }).fail(function(err) {
                alert("Erro ao buscar reserva.")
            })
        }

        if (responsavel) {
            let divChild = document.createElement("div");
            divChild.style["background-image"] = "url(\"/img/fundo.png\")";
            segment.appendChild(divChild);
        }

        horario.getElemento().find('.container-reservas').append(segment)

        this.reservas[horario.getId()] = this.reservas[horario.getId()] || {};

        this.reservas[horario.getId()][sala.id] = {
            horaraio : horario,
            sala : sala,
            id : id,
            responsavel: responsavel
        }
    }

    /**
     * Verifica se existe a reserva
     */
    Reservas.prototype.reservado = function(horario, sala) {

        if (this.reservas[horario.getId()] == undefined || this.reservas[horario.getId()][sala.id] == undefined) {
            return false;
        }

        return true;
    }

    Reservas.prototype.remover = function(horario, sala, elemento) {

        delete this.reservas[horario.getId()][sala.id];

        horario.getElemento().find(".container-reservas")[0].removeChild(elemento);
    }

    exports.Reservas = Reservas;

})(jQuery, this);
