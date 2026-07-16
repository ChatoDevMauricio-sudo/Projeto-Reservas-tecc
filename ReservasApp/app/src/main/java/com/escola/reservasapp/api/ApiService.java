package com.escola.reservasapp.api;

import com.escola.reservasapp.model.CategoriasResponse;
import com.escola.reservasapp.model.HistoricoResponse;
import com.escola.reservasapp.model.LoginRequest;
import com.escola.reservasapp.model.LoginResponse;
import com.escola.reservasapp.model.RecursosResponse;
import com.escola.reservasapp.model.ReservaRequest;
import com.escola.reservasapp.model.SimpleResponse;

import retrofit2.Call;
import retrofit2.http.Body;
import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.Query;

public interface ApiService {

    @POST("api_login.php")
    Call<LoginResponse> login(@Body LoginRequest body);

    @GET("api_recursos.php")
    Call<RecursosResponse> listarRecursos(@Query("categoria_id") Integer categoriaId,
                                          @Query("busca") String busca);

    @GET("api_categorias.php")
    Call<CategoriasResponse> listarCategorias();

    @POST("api_reservar.php")
    Call<SimpleResponse> reservar(@Body ReservaRequest body);

    @GET("api_historico.php")
    Call<HistoricoResponse> historico(@Query("usuario_id") int usuarioId);
}
