package com.escola.reservasapp;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.escola.reservasapp.api.RetrofitClient;
import com.escola.reservasapp.model.Categoria;
import com.escola.reservasapp.model.CategoriasResponse;
import com.escola.reservasapp.model.Recurso;
import com.escola.reservasapp.model.RecursosResponse;
import com.escola.reservasapp.util.Sessao;
import com.google.android.material.chip.Chip;
import com.google.android.material.chip.ChipGroup;

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class MainActivity extends AppCompatActivity {
    private static final String TAG = "MainActivity";

    private RecyclerView recycler;
    private RecursoAdapter adapter;
    private ChipGroup chipGroup;
    private EditText edBusca;
    private ProgressBar progress;
    private TextView txtVazio;

    private Integer categoriaSelecionada = null; // null = todas

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        TextView saudacao = findViewById(R.id.txtSaudacao);
        saudacao.setText("Olá, " + Sessao.getNome(this));

        recycler  = findViewById(R.id.recyclerRecursos);
        chipGroup = findViewById(R.id.chipGroup);
        edBusca   = findViewById(R.id.edBusca);
        progress  = findViewById(R.id.progress);
        txtVazio  = findViewById(R.id.txtVazio);

        recycler.setLayoutManager(new LinearLayoutManager(this));
        adapter = new RecursoAdapter(this, new ArrayList<>(), recurso -> {
            Intent it = new Intent(this, DetalhesActivity.class);
            it.putExtra("recurso", recurso);
            startActivity(it);
        });
        recycler.setAdapter(adapter);

        findViewById(R.id.btnBuscar).setOnClickListener(v -> carregarRecursos());
        findViewById(R.id.btnHistorico).setOnClickListener(v ->
                startActivity(new Intent(this, HistoricoActivity.class)));
        Button btnSair = findViewById(R.id.btnSair);
        btnSair.setOnClickListener(v -> {
            Sessao.sair(this);
            startActivity(new Intent(this, LoginActivity.class));
            finish();
        });

        carregarCategorias();
        carregarRecursos();
    }

    private void carregarCategorias() {
        RetrofitClient.getApi().listarCategorias().enqueue(new Callback<CategoriasResponse>() {
            @Override
            public void onResponse(Call<CategoriasResponse> call, Response<CategoriasResponse> resp) {
                chipGroup.removeAllViews();
                adicionarChip("Todas", null, true);
                if (resp.body() != null && resp.body().categorias != null) {
                    for (Categoria c : resp.body().categorias) {
                        adicionarChip(c.nome, c.id, false);
                    }
                }
            }
            @Override public void onFailure(Call<CategoriasResponse> call, Throwable t) { }
        });
    }

    private void adicionarChip(String texto, Integer catId, boolean marcado) {
        Chip chip = new Chip(this);
        chip.setText(texto);
        chip.setCheckable(true);
        chip.setChecked(marcado);
        chip.setOnClickListener(v -> {
            categoriaSelecionada = catId;
            carregarRecursos();
        });
        chipGroup.addView(chip);
    }

    private void carregarRecursos() {
        progress.setVisibility(View.VISIBLE);
        txtVazio.setVisibility(View.GONE);
        String busca = edBusca.getText().toString().trim();

        RetrofitClient.getApi().listarRecursos(categoriaSelecionada, busca)
            .enqueue(new Callback<RecursosResponse>() {
                @Override
                public void onResponse(Call<RecursosResponse> call, Response<RecursosResponse> resp) {
                    progress.setVisibility(View.GONE);
                    List<Recurso> lista = (resp.body() != null && resp.body().recursos != null)
                            ? resp.body().recursos : new ArrayList<>();
                    adapter.atualizar(lista);
                    txtVazio.setVisibility(lista.isEmpty() ? View.VISIBLE : View.GONE);
                }
                @Override
                public void onFailure(Call<RecursosResponse> call, Throwable t) {
                    progress.setVisibility(View.GONE);
                    Log.e(TAG, "Falha ao carregar recursos da API", t);
                    String detalhe = t.getMessage() != null ? t.getMessage() : t.getClass().getSimpleName();
                    Toast.makeText(MainActivity.this,
                            "Erro de conexão: " + detalhe, Toast.LENGTH_LONG).show();
                }
            });
    }

    @Override
    protected void onResume() {
        super.onResume();
        // recarrega ao voltar de uma reserva
        carregarRecursos();
    }
}
