package br.com.unialfa.dao;

import br.com.unialfa.model.Aluno;
import br.com.unialfa.util.ConnectionFactory;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;

import java.sql.ResultSet;
import java.util.ArrayList;
import java.util.List;

public class AlunoDAO {

    // O nosso "Entregador" que leva o Aluno para a base de dados
    public void salvar(Aluno aluno) {

        // 1. A Ordem de Serviço (A query SQL com as caixas vazias)
        String sql = "INSERT INTO alunos (ra, nome, curso) VALUES (?, ?, ?)";

        // 2. Abrindo a porta do estoque com a chave (Connection)
        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            // 3. Preenchendo as caixas vazias com os dados do Aluno
            stmt.setString(1, aluno.getRa());
            stmt.setString(2, aluno.getNome());
            stmt.setString(3, aluno.getCurso());

            // Empurra a informação para dentro da tabela do MySQL
            stmt.execute();
            System.out.println("✅ SUCESSO: Aluno '" + aluno.getNome() + "' guardado na base de dados!");

        } catch (SQLException e) {
            // Se algo correr mal (ex: base de dados desligada), avisa aqui
            throw new RuntimeException("❌ ERRO ao guardar o aluno: " + e.getMessage(), e);
        }
    }
    // Método para procurar todos os alunos na base de dados
    public List<Aluno> listarTodos() {
        List<Aluno> lista = new ArrayList<>();
        String sql = "SELECT * FROM alunos";

        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql);
             ResultSet rs = stmt.executeQuery()) {

            while (rs.next()) {
                // Cria o objeto Aluno e adiciona à lista
                Aluno aluno = new Aluno(
                        rs.getString("ra"),
                        rs.getString("nome"),
                        rs.getString("curso")
                );
                aluno.setApto(rs.getBoolean("apto")); // puxa o status do banco
                lista.add(aluno);
            }
        } catch (SQLException e) {
            throw new RuntimeException("❌ ERRO ao listar alunos: " + e.getMessage(), e);
        }
        return lista;
    }

    // Método para mudar o status do aluno (Apto / Inativo)
    public void atualizarAptidao(String ra, boolean apto) {
        String sql = "UPDATE alunos SET apto = ? WHERE ra = ?";
        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setBoolean(1, apto);
            stmt.setString(2, ra);
            stmt.executeUpdate();

        } catch (Exception e) {
            throw new RuntimeException("❌ Erro ao atualizar aptidão: " + e.getMessage(), e);
        }
    }
    // Método para atualizar os dados cadastrais (Nome e Curso) do aluno
    public void atualizarDados(Aluno aluno) {
        String sql = "UPDATE alunos SET nome = ?, curso = ? WHERE ra = ?";
        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, aluno.getNome());
            stmt.setString(2, aluno.getCurso());
            stmt.setString(3, aluno.getRa());
            stmt.executeUpdate();

        } catch (Exception e) {
            throw new RuntimeException("❌ Erro ao atualizar dados do aluno: " + e.getMessage(), e);
        }
    }
    // Método para exclusão física (Hard Delete - LGPD)
    public void excluir(String ra) {
        String sql = "DELETE FROM alunos WHERE ra = ?";
        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, ra);
            stmt.execute();
            System.out.println("✅ SUCESSO: Aluno RA " + ra + " excluído fisicamente do sistema.");

        } catch (SQLException e) {
            throw new RuntimeException("❌ ERRO ao excluir aluno: " + e.getMessage(), e);
        }
    }
}