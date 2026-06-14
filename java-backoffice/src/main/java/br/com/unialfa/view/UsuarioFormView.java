package br.com.unialfa.view;

import br.com.unialfa.model.Usuario;
import br.com.unialfa.service.UsuarioService;

import javax.swing.*;
import java.awt.*;

public class UsuarioFormView extends JDialog {

    private JTextField txtNome = new JTextField();
    private JTextField txtLogin = new JTextField();
    private JPasswordField txtSenha = new JPasswordField();
    private JComboBox<String> cbPerfil = new JComboBox<>(new String[]{"OPERADOR", "ADMIN"});
    private JButton btnSalvar = new JButton("Salvar Utilizador");

    private Usuario usuarioExistente = null;
    private UsuarioService service = new UsuarioService();
    private Runnable aoSalvarCallback;

    public UsuarioFormView(JFrame pai, Usuario usuario, Runnable aoSalvarCallback) {
        super(pai, true);
        this.usuarioExistente = usuario;
        this.aoSalvarCallback = aoSalvarCallback;

        setTitle(usuario == null ? "Cadastrar Novo Utilizador" : "Editar Utilizador");
        setSize(400, 300);
        setLocationRelativeTo(pai);
        setLayout(new GridLayout(5, 2, 10, 10));

        // Correção visual do botão
        btnSalvar.setBackground(new Color(0, 102, 204));
        btnSalvar.setForeground(Color.BLACK);
        btnSalvar.setOpaque(true);
        btnSalvar.setContentAreaFilled(true);

        add(new JLabel("  Nome Completo:")); add(txtNome);
        add(new JLabel("  Login de Acesso:")); add(txtLogin);
        add(new JLabel("  Palavra-passe:")); add(txtSenha);
        add(new JLabel("  Perfil de Acesso:")); add(cbPerfil);
        add(new JLabel("")); add(btnSalvar);

        if (usuario != null) {
            txtNome.setText(usuario.getNome());
            txtLogin.setText(usuario.getLogin());
            cbPerfil.setSelectedItem(usuario.getPerfil());
            // A senha fica em branco por segurança na edição
        }

        btnSalvar.addActionListener(e -> salvar());
    }

    private void salvar() {
        String nome = txtNome.getText();
        String login = txtLogin.getText();
        String senha = new String(txtSenha.getPassword());
        String perfil = (String) cbPerfil.getSelectedItem();
        boolean isEdicao = (usuarioExistente != null);
        Long id = isEdicao ? usuarioExistente.getId() : null;

        new Thread(() -> {
            try {
                service.salvarOuEditar(id, nome, login, senha, perfil, isEdicao);

                SwingUtilities.invokeLater(() -> {
                    JOptionPane.showMessageDialog(this, "Utilizador guardado com sucesso!");
                    if (aoSalvarCallback != null) aoSalvarCallback.run();
                    dispose();
                });
            } catch (Exception ex) {
                SwingUtilities.invokeLater(() ->
                        JOptionPane.showMessageDialog(this, ex.getMessage(), "Atenção", JOptionPane.WARNING_MESSAGE)
                );
            }
        }).start();
    }
}