package br.com.unialfa.model;

public class Usuario {

    // Atributos privados (Encapsulamento)
    private Long id;
    private String nome;
    private String login;
    private String senha;
    private String perfil; // Vai receber "ADMIN" ou "OPERADOR"

    // Construtor vazio (Necessário para os frameworks e o DAO)
    public Usuario() {
    }

    // Construtor completo para facilitar a criação de objetos
    public Usuario(Long id, String nome, String login, String senha, String perfil) {
        this.id = id;
        this.nome = nome;
        this.login = login;
        this.senha = senha;
        this.perfil = perfil;
    }

    // --- Getters e Setters ---

    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public String getNome() {
        return nome;
    }

    public void setNome(String nome) {
        this.nome = nome;
    }

    public String getLogin() {
        return login;
    }

    public void setLogin(String login) {
        this.login = login;
    }

    public String getSenha() {
        return senha;
    }

    public void setSenha(String senha) {
        this.senha = senha;
    }

    public String getPerfil() {
        return perfil;
    }

    public void setPerfil(String perfil) {
        this.perfil = perfil;
    }

    // Este método ajuda caso você queira listar os usuários num ComboBox ou JList no futuro
    @Override
    public String toString() {
        return this.nome + " - Perfil: " + this.perfil;
    }
}