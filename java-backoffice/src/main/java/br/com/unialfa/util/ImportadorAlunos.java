package br.com.unialfa.util;

import br.com.unialfa.dao.AlunoDAO;
import br.com.unialfa.model.Aluno;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;

public class ImportadorAlunos {
    public void importar(File arquivo) {
        AlunoDAO dao = new AlunoDAO();
        try (BufferedReader br = new BufferedReader(new FileReader(arquivo))) {
            String linha;
            while ((linha = br.readLine()) != null) {
                // Supomos o formato: RA;Nome;Curso
                String[] dados = linha.split(";");
                if (dados.length == 3) {
                    Aluno aluno = new Aluno(dados[0], dados[1], dados[2]);
                    dao.salvar(aluno);
                }
            }
        } catch (Exception e) {
            throw new RuntimeException("Erro ao ler arquivo: " + e.getMessage());
        }
    }
}