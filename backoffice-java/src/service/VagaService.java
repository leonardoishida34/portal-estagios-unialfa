package br.com.unialfa.service;

import br.com.unialfa.dao.EmpresaDAO;
import br.com.unialfa.dao.VagaDAO;
import br.com.unialfa.model.Empresa;
import br.com.unialfa.model.Vaga;

import java.util.List;


public class VagaService {

    private VagaDAO vagaDAO = new VagaDAO();
    private EmpresaDAO empresaDAO = new EmpresaDAO();
    private br.com.unialfa.dao.CandidaturaDAO candidaturaDAO = new br.com.unialfa.dao.CandidaturaDAO();
    // Busca as empresas para preencher a caixinha (Dropdown)
    public List<Empresa> listarEmpresasAprovadas() {
        return empresaDAO.listarAprovadas();
    }

    // Regra de Negócio para Salvar a Vaga
    public void salvar(String titulo, String descricao, String bolsaTexto, Empresa empresaSelecionada) throws Exception {
        if (titulo == null || titulo.trim().isEmpty()) throw new Exception("O título da vaga é obrigatório.");
        if (descricao == null || descricao.trim().isEmpty()) throw new Exception("A descrição da vaga é obrigatória.");
        if (bolsaTexto == null || bolsaTexto.trim().isEmpty()) throw new Exception("O valor da bolsa é obrigatório.");
        if (empresaSelecionada == null) throw new Exception("Selecione uma empresa válida.");

        double valorBolsa;
        try {
            valorBolsa = Double.parseDouble(bolsaTexto.replace(",", "."));
        } catch (NumberFormatException e) {
            throw new Exception("O valor da bolsa deve ser numérico (ex: 1200.50).");
        }

        Vaga v = new Vaga();
        v.setTitulo(titulo.trim());
        v.setDescricao(descricao.trim());
        v.setBolsa(valorBolsa);
        v.setEmpresaId(empresaSelecionada.getId());
        v.setAtiva(true);

        vagaDAO.salvar(v);
    }

    // NOVO MÉTODO: Faz a ponte para listar as vagas na tela de Gestão
    public List<Object[]> listarTodasComEmpresa() {
        return vagaDAO.listarTodasComEmpresa();
    }

    // Regra para Excluir em Cascata (LGPD)
    public void excluir(Long id) throws Exception {
        if (id == null) {
            throw new Exception("ID da vaga inválido para exclusão.");
        }

        // 1. Apaga as candidaturas amarradas a esta vaga
        candidaturaDAO.excluirPorVaga(id);

        // 2. Apaga a vaga física
        vagaDAO.excluir(id);
    }
}