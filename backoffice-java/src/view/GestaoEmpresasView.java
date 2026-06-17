package br.com.unialfa.view;

import br.com.unialfa.model.Empresa;
import br.com.unialfa.service.EmpresaService;

import javax.swing.*;
import javax.swing.table.DefaultTableModel;
import java.awt.*;
import java.util.List;

public class GestaoEmpresasView extends JFrame {

    private JTable tabelaEmpresas;
    private DefaultTableModel modeloTabela;
    private EmpresaService service = new EmpresaService();

    public GestaoEmpresasView() {
        setTitle("Gestão de Empresas - Backoffice");
        setSize(750, 400);
        setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
        setLocationRelativeTo(null);
        setLayout(new BorderLayout());

        String[] colunas = {"ID", "Razão Social", "CNPJ", "Status"};
        modeloTabela = new DefaultTableModel(colunas, 0);
        tabelaEmpresas = new JTable(modeloTabela);
        add(new JScrollPane(tabelaEmpresas), BorderLayout.CENTER);

        JPanel painelBotoes = new JPanel(new FlowLayout());
        JButton btnAprovar = new JButton("Aprovar Empresa");
        JButton btnBloquear = new JButton("Bloquear Empresa");
        JButton btnExcluir = new JButton("Excluir Empresa"); // Novo botão

        btnAprovar.setBackground(new Color(40, 167, 69));
        btnAprovar.setForeground(Color.BLACK);
        btnAprovar.setOpaque(true); btnAprovar.setContentAreaFilled(true);

        btnBloquear.setBackground(new Color(255, 193, 7)); // Amarelo para bloqueio
        btnBloquear.setForeground(Color.BLACK);
        btnBloquear.setOpaque(true); btnBloquear.setContentAreaFilled(true);

        btnExcluir.setBackground(new Color(220, 53, 69)); // Vermelho LGPD
        btnExcluir.setForeground(Color.BLACK);
        btnExcluir.setOpaque(true); btnExcluir.setContentAreaFilled(true);

        painelBotoes.add(btnAprovar);
        painelBotoes.add(btnBloquear);
        painelBotoes.add(btnExcluir);
        add(painelBotoes, BorderLayout.SOUTH);

        btnAprovar.addActionListener(e -> alterarStatus(true));
        btnBloquear.addActionListener(e -> alterarStatus(false));
        btnExcluir.addActionListener(e -> excluirEmpresa());

        carregarDados();
    }

    private void carregarDados() {
        modeloTabela.setRowCount(0);
        List<Empresa> lista = service.listarTodas();
        for (Empresa emp : lista) {
            modeloTabela.addRow(new Object[]{emp.getId(), emp.getRazaoSocial(), emp.getCnpj(), emp.isAprovada() ? "Aprovada" : "Pendente/Bloqueada"});
        }
    }

    private void alterarStatus(boolean aprovar) {
        int linhaSelecionada = tabelaEmpresas.getSelectedRow();
        if (linhaSelecionada == -1) {
            JOptionPane.showMessageDialog(this, "Selecione uma empresa na tabela primeiro.");
            return;
        }
        Long idEmpresa = (Long) modeloTabela.getValueAt(linhaSelecionada, 0);
        new Thread(() -> {
            try {
                service.atualizarStatus(idEmpresa, aprovar);
                SwingUtilities.invokeLater(() -> {
                    carregarDados();
                    JOptionPane.showMessageDialog(this, "Status atualizado com sucesso!");
                });
            } catch (Exception ex) {
                SwingUtilities.invokeLater(() -> JOptionPane.showMessageDialog(this, "Erro: " + ex.getMessage(), "Erro", JOptionPane.ERROR_MESSAGE));
            }
        }).start();
    }

    private void excluirEmpresa() {
        int linhaSelecionada = tabelaEmpresas.getSelectedRow();
        if (linhaSelecionada == -1) {
            JOptionPane.showMessageDialog(this, "Selecione uma empresa para excluir.");
            return;
        }

        Long idEmpresa = (Long) modeloTabela.getValueAt(linhaSelecionada, 0);
        String nomeEmpresa = (String) modeloTabela.getValueAt(linhaSelecionada, 1);

        int confirmacao = JOptionPane.showConfirmDialog(this,
                "Aviso LGPD: Deseja excluir fisicamente a empresa '" + nomeEmpresa + "'?",
                "Confirmação de Exclusão",
                JOptionPane.YES_NO_OPTION,
                JOptionPane.WARNING_MESSAGE);

        if (confirmacao == JOptionPane.YES_OPTION) {
            new Thread(() -> {
                try {
                    service.excluir(idEmpresa); // Vai acionar a trava do Service!
                    SwingUtilities.invokeLater(() -> {
                        carregarDados();
                        JOptionPane.showMessageDialog(this, "Empresa excluída fisicamente do banco de dados.");
                    });
                } catch (Exception ex) {
                    SwingUtilities.invokeLater(() -> JOptionPane.showMessageDialog(this, ex.getMessage(), "Acesso Negado pelo Sistema", JOptionPane.ERROR_MESSAGE));
                }
            }).start();
        }
    }
}