package br.com.unialfa.view;

import br.com.unialfa.model.Empresa;
import br.com.unialfa.service.VagaService; // Agora usamos o Service!

import javax.swing.*;
import java.awt.*;

public class VagaView extends JFrame {
    private JTextField txtTitulo = new JTextField();
    private JTextField txtDescricao = new JTextField();
    private JTextField txtBolsa = new JTextField();
    private JComboBox<Empresa> cbEmpresas = new JComboBox<>();
    private JButton btnSalvar = new JButton("Salvar Vaga");

    private VagaService service = new VagaService(); // Conexão com o Cérebro

    public VagaView() {
        setTitle("Nova Vaga de Estágio");
        setSize(400, 300);
        setLayout(new GridLayout(5, 2, 10, 10));
        setLocationRelativeTo(null);
        setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE); // Corrigido para a tela não fechar o sistema todo!

        // Preenche o Dropdown usando o Service
        service.listarEmpresasAprovadas().forEach(empresa -> cbEmpresas.addItem(empresa));

        add(new JLabel(" Título:")); add(txtTitulo);
        add(new JLabel(" Descrição:")); add(txtDescricao);
        add(new JLabel(" Bolsa (R$):")); add(txtBolsa);
        add(new JLabel(" Empresa:")); add(cbEmpresas);
        add(new JLabel("")); add(btnSalvar);

        btnSalvar.addActionListener(e -> salvar());
    }

    private void salvar() {
        // Pega apenas os textos crus da tela
        String titulo = txtTitulo.getText();
        String descricao = txtDescricao.getText();
        String bolsa = txtBolsa.getText();
        Empresa selecionada = (Empresa) cbEmpresas.getSelectedItem();

        // Roda em segundo plano para não travar
        new Thread(() -> {
            try {
                // O Service faz todo o trabalho duro e as validações
                service.salvar(titulo, descricao, bolsa, selecionada);

                SwingUtilities.invokeLater(() -> {
                    JOptionPane.showMessageDialog(this, "Vaga cadastrada com sucesso!");
                    dispose(); // Fecha a tela
                });
            } catch (Exception ex) {
                // Se o utilizador digitar letras na bolsa ou deixar vazio, mostra o aviso
                SwingUtilities.invokeLater(() ->
                        JOptionPane.showMessageDialog(this, ex.getMessage(), "Atenção", JOptionPane.WARNING_MESSAGE)
                );
            }
        }).start();
    }
}