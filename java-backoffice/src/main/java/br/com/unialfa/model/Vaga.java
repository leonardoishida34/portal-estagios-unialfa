package br.com.unialfa.model;

public class Vaga {

    // Atributos privados
    private Long id;
    private String titulo;
    private String descricao;
    private Double bolsa; // Valor da remuneração / bolsa auxílio
    private Long empresaId; // Guarda qual empresa é a dona da vaga
    private boolean ativa;

    // Construtor vazio
    public Vaga() {
    }

    // Construtor completo
    public Vaga(Long id, String titulo, String descricao, Double bolsa, Long empresaId, boolean ativa) {
        this.id = id;
        this.titulo = titulo;
        this.descricao = descricao;
        this.bolsa = bolsa;
        this.empresaId = empresaId;
        this.ativa = ativa;
    }

    // --- Getters e Setters (Encapsulamento) ---

    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }

    public String getTitulo() { return titulo; }
    public void setTitulo(String titulo) { this.titulo = titulo; }

    public String getDescricao() { return descricao; }
    public void setDescricao(String descricao) { this.descricao = descricao; }

    public Double getBolsa() { return bolsa; }
    public void setBolsa(Double bolsa) { this.bolsa = bolsa; }

    public Long getEmpresaId() { return empresaId; }
    public void setEmpresaId(Long empresaId) { this.empresaId = empresaId; }

    public boolean isAtiva() { return ativa; }
    public void setAtiva(boolean ativa) { this.ativa = ativa; }
}