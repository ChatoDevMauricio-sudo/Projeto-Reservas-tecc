package com.escola.reservasapp;

import android.app.DatePickerDialog;
import android.os.Bundle;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.bumptech.glide.Glide;
import com.escola.reservasapp.api.RetrofitClient;
import com.escola.reservasapp.model.Recurso;
import com.escola.reservasapp.model.ReservaRequest;
import com.escola.reservasapp.model.SimpleResponse;
import com.escola.reservasapp.util.Sessao;

import java.util.Calendar;
import java.util.Locale;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class DetalhesActivity extends AppCompatActivity {

    private Recurso recurso;
    private TextView txtData;
    private Spinner spinnerTurno;
    private String dataEscolhida = "";
    private ProgressBar progress;

    private final String[] turnosLabel = {"Manhã", "Tarde", "Noite"};
    private final String[] turnosValor = {"manha", "tarde", "noite"};

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detalhes);

        recurso = (Recurso) getIntent().getSerializableExtra("recurso");
        if (recurso == null) { finish(); return; }

        ImageView foto = findViewById(R.id.imgDetalhe);
        TextView nome   = findViewById(R.id.txtNomeDet);
        TextView cat    = findViewById(R.id.txtCategoriaDet);
        TextView desc   = findViewById(R.id.txtDescricao);
        TextView setor  = findViewById(R.id.txtSetor);
        txtData         = findViewById(R.id.txtData);
        spinnerTurno    = findViewById(R.id.spinnerTurno);
        progress        = findViewById(R.id.progressDet);

        nome.setText(recurso.nome);
        cat.setText(recurso.categoria);
        desc.setText(recurso.descricao == null || recurso.descricao.isEmpty()
                ? "Sem descrição." : recurso.descricao);
        setor.setText("Setor responsável: " + recurso.setor
                + (recurso.responsavel != null ? " (" + recurso.responsavel + ")" : ""));
        Glide.with(this)
                .load(recurso.foto_url == null || recurso.foto_url.isEmpty() ? null : recurso.foto_url)
                .placeholder(R.drawable.ic_placeholder)
                .error(R.drawable.ic_placeholder)
                .into(foto);

        spinnerTurno.setAdapter(new ArrayAdapter<>(this,
                android.R.layout.simple_spinner_dropdown_item, turnosLabel));

        txtData.setOnClickListener(v -> escolherData());
        Button btnReservar = findViewById(R.id.btnReservar);
        btnReservar.setOnClickListener(v -> reservar());
    }

    private void escolherData() {
        Calendar c = Calendar.getInstance();
        DatePickerDialog dlg = new DatePickerDialog(this, (view, ano, mes, dia) -> {
            dataEscolhida = String.format(Locale.US, "%04d-%02d-%02d", ano, mes + 1, dia);
            txtData.setText(String.format(Locale.US, "%02d/%02d/%04d", dia, mes + 1, ano));
        }, c.get(Calendar.YEAR), c.get(Calendar.MONTH), c.get(Calendar.DAY_OF_MONTH));
        dlg.getDatePicker().setMinDate(System.currentTimeMillis() - 1000);
        dlg.show();
    }

    private void reservar() {
        if (dataEscolhida.isEmpty()) {
            Toast.makeText(this, "Escolha uma data", Toast.LENGTH_SHORT).show();
            return;
        }
        String turno = turnosValor[spinnerTurno.getSelectedItemPosition()];
        int usuarioId = Sessao.getId(this);
        progress.setVisibility(View.VISIBLE);

        ReservaRequest req = new ReservaRequest(usuarioId, recurso.id, dataEscolhida, turno);
        RetrofitClient.getApi().reservar(req).enqueue(new Callback<SimpleResponse>() {
            @Override
            public void onResponse(Call<SimpleResponse> call, Response<SimpleResponse> resp) {
                progress.setVisibility(View.GONE);
                if (resp.isSuccessful() && resp.body() != null && "ok".equals(resp.body().status)) {
                    Toast.makeText(DetalhesActivity.this, "Reserva confirmada!", Toast.LENGTH_LONG).show();
                    finish();
                } else {
                    String msg = resp.body() != null ? resp.body().mensagem : "Não foi possível reservar";
                    Toast.makeText(DetalhesActivity.this, msg, Toast.LENGTH_LONG).show();
                }
            }
            @Override
            public void onFailure(Call<SimpleResponse> call, Throwable t) {
                progress.setVisibility(View.GONE);
                Toast.makeText(DetalhesActivity.this, "Erro de conexão", Toast.LENGTH_LONG).show();
            }
        });
    }
}
