package com.escola.reservasapp.model;

public class ReservaRequest {
    public int usuario_id;
    public int recurso_id;
    public String data_reserva;
    public String turno;

    public ReservaRequest(int usuario_id, int recurso_id, String data_reserva, String turno) {
        this.usuario_id = usuario_id;
        this.recurso_id = recurso_id;
        this.data_reserva = data_reserva;
        this.turno = turno;
    }
}
