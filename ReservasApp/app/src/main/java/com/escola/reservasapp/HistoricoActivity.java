package com.escola.reservasapp;

import android.os.Bundle;
import android.view.View;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.escola.reservasapp.api.RetrofitClient;
import com.escola.reservasapp.model.HistoricoResponse;
import com.escola.reservasapp.util.Sessao;

import java.util.ArrayList;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class HistoricoActivity extends AppCompatActivity {

    private ReservaAdapter adapter;
    private ProgressBar progress;
    private TextView txtVazio;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_historico);

        RecyclerView recycler = findViewById(R.id.recyclerHistorico);
        progress = findViewById(R.id.progressHist);
        txtVazio = findViewById(R.id.txtVazioHist);

        recycler.setLayoutManager(new LinearLayoutManager(this));
        adapter = new ReservaAdapter(this, new ArrayList<>());
        recycler.setAdapter(adapter);

        carregar();
    }

    private void carregar() {
        progress.setVisibility(View.VISIBLE);
        RetrofitClient.getApi().historico(Sessao.getId(this))
            .enqueue(new Callback<HistoricoResponse>() {
                @Override
                public void onResponse(Call<HistoricoResponse> call, Response<HistoricoResponse> resp) {
                    progress.setVisibility(View.GONE);
                    if (resp.body() != null && resp.body().reservas != null
                            && !resp.body().reservas.isEmpty()) {
                        adapter.atualizar(resp.body().reservas);
                        txtVazio.setVisibility(View.GONE);
                    } else {
                        txtVazio.setVisibility(View.VISIBLE);
                    }
                }
                @Override
                public void onFailure(Call<HistoricoResponse> call, Throwable t) {
                    progress.setVisibility(View.GONE);
                    Toast.makeText(HistoricoActivity.this, "Erro de conexão", Toast.LENGTH_LONG).show();
                }
            });
    }
}
