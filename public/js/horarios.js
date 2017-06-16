;(function($, exports) {

    var Horario = function(data) {
        this.data = data;
        this.day = data.getDate();
        this.month = (data.getMonth()+1);
        this.year = data.getFullYear();
        this.hour = data.getHours();
    }

    Horario.prototype.getElemento = function() {
        return $("#" + this.getId());
    }

    Horario.prototype.getId = function() {
        return this.day.toString() + this.month.toString() + this.hour.toString();
    }

    exports.Horario = Horario;

})(jQuery, this);
