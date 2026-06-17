package br.com.unialfa.view;

import br.com.unialfa.model.Aluno;
import br.com.unialfa.service.AlunoService;

import javax.swing.*;
import javax.swing.table.DefaultTableModel;
import java.awt.*;
import java.util.List;

public class GestaoAlunosView extends JFrame {

    private JTable tabelaAlunos;
    private DefaultTableModel modeloTabela;
    private AlunoService service = new AlunoService(); // Agora usa o Service!

    public GestaoAlunosView() {
        setTitle("Gestão Geral de Alunos - Backoffice UniALFA");
        setSize(750, 450);
        setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
        setLocationRelativeTo(null);
        setLayout(new BorderLayout());

        String[] colunas = {"RA", "Nome do Aluno", "Curso", "Status"};
        modeloTabela = new DefaultTableModel(colunas, 0);
        tabelaAlunos = new JTable(modeloTabela);
        add(new JScrollPane(tabelaAlunos), BorderLayout.CENTER);

        JPanel painelBotoes = new JPanel(new FlowLayout(FlowLayout.CENTER, 15, 10));
        JButton btnNovo = new JButton("Cadastrar Aluno");
        JButton btnEditar = new JButton("Editar Aluno");
        JButton btnApto = new JButton("Marcar como Apto");
        JButton btnInativo = new JButton("Marcar como Inativo");

        btnApto.setBackground(new Color(40, 167, 69));
        btnApto.setForeground(Color.BLACK);
        btnInativo.setBackground(new Color(220, 53, 69));
        btnInativo.setForeground(Color.BLACK);

        painelBotoes.add(btnNovo); painelBotoes.add(btnEditar);
        painelBotoes.add(btnApto); painelBotoes.add(btnInativo);
        add(painelBotoes, BorderLayout.SOUTH);

        btnNovo.addActionListener(e -> abrirFormulario(null));
        btnEditar.addActionListener(e -> {
            int linha = tabelaAlunos.getSelectedRow();
            if (linha == -1) {
                JOptionPane.showMessageDialog(this, "Selecione um aluno na tabela para editar.");
                return;
            }
            String ra = (String) modeloTabela.getValueAt(linha, 0);
            String nome = (String) modeloTabela.getValueAt(linha, 1);
            String curso = (String) modeloTabela.getValueAt(linha, 2);
            abrirFormulario(new Aluno(ra, nome, curso));
        });

        btnApto.addActionListener(e -> alterarAptidao(true));
        btnInativo.addActionListener(e -> alterarAptidao(false));

        carregarDados();
    }

    private void carregarDados() {
        modeloTabela.setRowCount(0);
        List<Aluno> lista = service.listarTodos();
        for (Aluno aluno : lista) {
            Object[] linha = {aluno.getRa(), aluno.getNome(), aluno.getCurso(), aluno.isApto() ? "Apto" : "Inativo"};
            modeloTabela.addRow(linha);
        }
    }

    private void abrirFormulario(Aluno aluno) {
        new AlunoFormView(this, aluno, this::carregarDados).setVisible(true);
    }

    private void alterarAptidao(boolean apto) {
        int linha = tabelaAlunos.getSelectedRow();
        if (linha == -1) {
            JOptionPane.showMessageDialog(this, "Selecione um aluno na tabela.");
            return;
        }
        String ra = (String) modeloTabela.getValueAt(linha, 0);
        new Thread(() -> {
            try {
                service.atualizarAptidao(ra, apto);
                SwingUtilities.invokeLater(() -> {
                    carregarDados();
                    JOptionPane.showMessageDialog(this, "Status modificado!");
                });
            } catch (Exception ex) {
                SwingUtilities.invokeLater(() -> JOptionPane.showMessageDialog(this, "Erro: " + ex.getMessage()));
            }
        }).start();
    }
}