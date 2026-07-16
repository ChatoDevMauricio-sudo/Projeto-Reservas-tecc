package com.escola.reservasapp;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.escola.reservasapp.model.Reserva;

import java.util.List;

public class ReservaAdapter extends RecyclerView.Adapter<ReservaAdapter.VH> {

    private final Context ctx;
    private List<Reserva> lista;

    public ReservaAdapter(Context ctx, List<Reserva> lista) {
        this.ctx = ctx; this.lista = lista;
    }

    public void atualizar(List<Reserva> nova) { this.lista = nova; notifyDataSetChanged(); }

    @NonNull @Override
    public VH onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(ctx).inflate(R.layout.item_reserva, parent, false);
        return new VH(v);
    }

    @Override
    public void onBindViewHolder(@NonNull VH h, int pos) {
        Reserva r = lista.get(pos);
        h.recurso.setText(r.recurso);
        h.info.setText(formatarData(r.data_reserva) + "  •  " + turno(r.turno)
                + "  •  " + r.origem);
        h.status.setText("ativa".equals(r.status) ? "Ativa" : "Cancelada");
    }

    private String turno(String t) {
        if ("manha".equals(t)) return "Manhã";
        if ("tarde".equals(t)) return "Tarde";
        if ("noite".equals(t)) return "Noite";
        return t;
    }

    private String formatarData(String iso) {
        // "2026-07-10" -> "10/07/2026"
        try {
            String[] p = iso.split("-");
            return p[2] + "/" + p[1] + "/" + p[0];
        } catch (Exception e) { return iso; }
    }

    @Override public int getItemCount() { return lista.size(); }

    static class VH extends RecyclerView.ViewHolder {
        TextView recurso, info, status;
        VH(@NonNull View v) {
            super(v);
            recurso = v.findViewById(R.id.txtRecursoHist);
            info    = v.findViewById(R.id.txtInfoHist);
            status  = v.findViewById(R.id.txtStatusHist);
        }
    }
}
