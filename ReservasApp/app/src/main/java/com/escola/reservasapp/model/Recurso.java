package com.escola.reservasapp.model;

import java.io.Serializable;

public class Recurso implements Serializable {
    public int id;
    public String nome;
    public String descricao;
    public String foto_url;
    public int categoria_id;
    public String categoria;
    public int setor_id;
    public String setor;
    public String responsavel;
}
