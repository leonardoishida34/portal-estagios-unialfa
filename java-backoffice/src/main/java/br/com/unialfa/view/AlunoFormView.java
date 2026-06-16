package br.com.unialfa.view;

import br.com.unialfa.model.Aluno;
import br.com.unialfa.service.AlunoService;

import javax.swing.*;
import java.awt.*;

public class AlunoFormView extends JDialog {

    private JTextField txtRa = new JTextField();
    private JTextField txtNome = new JTextField();
    private JTextField txtCurso = new JTextField();
    private JButton btnSalvar = new JButton("Salvar Dados");

    private Aluno alunoExistente = null;
    private AlunoService service = new AlunoService(); // Agora usa o Service!
    private Runnable aoSalvarCallback;

    public AlunoFormView(JFrame pai, Aluno aluno, Runnable aoSalvarCallback) {
        super(pai, true);
        this.alunoExistente = aluno;
        this.aoSalvarCallback = aoSalvarCallback;

        setTitle(aluno == null ? "Cadastrar Novo Aluno" : "Editar Dados do Aluno");
        setSize(400, 250);
        setLocationRelativeTo(pai);
        setLayout(new GridLayout(4, 2, 10, 10));

        add(new JLabel("  RA (Mín. 6 caracteres):")); add(txtRa);
        add(new JLabel("  Nome Completo:")); add(txtNome);
        add(new JLabel("  Curso:")); add(txtCurso);
        add(new JLabel("")); add(btnSalvar);

        if (aluno != null) {
            txtRa.setText(aluno.getRa());
            txtRa.setEditable(false);
            txtNome.setText(aluno.getNome());
            txtCurso.setText(aluno.getCurso());
        }

        btnSalvar.addActionListener(e -> salvar());
    }

    private void salvar() {
        String ra = txtRa.getText();
        String nome = txtNome.getText();
        String curso = txtCurso.getText();
        boolean isEdicao = (alunoExistente != null);

        new Thread(() -> {
            try {
                service.salvarOuEditar(ra, nome, curso, isEdicao);

                SwingUtilities.invokeLater(() -> {
                    JOptionPane.showMessageDialog(this, "Dados gravados com sucesso!");
                    if (aoSalvarCallback != null) aoSalvarCallback.run();
                    dispose();
                });
            } catch (Exception ex) {
                SwingUtilities.invokeLater(() ->
                        JOptionPane.showMessageDialog(this, "Erro: " + ex.getMessage(), "Erro", JOptionPane.ERROR_MESSAGE)
                );
            }
        }).start();
    }
}