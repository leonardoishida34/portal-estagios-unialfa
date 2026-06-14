package br.com.unialfa.view;

import br.com.unialfa.model.Usuario;
import br.com.unialfa.service.UsuarioService;

import javax.swing.*;
import javax.swing.table.DefaultTableModel;
import java.awt.*;
import java.util.List;

public class GestaoUsuariosView extends JFrame {

    private JTable tabelaUsuarios;
    private DefaultTableModel modeloTabela;
    private UsuarioService service = new UsuarioService();

    public GestaoUsuariosView() {
        setTitle("Gestão de Utilizadores e Permissões - ADMIN");
        setSize(700, 400);
        setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
        setLocationRelativeTo(null);
        setLayout(new BorderLayout());

        // 1. Tabela
        String[] colunas = {"ID", "Nome", "Login", "Perfil"};
        modeloTabela = new DefaultTableModel(colunas, 0);
        tabelaUsuarios = new JTable(modeloTabela);
        add(new JScrollPane(tabelaUsuarios), BorderLayout.CENTER);

        // 2. Botões
        JPanel painelBotoes = new JPanel(new FlowLayout());
        JButton btnNovo = new JButton("Novo Usuário");
        JButton btnEditar = new JButton("Editar");
        JButton btnExcluir = new JButton("Excluir");

        // Botão de Excluir em vermelho com a correção visual!
        btnExcluir.setBackground(new Color(33, 33, 33));
        btnExcluir.setForeground(Color.BLACK);
        btnExcluir.setOpaque(true);
        btnExcluir.setContentAreaFilled(true);

        painelBotoes.add(btnNovo);
        painelBotoes.add(btnEditar);
        painelBotoes.add(btnExcluir);
        add(painelBotoes, BorderLayout.SOUTH);

        // 3. Ações
        btnNovo.addActionListener(e -> abrirFormulario(null));

        btnEditar.addActionListener(e -> {
            int linha = tabelaUsuarios.getSelectedRow();
            if (linha == -1) {
                JOptionPane.showMessageDialog(this, "Selecione um utilizador para editar.");
                return;
            }
            Long id = (Long) modeloTabela.getValueAt(linha, 0);
            String nome = (String) modeloTabela.getValueAt(linha, 1);
            String login = (String) modeloTabela.getValueAt(linha, 2);
            String perfil = (String) modeloTabela.getValueAt(linha, 3);
            abrirFormulario(new Usuario(id, nome, login, "", perfil));
        });

        btnExcluir.addActionListener(e -> excluirUsuario());

        carregarDados();
    }

    private void carregarDados() {
        modeloTabela.setRowCount(0);
        List<Usuario> lista = service.listarTodos();
        for (Usuario u : lista) {
            modeloTabela.addRow(new Object[]{u.getId(), u.getNome(), u.getLogin(), u.getPerfil()});
        }
    }

    private void abrirFormulario(Usuario usuario) {
        new UsuarioFormView(this, usuario, this::carregarDados).setVisible(true);
    }

    private void excluirUsuario() {
        int linha = tabelaUsuarios.getSelectedRow();
        if (linha == -1) {
            JOptionPane.showMessageDialog(this, "Selecione um utilizador na tabela para excluir.");
            return;
        }

        Long id = (Long) modeloTabela.getValueAt(linha, 0);
        String nome = (String) modeloTabela.getValueAt(linha, 1);

        // Trava de Segurança da Exclusão
        int confirmacao = JOptionPane.showConfirmDialog(this,
                "Tem a certeza absoluta que deseja excluir fisicamente o utilizador '" + nome + "'?\nEsta ação não pode ser desfeita.",
                "Aviso LGPD - Exclusão de Dados",
                JOptionPane.YES_NO_OPTION,
                JOptionPane.WARNING_MESSAGE);

        if (confirmacao == JOptionPane.YES_OPTION) {
            new Thread(() -> {
                try {
                    service.excluir(id);
                    SwingUtilities.invokeLater(() -> {
                        carregarDados();
                        JOptionPane.showMessageDialog(this, "Registo eliminado com sucesso da base de dados.");
                    });
                } catch (Exception ex) {
                    SwingUtilities.invokeLater(() -> JOptionPane.showMessageDialog(this, "Erro: " + ex.getMessage()));
                }
            }).start();
        }
    }
}