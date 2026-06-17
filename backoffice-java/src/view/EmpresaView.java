package br.com.unialfa.view;

import br.com.unialfa.service.EmpresaService; // IMPORTANTE: Agora usamos o Service e não o DAO

import javax.swing.*;
import java.awt.*;

public class EmpresaView extends JFrame {

    // Declaração dos componentes visuais
    private JTextField txtRazaoSocial;
    private JTextField txtCnpj;
    private JTextField txtEmail;
    private JTextField txtTelefone;
    private JPasswordField txtSenha;
    private JButton btnGuardar;

    // A nossa View agora só fala com o Cérebro (Service)
    private EmpresaService service = new EmpresaService();

    public EmpresaView() {
        // Configurações básicas da janela
        setTitle("Registo de Empresas - Backoffice UniALFA");
        setSize(400, 290);
        setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
        setLocationRelativeTo(null);
        setLayout(new GridLayout(7, 2, 10, 10));

        // Inicialização dos componentes
        txtRazaoSocial = new JTextField();
        txtCnpj = new JTextField();
        txtEmail = new JTextField();
        txtTelefone = new JTextField();
        txtSenha = new JPasswordField();
        btnGuardar = new JButton("Guardar Empresa");

        // Adicionando os componentes à janela
        add(new JLabel("  Razão Social:")); add(txtRazaoSocial);
        add(new JLabel("  CNPJ:")); add(txtCnpj);
        add(new JLabel("  E-mail:")); add(txtEmail);
        add(new JLabel("  Telefone:")); add(txtTelefone);
        add(new JLabel("  Senha (portal):")); add(txtSenha);
        add(new JLabel("")); add(btnGuardar);

        // Ação do botão Guardar (usando Lambda para ficar mais moderno)
        btnGuardar.addActionListener(e -> guardarDados());
    }

    // Método refatorado: A Tela recolhe os textos e manda para o Service julgar
    private void guardarDados() {
        String razao = txtRazaoSocial.getText();
        String cnpj = txtCnpj.getText();
        String email = txtEmail.getText();
        String telefone = txtTelefone.getText();
        String senha = new String(txtSenha.getPassword());

        // Abre uma Thread para que a interface não trave enquanto o Java pensa/salva
        new Thread(() -> {
            try {
                // 1. Manda os textos crus para o Cérebro validar e salvar
                service.salvar(razao, cnpj, email, telefone, senha);

                // 2. Se deu tudo certo, volta para a tela para mostrar a mensagem
                SwingUtilities.invokeLater(() -> {
                    JOptionPane.showMessageDialog(this, "Empresa guardada com sucesso!", "Sucesso", JOptionPane.INFORMATION_MESSAGE);
                    limparCampos();
                });

            } catch (Exception ex) {
                // 3. Se o Service lançar erro (ex: campo vazio), mostra o aviso
                SwingUtilities.invokeLater(() ->
                        JOptionPane.showMessageDialog(this, ex.getMessage(), "Atenção", JOptionPane.WARNING_MESSAGE)
                );
            }
        }).start();
    }

    private void limparCampos() {
        txtRazaoSocial.setText("");
        txtCnpj.setText("");
        txtEmail.setText("");
        txtTelefone.setText("");
        txtSenha.setText("");
        txtRazaoSocial.requestFocus();
    }

}