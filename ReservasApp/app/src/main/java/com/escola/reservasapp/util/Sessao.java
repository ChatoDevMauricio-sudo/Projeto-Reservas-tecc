package com.escola.reservasapp.util;

import android.content.Context;
import android.content.SharedPreferences;

import com.escola.reservasapp.model.Usuario;

/** Guarda o usuário logado localmente (SharedPreferences). */
public class Sessao {
    private static final String PREF = "sessao_reservas";

    private static SharedPreferences prefs(Context c) {
        return c.getSharedPreferences(PREF, Context.MODE_PRIVATE);
    }

    public static void salvar(Context c, Usuario u) {
        prefs(c).edit()
                .putInt("id", u.id)
                .putString("nome", u.nome)
                .putString("email", u.email)
                .putString("tipo", u.tipo)
                .apply();
    }

    public static int getId(Context c)    { return prefs(c).getInt("id", 0); }
    public static String getNome(Context c){ return prefs(c).getString("nome", ""); }
    public static boolean logado(Context c){ return getId(c) > 0; }

    public static void sair(Context c) { prefs(c).edit().clear().apply(); }
}
