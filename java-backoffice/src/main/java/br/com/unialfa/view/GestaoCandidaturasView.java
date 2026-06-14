package br.com.unialfa.view;

import br.com.unialfa.service.CandidaturaService;

import javax.swing.*;
import javax.swing.table.DefaultTableModel;
import java.awt.*;
import java.util.List;

public class GestaoCandidaturasView extends JFrame {

    private JTable tabelaCandidaturas;
    private DefaultTableModel modeloTabela;

    // Agora usamos o Service e não o DAO!
    private CandidaturaService service = new CandidaturaService();

    public GestaoCandidaturasView() {
        setTitle("Gestão de Candidaturas de Estágio");
        setSize(800, 450);
        setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
        setLocationRelativeTo(null);
        setLayout(new BorderLayout());

        // 1. Configurar a Tabela
        String[] colunas = {"ID", "Nome do Aluno", "Vaga", "Empresa", "Status da Candidatura"};
        modeloTabela = new DefaultTableModel(colunas, 0);
        tabelaCandidaturas = new JTable(modeloTabela);

        tabelaCandidaturas.getColumnModel().getColumn(0).setPreferredWidth(30);
        tabelaCandidaturas.getColumnModel().getColumn(1).setPreferredWidth(150);
        tabelaCandidaturas.getColumnModel().getColumn(2).setPreferredWidth(150);
        tabelaCandidaturas.getColumnModel().getColumn(3).setPreferredWidth(150);

        add(new JScrollPane(tabelaCandidaturas), BorderLayout.CENTER);

        // 2. Painel de Botões
        JPanel painelBotoes = new JPanel(new FlowLayout());
        JButton btnAprovar = new JButton("Aprovar Candidatura");
        JButton btnRejeitar = new JButton("Rejeitar Candidatura");

        // --- CORREÇÃO VISUAL DOS BOTÕES AQUI ---
        btnAprovar.setBackground(new Color(40, 167, 69));
        btnAprovar.setForeground(Color.BLACK);
        btnAprovar.setOpaque(true);
        btnAprovar.setContentAreaFilled(true);

        btnRejeitar.setBackground(new Color(220, 53, 69));
        btnRejeitar.setForeground(Color.BLACK);
        btnRejeitar.setOpaque(true);
        btnRejeitar.setContentAreaFilled(true);
        // ----------------------------------------

        painelBotoes.add(btnAprovar);
        painelBotoes.add(btnRejeitar);
        add(painelBotoes, BorderLayout.SOUTH);

        // 3. Ações dos Botões
        btnAprovar.addActionListener(e -> alterarStatus("Aprovada"));
        btnRejeitar.addActionListener(e -> alterarStatus("Rejeitada"));

        carregarDados();
    }

    private void carregarDados() {
        modeloTabela.setRowCount(0);
        // A tela pede a lista ao Service
        List<Object[]> lista = service.listarTodas();
        for (Object[] linha : lista) {
            modeloTabela.addRow(linha);
        }
    }

    private void alterarStatus(String novoStatus) {
        int linhaSelecionada = tabelaCandidaturas.getSelectedRow();

        if (linhaSelecionada == -1) {
            JOptionPane.showMessageDialog(this, "Por favor, selecione uma linha.");
            return;
        }

        Long idCandidatura = (Long) modeloTabela.getValueAt(linhaSelecionada, 0);

        new Thread(() -> {
            try {
                // Manda o ID e a ordem para o Cérebro resolver
                service.atualizarStatus(idCandidatura, novoStatus);

                SwingUtilities.invokeLater(() -> {
                    carregarDados();
                    JOptionPane.showMessageDialog(this, "Status atualizado!");
                });
            } catch (Exception ex) {
                SwingUtilities.invokeLater(() ->
                        JOptionPane.showMessageDialog(this, "Erro: " + ex.getMessage(), "Erro", JOptionPane.ERROR_MESSAGE)
                );
            }
        }).start();
    }
}