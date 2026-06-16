package br.com.unialfa.util;

import java.io.BufferedWriter;
import java.io.File;
import java.io.FileWriter;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;

public class GeradorRelatorios {

    public void exportarEmpresas(File arquivo) throws Exception {
        String sql = "SELECT id, razao_social, cnpj, email, aprovada FROM empresas";
        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql);
             ResultSet rs = stmt.executeQuery();
             BufferedWriter writer = new BufferedWriter(new FileWriter(arquivo))) {

            writer.write("=====================================================================\n");
            writer.write("                 RELATÓRIO DE EMPRESAS CADASTRADAS                   \n");
            writer.write("=====================================================================\n\n");
            writer.write(String.format("%-5s | %-30s | %-18s | %-15s\n", "ID", "Razão Social", "CNPJ", "Status"));
            writer.write("---------------------------------------------------------------------\n");

            while (rs.next()) {
                String status = rs.getBoolean("aprovada") ? "Aprovada" : "Pendente/Bloqueada";
                writer.write(String.format("%-5d | %-30s | %-18s | %-15s\n",
                        rs.getLong("id"),
                        rs.getString("razao_social"),
                        rs.getString("cnpj"),
                        status));
            }
        }
    }

    public void exportarAlunos(File arquivo) throws Exception {
        String sql = "SELECT ra, nome, curso FROM alunos";
        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql);
             ResultSet rs = stmt.executeQuery();
             BufferedWriter writer = new BufferedWriter(new FileWriter(arquivo))) {

            writer.write("=====================================================================\n");
            writer.write("                  RELATÓRIO DE ALUNOS IMPORTADOS                     \n");
            writer.write("=====================================================================\n\n");
            writer.write(String.format("%-10s | %-30s | %-25s\n", "RA", "Nome do Aluno", "Curso"));
            writer.write("---------------------------------------------------------------------\n");

            while (rs.next()) {
                writer.write(String.format("%-10s | %-30s | %-25s\n",
                        rs.getString("ra"),
                        rs.getString("nome"),
                        rs.getString("curso")));
            }
        }
    }

    public void exportarVagas(File arquivo) throws Exception {
        String sql = "SELECT v.id, v.titulo, e.razao_social, v.bolsa FROM vagas v " +
                "INNER JOIN empresas e ON v.empresa_id = e.id WHERE v.ativa = true";
        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql);
             ResultSet rs = stmt.executeQuery();
             BufferedWriter writer = new BufferedWriter(new FileWriter(arquivo))) {

            writer.write("=====================================================================\n");
            writer.write("                    RELATÓRIO DE VAGAS DISPONÍVEIS                   \n");
            writer.write("=====================================================================\n\n");
            writer.write(String.format("%-5s | %-25s | %-25s | %-12s\n", "ID", "Título da Vaga", "Empresa", "Bolsa"));
            writer.write("---------------------------------------------------------------------\n");

            while (rs.next()) {
                writer.write(String.format("%-5d | %-25s | %-25s | R$ %-10.2f\n",
                        rs.getLong("id"),
                        rs.getString("titulo"),
                        rs.getString("razao_social"),
                        rs.getDouble("bolsa")));
            }
        }
    }

    public void exportarCandidaturas(File arquivo) throws Exception {
        String sql = "SELECT c.id, a.nome AS aluno, v.titulo AS vaga, e.razao_social AS empresa, c.status " +
                "FROM candidaturas c " +
                "INNER JOIN alunos a ON c.aluno_ra = a.ra " +
                "INNER JOIN vagas v ON c.vaga_id = v.id " +
                "INNER JOIN empresas e ON v.empresa_id = e.id";
        try (Connection conn = ConnectionFactory.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql);
             ResultSet rs = stmt.executeQuery();
             BufferedWriter writer = new BufferedWriter(new FileWriter(arquivo))) {

            writer.write("====================================================================================\n");
            writer.write("                       RELATÓRIO DE CANDIDATURAS REALIZADAS                         \n");
            writer.write("====================================================================================\n\n");
            writer.write(String.format("%-5s | %-25s | %-25s | %-20s | %-12s\n", "ID", "Aluno", "Vaga", "Empresa", "Status"));
            writer.write("------------------------------------------------------------------------------------\n");

            while (rs.next()) {
                writer.write(String.format("%-5d | %-25s | %-25s | %-20s | %-12s\n",
                        rs.getLong("id"),
                        rs.getString("aluno"),
                        rs.getString("vaga"),
                        rs.getString("empresa"),
                        rs.getString("status")));
            }
        }
    }
}