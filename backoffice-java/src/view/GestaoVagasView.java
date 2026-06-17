package br.com.unialfa.view;

import br.com.unialfa.model.Usuario;
import br.com.unialfa.service.VagaService;

import javax.swing.*;
import javax.swing.table.DefaultTableModel;
import java.awt.*;
import java.util.List;

public class GestaoVagasView extends JFrame {

    private JTable tabelaVagas;
    private DefaultTableModel modeloTabela;
    private VagaService service = new VagaService();

    public GestaoVagasView(Usuario usuarioLogado) {
        setTitle("Gestão de Vagas de Estágio - Backoffice");
        setSize(750, 400);
        setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
        setLocationRelativeTo(null);
        setLayout(new BorderLayout());

        // 1. Configurar as Colunas da Tabela
        String[] colunas = {"ID", "Título da Vaga", "Empresa Ofertante", "Bolsa", "Status"};
        modeloTabela = new DefaultTableModel(colunas, 0);
        tabelaVagas = new JTable(modeloTabela);

        tabelaVagas.getColumnModel().getColumn(0).setPreferredWidth(40);
        tabelaVagas.getColumnModel().getColumn(1).setPreferredWidth(200);
        tabelaVagas.getColumnModel().getColumn(2).setPreferredWidth(200);

        add(new JScrollPane(tabelaVagas), BorderLayout.CENTER);

        // 2. Painel de Botões
        JPanel painelBotoes = new JPanel(new FlowLayout());
        JButton btnExcluir = new JButton("Excluir Vaga");

        // estilo botão
        btnExcluir.setBackground(new Color(220, 53, 69));
        btnExcluir.setForeground(Color.BLACK);
        btnExcluir.setOpaque(true);
        btnExcluir.setContentAreaFilled(true);

        // Somente ADMIN pode excluir vagas
        if (usuarioLogado.getPerfil().equals("OPERADOR")) {
            btnExcluir.setEnabled(false);
            btnExcluir.setToolTipText("Apenas administradores podem excluir vagas.");
        }

        painelBotoes.add(btnExcluir);
        add(painelBotoes, BorderLayout.SOUTH);

        // 3. Ação de Excluir
        btnExcluir.addActionListener(e -> excluirVaga());

        carregarDados();
    }

    private void carregarDados() {
        List<Object[]> listaDeVagas = service.listarTodasComEmpresa();
        modeloTabela.setRowCount(0);
        for (Object[] linha : listaDeVagas) {
            modeloTabela.addRow(linha);
        }
    }

    private void excluirVaga() {
        int linhaSelecionada = tabelaVagas.getSelectedRow();
        if (linhaSelecionada == -1) {
            JOptionPane.showMessageDialog(this, "Selecione uma vaga na tabela para excluir.");
            return;
        }

        Long idVaga = (Long) modeloTabela.getValueAt(linhaSelecionada, 0);
        String titulo = (String) modeloTabela.getValueAt(linhaSelecionada, 1);

        int confirmacao = JOptionPane.showConfirmDialog(this,
                "Aviso LGPD: Deseja excluir fisicamente a vaga '" + titulo + "'?\nIsto também apagará todas as candidaturas feitas para ela.",
                "Confirmação de Exclusão (Cascata)",
                JOptionPane.YES_NO_OPTION,
                JOptionPane.WARNING_MESSAGE);

        if (confirmacao == JOptionPane.YES_OPTION) {
            new Thread(() -> {
                try {
                    service.excluir(idVaga);
                    SwingUtilities.invokeLater(() -> {
                        carregarDados();
                        JOptionPane.showMessageDialog(this, "Vaga e candidaturas excluídas com sucesso.");
                    });
                } catch (Exception ex) {
                    SwingUtilities.invokeLater(() -> JOptionPane.showMessageDialog(this, "Erro: " + ex.getMessage(), "Erro", JOptionPane.ERROR_MESSAGE));
                }
            }).start();
        }
    }
}