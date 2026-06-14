package br.com.unialfa.view;

import br.com.unialfa.util.GeradorRelatorios;
import javax.swing.*;
import java.awt.*;
import java.io.File;

public class RelatoriosView extends JFrame {

    private GeradorRelatorios gerador = new GeradorRelatorios();

    public RelatoriosView() {
        setTitle("Exportação de Relatórios Gerenciais - UniALFA");
        setSize(450, 300);
        setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
        setLocationRelativeTo(null);
        setLayout(new BorderLayout(10, 10));

        JLabel lblInfo = new JLabel("Selecione o relatório que deseja exportar em formato .txt", SwingConstants.CENTER);
        lblInfo.setBorder(BorderFactory.createEmptyBorder(15, 10, 10, 10));
        add(lblInfo, BorderLayout.NORTH);

        JPanel painelBotoes = new JPanel(new GridLayout(4, 1, 10, 10));
        painelBotoes.setBorder(BorderFactory.createEmptyBorder(10, 40, 20, 40));

        JButton btnEmpresas = new JButton("Relatório de Empresas Cadastradas");
        JButton btnAlunos = new JButton("Relatório de Alunos Cadastrados");
        JButton btnVagas = new JButton("Relatório de Vagas Disponíveis");
        JButton btnCandidaturas = new JButton("Relatório de Candidaturas e Status");

        painelBotoes.add(btnEmpresas);
        painelBotoes.add(btnAlunos);
        painelBotoes.add(btnVagas);
        painelBotoes.add(btnCandidaturas);

        add(painelBotoes, BorderLayout.CENTER);

        // Configuração dos eventos de clique
        btnEmpresas.addActionListener(e -> processarExportacao("relatorio_empresas.txt", 1));
        btnAlunos.addActionListener(e -> processarExportacao("relatorio_alunos.txt", 2));
        btnVagas.addActionListener(e -> processarExportacao("relatorio_vagas.txt", 3));
        btnCandidaturas.addActionListener(e -> processarExportacao("relatorio_candidaturas.txt", 4));
    }

    private void processarExportacao(String nomePadrao, int tipoRelatorio) {
        JFileChooser fileChooser = new JFileChooser();
        fileChooser.setDialogTitle("Salvar Relatório");
        fileChooser.setSelectedFile(new File(nomePadrao));

        int resultado = fileChooser.showSaveDialog(this);

        if (resultado == JFileChooser.APPROVE_OPTION) {
            File arquivoParaSalvar = fileChooser.getSelectedFile();
            try {
                switch (tipoRelatorio) {
                    case 1 -> gerador.exportarEmpresas(arquivoParaSalvar);
                    case 2 -> gerador.exportarAlunos(arquivoParaSalvar);
                    case 3 -> gerador.exportarVagas(arquivoParaSalvar);
                    case 4 -> gerador.exportarCandidaturas(arquivoParaSalvar);
                }
                JOptionPane.showMessageDialog(this, "Relatório exportado com sucesso!", "Sucesso", JOptionPane.INFORMATION_MESSAGE);
            } catch (Exception ex) {
                JOptionPane.showMessageDialog(this, "Erro ao gerar relatório: " + ex.getMessage(), "Erro", JOptionPane.ERROR_MESSAGE);
            }
        }
    }
}