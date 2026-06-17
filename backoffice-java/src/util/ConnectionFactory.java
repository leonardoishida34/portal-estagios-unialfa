package br.com.unialfa.util;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class ConnectionFactory {

    // Caminho de conexão JDBC com o banco que você acabou de criar
    private static final String URL = "jdbc:mysql://localhost:3306/portal_estagios";
    private static final String USER = "root";
    private static final String PASSWORD = ""; // Coloque sua senha do MySQL aqui, se houver

    public static Connection getConnection() {
        try {
            return DriverManager.getConnection(URL, USER, PASSWORD);
        } catch (SQLException e) {
            throw new RuntimeException("Erro ao conectar com o banco de dados: " + e.getMessage(), e);
        }
    }
}