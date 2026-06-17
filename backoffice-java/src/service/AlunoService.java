package br.com.unialfa.service;

import br.com.unialfa.dao.AlunoDAO;
import br.com.unialfa.dao.CandidaturaDAO;
import br.com.unialfa.model.Aluno;

import java.util.List;

public class AlunoService {

    private AlunoDAO dao = new AlunoDAO();
    // AQUI ESTÁ A LINHA NO LUGAR CORRETO:
    private CandidaturaDAO candidaturaDAO = new CandidaturaDAO();

    public void salvarOuEditar(String ra, String nome, String curso, boolean isEdicao) throws Exception {
        if (ra == null || ra.trim().isEmpty() || nome == null || nome.trim().isEmpty() || curso == null || curso.trim().isEmpty()) {
            throw new Exception("Por favor, preencha todos os campos obrigatórios.");
        }
        if (!isEdicao && ra.trim().length() != 6) {
            throw new Exception("O RA deve conter pelo menos 6 dígitos.");
        }

        Aluno aluno = new Aluno(ra.trim(), nome.trim(), curso.trim());

        if (isEdicao) {
            dao.atualizarDados(aluno);
        } else {
            dao.salvar(aluno);
        }
    }

    public List<Aluno> listarTodos() {
        return dao.listarTodos();
    }

    public void atualizarAptidao(String ra, boolean apto) throws Exception {
        if (ra == null || ra.trim().isEmpty()) {
            throw new Exception("RA inválido para atualização.");
        }
        dao.atualizarAptidao(ra, apto);
    }

    // NOVA REGRA: Excluir em Cascata (LGPD)
    public void excluir(String ra) throws Exception {
        if (ra == null || ra.trim().isEmpty()) {
            throw new Exception("RA inválido para exclusão.");
        }

        // 1. Apaga todo o histórico de candidaturas deste aluno (Cascata)
        candidaturaDAO.excluirPorAluno(ra);

        // 2. Apaga o registo físico do aluno
        dao.excluir(ra);
    }
}