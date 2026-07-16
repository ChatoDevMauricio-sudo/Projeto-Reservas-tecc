package com.escola.reservasapp;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.escola.reservasapp.model.Recurso;

import java.util.List;

public class RecursoAdapter extends RecyclerView.Adapter<RecursoAdapter.VH> {

    public interface OnClique { void clicou(Recurso r); }

    private final Context ctx;
    private List<Recurso> lista;
    private final OnClique callback;

    public RecursoAdapter(Context ctx, List<Recurso> lista, OnClique callback) {
        this.ctx = ctx; this.lista = lista; this.callback = callback;
    }

    public void atualizar(List<Recurso> nova) {
        this.lista = nova;
        notifyDataSetChanged();
    }

    @NonNull @Override
    public VH onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(ctx).inflate(R.layout.item_recurso, parent, false);
        return new VH(v);
    }

    @Override
    public void onBindViewHolder(@NonNull VH h, int pos) {
        Recurso r = lista.get(pos);
        h.nome.setText(r.nome);
        h.categoria.setText(r.categoria);
        Glide.with(ctx)
                .load(r.foto_url == null || r.foto_url.isEmpty() ? null : r.foto_url)
                .placeholder(R.drawable.ic_placeholder)
                .error(R.drawable.ic_placeholder)
                .into(h.foto);
        h.itemView.setOnClickListener(v -> callback.clicou(r));
    }

    @Override public int getItemCount() { return lista.size(); }

    static class VH extends RecyclerView.ViewHolder {
        ImageView foto; TextView nome, categoria;
        VH(@NonNull View v) {
            super(v);
            foto = v.findViewById(R.id.imgFoto);
            nome = v.findViewById(R.id.txtNome);
            categoria = v.findViewById(R.id.txtCategoria);
        }
    }
}
