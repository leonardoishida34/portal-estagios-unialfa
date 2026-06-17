package br.com.unialfa.dao;

import br.com.unialfa.model.Empresa;
import br.com.unialfa.util.ConnectionFactory;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class EmpresaDAO {

    // Método para inserir uma nova empresa na base de dados
    public void salvar(Empresa empresa) {
        String sql = "INSERT INTO empresas (razao_social, cnpj, email, telefone, senha, aprovada) VALUES (?, ?, ?, ?, ?, ?)";

        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, empresa.getRazaoSocial());
            stmt.setString(2, empresa.getCnpj());
            stmt.setString(3, empresa.getEmail());
            stmt.setString(4, empresa.getTelefone());
            stmt.setString(5, empresa.getSenha());
            stmt.setBoolean(6, empresa.isAprovada());

            stmt.execute();
            System.out.println("✅ SUCESSO: Empresa '" + empresa.getRazaoSocial() + "' guardada na base de dados!");

        } catch (SQLException e) {
            throw new RuntimeException("❌ ERRO ao guardar a empresa: " + e.getMessage(), e);
        }
    }

    // Método para procurar todas as empresas na base de dados
    public List<Empresa> listarTodas() {
        List<Empresa> empresas = new ArrayList<>();
        String sql = "SELECT * FROM empresas";

        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql);
             ResultSet rs = stmt.executeQuery()) {

            while (rs.next()) {
                Empresa emp = new Empresa();
                emp.setId(rs.getLong("id"));
                emp.setRazaoSocial(rs.getString("razao_social"));
                emp.setCnpj(rs.getString("cnpj"));
                emp.setEmail(rs.getString("email"));
                emp.setTelefone(rs.getString("telefone"));
                emp.setAprovada(rs.getBoolean("aprovada"));

                empresas.add(emp);
            }
        } catch (SQLException e) {
            throw new RuntimeException("❌ ERRO ao listar empresas: " + e.getMessage(), e);
        }
        return empresas;
    }
    // Método para atualizar o status de aprovação de uma empresa
    public void atualizarStatus(Long id, boolean aprovada) {
        // Query SQL para atualizar apenas a coluna 'aprovada' da empresa com o ID correspondente
        String sql = "UPDATE empresas SET aprovada = ? WHERE id = ?";

        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setBoolean(1, aprovada);
            stmt.setLong(2, id);

            stmt.execute();
            System.out.println("✅ SUCESSO: Status da empresa ID " + id + " atualizado para " + aprovada);

        } catch (SQLException e) {
            throw new RuntimeException("❌ ERRO ao atualizar status da empresa: " + e.getMessage(), e);
        }

    }
    public List<Empresa> listarAprovadas() {
        List<Empresa> lista = new ArrayList<>();
        String sql = "SELECT * FROM empresas WHERE aprovada = true";
        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql);
             ResultSet rs = stmt.executeQuery()) {
            while (rs.next()) {
                Empresa e = new Empresa();
                e.setId(rs.getLong("id"));
                e.setRazaoSocial(rs.getString("razao_social"));
                lista.add(e);
            }
        } catch (Exception e) { e.printStackTrace(); }
        return lista;
    }
    // Método para exclusão física (Hard Delete - LGPD)
    public void excluir(Long id) {
        String sql = "DELETE FROM empresas WHERE id = ?";
        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setLong(1, id);
            stmt.execute();
            System.out.println("✅ SUCESSO: Empresa ID " + id + " excluída fisicamente.");

        } catch (SQLException e) {
            throw new RuntimeException("❌ ERRO ao excluir empresa: " + e.getMessage(), e);
        }
    }
}