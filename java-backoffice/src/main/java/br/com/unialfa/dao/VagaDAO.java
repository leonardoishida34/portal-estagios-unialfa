package br.com.unialfa.dao;

import br.com.unialfa.model.Vaga;
import br.com.unialfa.util.ConnectionFactory;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class VagaDAO {

    // Método responsável por inserir a nova vaga no banco de dados
    public void salvar(Vaga vaga) {

        // A nossa instrução SQL. Repare no campo empresa_id!
        String sql = "INSERT INTO vagas (titulo, descricao, bolsa, empresa_id, ativa) VALUES (?, ?, ?, ?, ?)";

        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            // Preenchendo as "caixas de segurança" com os dados do objeto
            stmt.setString(1, vaga.getTitulo());
            stmt.setString(2, vaga.getDescricao());
            stmt.setDouble(3, vaga.getBolsa());
            stmt.setLong(4, vaga.getEmpresaId()); // Aqui vai o ID da empresa vinculada
            stmt.setBoolean(5, vaga.isAtiva());

            // Executa a gravação no MySQL
            stmt.execute();
            System.out.println("✅ SUCESSO: Vaga '" + vaga.getTitulo() + "' salva no banco de dados!");

        } catch (SQLException e) {
            throw new RuntimeException("❌ ERRO ao salvar a vaga: " + e.getMessage(), e);
        }
    }
    // Método para listar todas as vagas, trazendo junto o nome da empresa
    public List<Object[]> listarTodasComEmpresa() {
        List<Object[]> lista = new ArrayList<>();

        // O INNER JOIN une a tabela de vagas com a de empresas através do ID
        String sql = "SELECT v.id, v.titulo, v.bolsa, e.razao_social, v.ativa " +
                "FROM vagas v INNER JOIN empresas e ON v.empresa_id = e.id";

        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql);
             ResultSet rs = stmt.executeQuery()) {

            while (rs.next()) {
                // Montamos uma "linha" de dados pronta para a tabela da interface visual
                Object[] linha = {
                        rs.getLong("id"),
                        rs.getString("titulo"),
                        rs.getString("razao_social"), // Vem da tabela empresas!
                        String.format("R$ %.2f", rs.getDouble("bolsa")), // Formata como moeda
                        rs.getBoolean("ativa") ? "Ativa" : "Inativa"
                };
                lista.add(linha);
            }
        } catch (Exception e) {
            throw new RuntimeException("❌ ERRO ao listar as vagas: " + e.getMessage(), e);
        }
        return lista;
    }
    // Verifica se existem vagas vinculadas a uma empresa (Trava de Segurança LGPD)
    public boolean temVagasVinculadas(Long idEmpresa) {
        String sql = "SELECT COUNT(*) FROM vagas WHERE empresa_id = ?";
        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setLong(1, idEmpresa);
            try (ResultSet rs = stmt.executeQuery()) {
                if (rs.next()) {
                    return rs.getInt(1) > 0; // Retorna true se encontrar 1 ou mais vagas
                }
            }
        } catch (SQLException e) {
            throw new RuntimeException("❌ Erro ao verificar dependência de vagas: " + e.getMessage(), e);
        }
        return false;
    }
    // Método para exclusão física (Hard Delete - LGPD)
    public void excluir(Long id) {
        String sql = "DELETE FROM vagas WHERE id = ?";
        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setLong(1, id);
            stmt.execute();

        } catch (Exception e) {
            throw new RuntimeException("❌ ERRO ao excluir a vaga: " + e.getMessage(), e);
        }
    }
}