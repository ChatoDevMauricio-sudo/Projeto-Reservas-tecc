package com.escola.reservasapp.model;

public class Categoria {
    public int id;
    public String nome;

    public Categoria() {}
    public Categoria(int id, String nome) { this.id = id; this.nome = nome; }

    @Override public String toString() { return nome; }
}
