package com.escola.reservasapp;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.escola.reservasapp.api.RetrofitClient;
import com.escola.reservasapp.model.LoginRequest;
import com.escola.reservasapp.model.LoginResponse;
import com.escola.reservasapp.util.Sessao;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class LoginActivity extends AppCompatActivity {
    private static final String TAG = "LoginActivity";

    private EditText edEmail, edSenha;
    private Button btnEntrar;
    private ProgressBar progress;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        // Se já estiver logado, pula direto pra tela principal.
        if (Sessao.logado(this)) {
            irParaMain();
            return;
        }

        setContentView(R.layout.activity_login);
        edEmail   = findViewById(R.id.edEmail);
        edSenha   = findViewById(R.id.edSenha);
        btnEntrar = findViewById(R.id.btnEntrar);
        progress  = findViewById(R.id.progress);

        btnEntrar.setOnClickListener(v -> fazerLogin());
    }

    private void fazerLogin() {
        String email = edEmail.getText().toString().trim();
        String senha = edSenha.getText().toString();
        if (email.isEmpty() || senha.isEmpty()) {
            Toast.makeText(this, "Preencha e-mail e senha", Toast.LENGTH_SHORT).show();
            return;
        }
        progress.setVisibility(View.VISIBLE);
        btnEntrar.setEnabled(false);

        RetrofitClient.getApi().login(new LoginRequest(email, senha))
            .enqueue(new Callback<LoginResponse>() {
                @Override
                public void onResponse(Call<LoginResponse> call, Response<LoginResponse> resp) {
                    progress.setVisibility(View.GONE);
                    btnEntrar.setEnabled(true);
                    if (resp.isSuccessful() && resp.body() != null
                            && "ok".equals(resp.body().status)) {
                        Sessao.salvar(LoginActivity.this, resp.body().usuario);
                        irParaMain();
                    } else {
                        Toast.makeText(LoginActivity.this,
                                "E-mail ou senha inválidos", Toast.LENGTH_LONG).show();
                    }
                }
                @Override
                public void onFailure(Call<LoginResponse> call, Throwable t) {
                    progress.setVisibility(View.GONE);
                    btnEntrar.setEnabled(true);
                    Log.e(TAG, "Falha ao conectar na API", t);
                    String detalhe = t.getMessage() != null ? t.getMessage() : t.getClass().getSimpleName();
                    Toast.makeText(LoginActivity.this,
                            "Erro de conexão: " + detalhe, Toast.LENGTH_LONG).show();
                }
            });
    }

    private void irParaMain() {
        startActivity(new Intent(this, MainActivity.class));
        finish();
    }
}
