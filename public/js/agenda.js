;(function($, exports) {

    var firstDay = new Date(),
        salas = null,
        current = null,
        reservas = null;

    firstDay.setDate(firstDay.getDate()-firstDay.getDay());

    $(document).ready(function() {

        /**
         * Carrega as salas
         */
        $.get("salas", function(data) {
            salas = new Salas();

            for (let sala of data) {
                salas.add(sala.id, sala.descricao);
            }

            renderGrid(firstDay.getTime())
        })

        $("#forward").click(function() {

            var date = new Date();
            date.setTime(current);
            date.setDate(date.getDate()+7);

            renderGrid(date.getTime());
        });

        $("#back").click(function() {

            var date = new Date();
            date.setTime(current);
            date.setDate(date.getDate()-7);

            renderGrid(date.getTime());
        })
    })

    function renderGrid(time) {

        current = time;
        reservas = new Reservas(salas);

        var days = [],
            date = new Date(),
            agenda = $("#agenda");

        date.setTime(time);
        agenda.html('');

        var fragment = document.createDocumentFragment(),
            thead = document.createElement("thead"),
            tbody = document.createElement("tbody"),
            trh = document.createElement("tr");

        fragment.appendChild(thead)
        fragment.appendChild(tbody)

        thead.appendChild(trh);
        trh.appendChild(document.createElement("th"))

        /**
         * Monta os dias da semana
         */
        for (let x = 0; x < 7; x++) {

            days.push({
                descr : date.toString().replace(/(\w{3}).*/g, '$1'),
                day : date.getDate(),
                month : date.getMonth()+1,
                year : date.getFullYear()
            });

            date.setDate(date.getDate()+1);

            let th = document.createElement("th"),
                el = days[days.length-1];

            th.classList.add("dia-agenda");
            th.appendChild(document.createTextNode(el.descr + " " + el.day + "/" + el.month))
            trh.appendChild(th);
        }

        /**
         * Monta a agenda
         */
        for (let y = 7; y <= 19; y++) {

            let tr = document.createElement("tr"),
                td = document.createElement("td"),
                content = document.createElement("div");

            content.classList.add("hora-agenda");
            content.appendChild( document.createTextNode(y + ":00") );
            td.appendChild(content);
            tr.appendChild(td);

            for (let day of days) {

                let td = document.createElement("td"),
                    content = document.createElement("div"),
                    add = document.createElement("div"),
                    container = document.createElement("div"),
                    icon = document.createElement("i");

                icon.classList.add("add");
                icon.classList.add("to");
                icon.classList.add("calendar");
                icon.classList.add("icon");
                add.classList.add("celula-agenda-add");
                add.appendChild( icon );
                add.title = "Agendar";

                content.classList.add("celula-agenda");
                td.appendChild(content);
                td.id = day.day.toString() + day.month.toString() + y.toString()

                add.onclick = function() {

                    let horario = new Horario(new Date(day.year, day.month-1, day.day, y));
                    reservas.nova(horario);
                }

                container.classList.add("container-reservas");

                content.appendChild(add);
                content.appendChild(container);
                tr.appendChild(td);
            }

            tbody.appendChild(tr);
        }

        /**
         * Joga para a tela a agenda
         */
        agenda.append(fragment);

        /**
         * Carrega as reservas ja resgitradas
         */
        $.get("reservas", {
            data_inicio: days[0].year + "-" + days[0].month + "-" + days[0].day,
            data_fim: days[6].year + "-" + days[6].month + "-" + days[6].day
        }, function(data) {

            for (let reserva of data) {
                let horario = new Horario(new Date(reserva.inicio));
                reservas.adicionar(horario, salas.get(reserva.sala_id), reserva.id, reserva.responsavel)
            }
        })
    }
})(jQuery, this);
