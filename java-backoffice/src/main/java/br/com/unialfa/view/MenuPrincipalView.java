package br.com.unialfa.view;

import br.com.unialfa.dao.DashboardDAO;
import br.com.unialfa.model.Usuario;
import javax.swing.*;
import java.awt.*;

public class MenuPrincipalView extends JFrame {

    private Usuario usuarioLogado;
    private DashboardDAO dao = new DashboardDAO();

    public MenuPrincipalView(Usuario usuario) {
        this.usuarioLogado = usuario;

        setTitle("Dashboard - Portal de Estágios UniALFA | Logado como: " + usuarioLogado.getNome());
        setExtendedState(MAXIMIZED_BOTH); // Abre em tela cheia
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setLocationRelativeTo(null);
        setLayout(new BorderLayout(10, 10));

        // --- 1. A MÁGICA DO DROPDOWN: CRIANDO A BARRA DE MENUS SUPERIOR ---
        criarMenuSuperior();

        // --- 2. PAINEL DE ESTATÍSTICAS (DASHBOARD) ---
        // Mantemos as estatísticas visíveis para a tela não ficar vazia
        JPanel painelStats = new JPanel(new GridLayout(1, 3, 15, 0));
        painelStats.setBorder(BorderFactory.createTitledBorder("Estatísticas em Tempo Real (Sessão: " + usuarioLogado.getPerfil() + ")"));
        painelStats.setPreferredSize(new Dimension(800, 120));

        painelStats.add(criarCard("Alunos Importados", dao.getTotalAlunos()));
        painelStats.add(criarCard("Empresas Registadas", dao.getTotalEmpresas()));
        painelStats.add(criarCard("Vagas Ativas", dao.getTotalVagas()));

        // Adiciona as estatísticas na parte de cima (logo abaixo da barra de menu)
        add(painelStats, BorderLayout.NORTH);

        // --- 3. ÁREA DE TRABALHO (FUNDO DA TELA) ---
        JPanel painelFundo = new JPanel();
        painelFundo.setBackground(new Color(240, 244, 248)); // Um cinza/azulado bem suave e profissional
        add(painelFundo, BorderLayout.CENTER);
    }

    // Método que constrói a barra de menus categorizada
    private void criarMenuSuperior() {
        JMenuBar barraDeMenu = new JMenuBar();

        // --- CATEGORIAS PRINCIPAIS (JMenu) ---
        JMenu menuEmpresas = new JMenu("Empresas");
        JMenu menuAlunos = new JMenu("Alunos");
        JMenu menuVagas = new JMenu("Vagas & Candidaturas");
        JMenu menuRelatorios = new JMenu("Relatórios");
        JMenu menuSistema = new JMenu("Sistema");

        // --- ITENS DE 'EMPRESAS' ---
        JMenuItem itemNovaEmpresa = new JMenuItem("Cadastrar Nova Empresa");
        JMenuItem itemGerirEmpresas = new JMenuItem("Gerenciar Empresas (Aprovar/Bloquear)");
        menuEmpresas.add(itemNovaEmpresa);
        menuEmpresas.add(itemGerirEmpresas);

        // --- ITENS DE 'ALUNOS' ---
        JMenuItem itemImportarAlunos = new JMenuItem("Importar Alunos via Arquivo (.txt)");
        JMenuItem itemGerirAlunos = new JMenuItem("Visualizar e Gerenciar Alunos");
        menuAlunos.add(itemImportarAlunos);
        menuAlunos.add(itemGerirAlunos);

        // --- ITENS DE 'VAGAS & CANDIDATURAS' ---
        JMenuItem itemNovaVaga = new JMenuItem("Cadastrar Vaga de Estágio");
        JMenuItem itemGerirVagas = new JMenuItem("Visualizar Todas as Vagas");
        JMenuItem itemCandidaturas = new JMenuItem("Gestão de Candidaturas");
        menuVagas.add(itemNovaVaga);
        menuVagas.add(itemGerirVagas);
        menuVagas.addSeparator(); // Cria uma linha divisória charmosa no menu
        menuVagas.add(itemCandidaturas);

        // --- ITENS DE 'RELATÓRIOS' ---
        JMenuItem itemExportar = new JMenuItem("Central de Relatórios (.txt)");
        menuRelatorios.add(itemExportar);

        // --- ITENS DE 'SISTEMA' ---
        JMenuItem itemUsuarios = new JMenuItem("Gestão de Utilizadores (ADMIN)");
        JMenuItem itemSair = new JMenuItem("Sair do Sistema (Logout)");
        menuSistema.add(itemUsuarios);
        menuSistema.addSeparator();
        menuSistema.add(itemSair);

        // --- LÓGICA DE PERMISSÕES (OPERADOR VS ADMIN) ---
        // A mesma lógica de segurança, mas agora aplicada aos Itens do Menu!
        if (usuarioLogado.getPerfil().equals("OPERADOR")) {
            itemGerirEmpresas.setEnabled(false);
            itemImportarAlunos.setEnabled(false);
            itemUsuarios.setVisible(false);
            itemCandidaturas.setText("Visualizar Candidaturas (Somente Leitura)");
        }

        // --- AÇÕES DOS CLIQUES ---
        itemNovaEmpresa.addActionListener(e -> new EmpresaView().setVisible(true));
        itemGerirEmpresas.addActionListener(e -> new GestaoEmpresasView().setVisible(true));
        itemImportarAlunos.addActionListener(e -> new ImportacaoView().setVisible(true));
        itemGerirAlunos.addActionListener(e -> new GestaoAlunosView().setVisible(true));
        itemNovaVaga.addActionListener(e -> new VagaView().setVisible(true));
        itemGerirVagas.addActionListener(e -> new GestaoVagasView().setVisible(true));
        itemCandidaturas.addActionListener(e -> new GestaoCandidaturasView().setVisible(true));
        itemExportar.addActionListener(e -> new RelatoriosView().setVisible(true));
        itemUsuarios.addActionListener(e -> new GestaoUsuariosView().setVisible(true));

        // Ação de Logout (Fecha o menu e abre o Login novamente)
        itemSair.addActionListener(e -> {
            new LoginView().setVisible(true);
            this.dispose();
        });

        // Adiciona as categorias na Barra principal
        barraDeMenu.add(menuEmpresas);
        barraDeMenu.add(menuAlunos);
        barraDeMenu.add(menuVagas);
        barraDeMenu.add(menuRelatorios);
        barraDeMenu.add(menuSistema);

        // Comando do JFrame que "cola" a barra lá no topo da janela
        setJMenuBar(barraDeMenu);
    }

    private JPanel criarCard(String titulo, int total) {
        JPanel card = new JPanel(new BorderLayout());
        card.setBackground(Color.WHITE);
        card.setBorder(BorderFactory.createLineBorder(new Color(220, 220, 220), 1));

        JLabel lblTitulo = new JLabel(titulo, SwingConstants.CENTER);
        lblTitulo.setFont(new Font("Arial", Font.PLAIN, 14));
        lblTitulo.setBorder(BorderFactory.createEmptyBorder(15, 0, 0, 0));

        JLabel lblTotal = new JLabel(String.valueOf(total), SwingConstants.CENTER);
        lblTotal.setFont(new Font("Arial", Font.BOLD, 40));
        lblTotal.setForeground(new Color(33, 33, 33)); // Cor combinando com nosso novo visual

        card.add(lblTitulo, BorderLayout.NORTH);
        card.add(lblTotal, BorderLayout.CENTER);
        return card;
    }
}