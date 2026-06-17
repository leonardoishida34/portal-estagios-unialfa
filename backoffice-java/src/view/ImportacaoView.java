package br.com.unialfa.view;

import br.com.unialfa.util.ImportadorAlunos;
import javax.swing.*;
import java.awt.event.ActionEvent;
import java.io.File;

public class ImportacaoView extends JFrame {
    private JButton btnSelecionar;

    public ImportacaoView() {
        setTitle("Importar Alunos");
        setSize(300, 150);
        setLayout(new java.awt.FlowLayout());
        setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);

        btnSelecionar = new JButton("Selecionar Ficheiro .txt");
        add(btnSelecionar);

        btnSelecionar.addActionListener((ActionEvent e) -> {
            JFileChooser fileChooser = new JFileChooser();
            int resultado = fileChooser.showOpenDialog(this);

            if (resultado == JFileChooser.APPROVE_OPTION) {
                File arquivo = fileChooser.getSelectedFile();

                // --- O SEU TRECHO DE CÓDIGO ENTRA AQUI ---
                ImportadorAlunos imp = new ImportadorAlunos();
                imp.importar(arquivo);
                System.out.println("Importação concluída!");
                // ------------------------------------------

                JOptionPane.showMessageDialog(this, "Importação finalizada com sucesso!");
            }
        });
    }
}