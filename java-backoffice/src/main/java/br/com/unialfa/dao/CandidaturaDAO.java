package br.com.unialfa.dao;

import br.com.unialfa.util.ConnectionFactory;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.util.ArrayList;
import java.util.List;

public class CandidaturaDAO {

    // Método para listar todas as candidaturas cruzando dados de 4 tabelas diferentes!
    public List<Object[]> listarTodas() {
        List<Object[]> lista = new ArrayList<>();
        String sql = "SELECT c.id, a.nome AS aluno, v.titulo AS vaga, e.razao_social AS empresa, c.status " +
                "FROM candidaturas c " +
                "INNER JOIN alunos a ON c.aluno_ra = a.ra " +
                "INNER JOIN vagas v ON c.vaga_id = v.id " +
                "INNER JOIN empresas e ON v.empresa_id = e.id";

        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql);
             ResultSet rs = stmt.executeQuery()) {

            while (rs.next()) {
                Object[] linha = {
                        rs.getLong("id"),
                        rs.getString("aluno"),
                        rs.getString("vaga"),
                        rs.getString("empresa"),
                        rs.getString("status")
                };
                lista.add(linha);
            }
        } catch (Exception e) {
            throw new RuntimeException("❌ Erro ao listar candidaturas: " + e.getMessage(), e);
        }
        return lista;
    }

    // Método para mudar o status da candidatura
    public void atualizarStatus(Long id, String novoStatus) {
        String sql = "UPDATE candidaturas SET status = ? WHERE id = ?";
        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, novoStatus);
            stmt.setLong(2, id);

            // O executeUpdate envia a ordem e devolve a confirmação
            int linhasAfetadas = stmt.executeUpdate();
            System.out.println("✅ MySQL confirmou: " + linhasAfetadas + " linha(s) atualizada(s).");

        } catch (Exception e) {
            throw new RuntimeException("❌ Erro ao atualizar status: " + e.getMessage(), e);
        }
    }
    // Método para exclusão em cascata (Apaga as candidaturas de um aluno específico)
    public void excluirPorAluno(String raAluno) {
        String sql = "DELETE FROM candidaturas WHERE aluno_ra = ?";
        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, raAluno);
            int apagadas = stmt.executeUpdate();
            System.out.println("✅ LGPD Cascata: " + apagadas + " candidaturas do aluno " + raAluno + " foram excluídas.");

        } catch (Exception e) {
            throw new RuntimeException("❌ Erro ao excluir candidaturas do aluno: " + e.getMessage(), e);
        }
    }
    // Método para exclusão em cascata (Apaga candidaturas quando uma Vaga é excluída)
    public void excluirPorVaga(Long vagaId) {
        String sql = "DELETE FROM candidaturas WHERE vaga_id = ?";
        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setLong(1, vagaId);
            stmt.executeUpdate();

        } catch (Exception e) {
            throw new RuntimeException("❌ Erro ao excluir candidaturas da vaga: " + e.getMessage(), e);
        }
    }
}