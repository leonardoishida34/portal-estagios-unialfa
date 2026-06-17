package br.com.unialfa;

import br.com.unialfa.view.LoginView; // Importa a nossa nova tela

import javax.swing.*;

public class Main {
    public static void main(String[] args) {

        // Estilo nativo do sistema operativo
        try {
            UIManager.setLookAndFeel(UIManager.getSystemLookAndFeelClassName());
        } catch (Exception e) {
            e.printStackTrace();
        }

        SwingUtilities.invokeLater(new Runnable() {
            @Override
            public void run() {
                // AGORA O SISTEMA INICIA PELA TELA DE LOGIN!
                LoginView login = new LoginView();
                login.setVisible(true);
            }
        });
    }
}