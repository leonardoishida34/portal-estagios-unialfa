package br.com.unialfa.view;

import br.com.unialfa.model.Usuario;
import br.com.unialfa.service.UsuarioService;

import javax.swing.*;
import java.awt.*;
import java.awt.event.KeyAdapter;
import java.awt.event.KeyEvent;

public class LoginView extends JFrame {

    private JTextField txtLogin;
    private JPasswordField txtSenha;
    private JButton btnEntrar;

    // Agora a tela usa o nosso Cérebro de Usuários
    private UsuarioService service = new UsuarioService();

    public LoginView() {
        setTitle("Autenticação - Portal de Estágios");
        setSize(350, 250);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setLocationRelativeTo(null);
        setResizable(false);
        setLayout(new BorderLayout());

        JLabel lblTitulo = new JLabel("Acesso ao Sistema", SwingConstants.CENTER);
        lblTitulo.setFont(new Font("Arial", Font.BOLD, 18));
        lblTitulo.setBorder(BorderFactory.createEmptyBorder(20, 0, 10, 0));
        add(lblTitulo, BorderLayout.NORTH);

        JPanel painelForm = new JPanel(new GridLayout(4, 1, 5, 5));
        painelForm.setBorder(BorderFactory.createEmptyBorder(10, 40, 10, 40));

        txtLogin = new JTextField();
        txtSenha = new JPasswordField();
        btnEntrar = new JButton("Login");

        // Estilo do Botão
        btnEntrar.setBackground(new Color(33, 33, 33));
        btnEntrar.setForeground(Color.BLACK); // Cor do texto "Login"
        btnEntrar.setOpaque(true);
        btnEntrar.setContentAreaFilled(true);
        btnEntrar.setFont(new Font("Arial", Font.BOLD, 12));

        painelForm.add(new JLabel("Usuário:"));
        painelForm.add(txtLogin);
        painelForm.add(new JLabel("Senha:"));
        painelForm.add(txtSenha);

        add(painelForm, BorderLayout.CENTER);

        JPanel painelBotao = new JPanel();
        painelBotao.setBorder(BorderFactory.createEmptyBorder(0, 0, 20, 0));
        painelBotao.add(btnEntrar);
        add(painelBotao, BorderLayout.SOUTH);

        btnEntrar.addActionListener(e -> realizarLogin());

        txtSenha.addKeyListener(new KeyAdapter() {
            @Override
            public void keyPressed(KeyEvent e) {
                if (e.getKeyCode() == KeyEvent.VK_ENTER) {
                    realizarLogin();
                }
            }
        });
    }

    private void realizarLogin() {
        String login = txtLogin.getText();
        String senha = new String(txtSenha.getPassword());

        try {
            // O Service vai verificar se está vazio e tentar buscar no banco
            Usuario usuarioLogado = service.autenticar(login, senha);

            if (usuarioLogado != null) {
                // SUCESSO! Passamos o utilizador completo para o Menu Principal
                MenuPrincipalView menu = new MenuPrincipalView(usuarioLogado);
                menu.setVisible(true);
                this.dispose();
            } else {
                JOptionPane.showMessageDialog(this, "Credenciais inválidas! Tente novamente.", "Acesso Negado", JOptionPane.ERROR_MESSAGE);
                txtSenha.setText("");
                txtSenha.requestFocus();
            }
        } catch (Exception ex) {
            // Se o utilizador não digitar nada, o Service lança o erro aqui
            JOptionPane.showMessageDialog(this, ex.getMessage(), "Atenção", JOptionPane.WARNING_MESSAGE);
        }
    }
}