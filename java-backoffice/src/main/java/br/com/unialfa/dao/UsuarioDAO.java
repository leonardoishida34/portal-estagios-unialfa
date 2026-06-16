package br.com.unialfa.dao;

import br.com.unialfa.model.Usuario;
import br.com.unialfa.util.ConnectionFactory;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class UsuarioDAO {

    // 1. Autenticação (Agora devolve o objeto Usuario para sabermos o Perfil)
    public Usuario autenticar(String login, String senha) {
        String sql = "SELECT * FROM usuarios WHERE login = ? AND senha = ?";
        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, login);
            stmt.setString(2, senha);

            try (ResultSet rs = stmt.executeQuery()) {
                if (rs.next()) {
                    return new Usuario(
                            rs.getLong("id"),
                            rs.getString("nome"),
                            rs.getString("login"),
                            rs.getString("senha"),
                            rs.getString("perfil")
                    );
                }
            }
        } catch (Exception e) {
            System.err.println("❌ Erro na autenticação: " + e.getMessage());
        }
        return null; // Retorna nulo se errar as credenciais
    }

    // 2. Salvar novo usuário
    public void salvar(Usuario usuario) {
        String sql = "INSERT INTO usuarios (nome, login, senha, perfil) VALUES (?, ?, ?, ?)";
        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, usuario.getNome());
            stmt.setString(2, usuario.getLogin());
            stmt.setString(3, usuario.getSenha());
            stmt.setString(4, usuario.getPerfil());
            stmt.execute();

        } catch (SQLException e) {
            throw new RuntimeException("❌ ERRO ao salvar usuário: " + e.getMessage(), e);
        }
    }

    // 3. Listar todos os usuários
    public List<Usuario> listarTodos() {
        List<Usuario> lista = new ArrayList<>();
        String sql = "SELECT * FROM usuarios";
        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql);
             ResultSet rs = stmt.executeQuery()) {

            while (rs.next()) {
                lista.add(new Usuario(
                        rs.getLong("id"),
                        rs.getString("nome"),
                        rs.getString("login"),
                        rs.getString("senha"),
                        rs.getString("perfil")
                ));
            }
        } catch (SQLException e) {
            throw new RuntimeException("❌ ERRO ao listar usuários: " + e.getMessage(), e);
        }
        return lista;
    }

    // 4. Atualizar dados do usuário
    public void atualizar(Usuario usuario) {
        String sql = "UPDATE usuarios SET nome = ?, login = ?, senha = ?, perfil = ? WHERE id = ?";
        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, usuario.getNome());
            stmt.setString(2, usuario.getLogin());
            stmt.setString(3, usuario.getSenha());
            stmt.setString(4, usuario.getPerfil());
            stmt.setLong(5, usuario.getId());
            stmt.executeUpdate();

        } catch (Exception e) {
            throw new RuntimeException("❌ Erro ao atualizar usuário: " + e.getMessage(), e);
        }
    }

    // 5. Excluir (Hard Delete exigido pela LGPD)
    public void excluir(Long id) {
        String sql = "DELETE FROM usuarios WHERE id = ?";
        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setLong(1, id);
            stmt.execute();
            System.out.println("✅ SUCESSO: Usuário ID " + id + " excluído fisicamente do sistema.");

        } catch (Exception e) {
            throw new RuntimeException("❌ Erro ao excluir usuário: " + e.getMessage(), e);
        }
    }
}