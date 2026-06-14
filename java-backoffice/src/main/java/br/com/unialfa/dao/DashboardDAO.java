package br.com.unialfa.dao;

import br.com.unialfa.util.ConnectionFactory;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;

public class DashboardDAO {

    // Método privado genérico para não repetirmos código
    private int buscarTotal(String sql) {
        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql);
             ResultSet rs = stmt.executeQuery()) {

            if (rs.next()) {
                return rs.getInt(1); // Devolve o resultado da contagem
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        return 0;
    }

    // Métodos públicos que o Menu vai chamar
    public int getTotalAlunos() {
        return buscarTotal("SELECT COUNT(*) FROM alunos");
    }

    public int getTotalEmpresas() {
        return buscarTotal("SELECT COUNT(*) FROM empresas");
    }

    public int getTotalVagas() {
        return buscarTotal("SELECT COUNT(*) FROM vagas");
    }
}