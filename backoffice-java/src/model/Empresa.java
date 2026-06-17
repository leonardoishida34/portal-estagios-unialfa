package br.com.unialfa.model;

public class Empresa {

    // Atributos privados (Encapsulamento rigoroso)
    private Long id;
    private String razaoSocial;
    private String cnpj;
    private String email;
    private String telefone;
    private String senha;
    private boolean aprovada; // O Backoffice define se a empresa está apta a publicar vagas

    // Construtor vazio (Necessário para a manipulação dos dados depois)
    public Empresa() {
    }

    // Construtor completo
    public Empresa(Long id, String razaoSocial, String cnpj, String email, String telefone, boolean aprovada) {
        this.id = id;
        this.razaoSocial = razaoSocial;
        this.cnpj = cnpj;
        this.email = email;
        this.telefone = telefone;
        this.aprovada = aprovada;
    }

    // Getters e Setters (Métodos públicos de acesso)
    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public String getRazaoSocial() {
        return razaoSocial;
    }

    public void setRazaoSocial(String razaoSocial) {
        this.razaoSocial = razaoSocial;
    }

    public String getCnpj() {
        return cnpj;
    }

    public void setCnpj(String cnpj) {
        this.cnpj = cnpj;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getTelefone() {
        return telefone;
    }

    public void setTelefone(String telefone) {
        this.telefone = telefone;
    }

    public String getSenha() {
        return senha;
    }

    public void setSenha(String senha) {
        this.senha = senha;
    }

    public boolean isAprovada() {
        return aprovada;
    }

    public void setAprovada(boolean aprovada) {
        this.aprovada = aprovada;
    }
    @Override
    public String toString() {
        return this.razaoSocial; // Isso faz o nome da empresa aparecer na listagem
    }
}