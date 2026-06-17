package br.com.unialfa.model;

public class Aluno {
    private String ra;
    private String nome;
    private String curso;
    private boolean apto;

    public Aluno(String ra, String nome, String curso) {
        this.ra = ra;
        this.nome = nome;
        this.curso = curso;
        this.apto = true;
    }

    // Getters
    public String getRa() { return ra; }
    public String getNome() { return nome; }
    public String getCurso() { return curso; }
    public boolean isApto() { return apto; }

    // Setters
    public void setApto(boolean apto) { this.apto = apto; }
}